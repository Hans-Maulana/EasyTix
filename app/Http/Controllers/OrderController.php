<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    public function index()
    {
        $events = Event::whereHas('event_schedule.tickets')
            ->with(['event_schedule.tickets.ticket_type'])
            ->where('status', 'active')
            ->get();
        return view('user.buy-tickets', compact('events'));
    }

    public function addToCart(Request $request)
    {
        $ticket = Ticket::with(['ticket_type', 'event_schedule.event'])->findOrFail($request->ticket_id);
        
        $cart = session()->get('cart', []);

        if(isset($cart[$request->ticket_id])) {
            $cart[$request->ticket_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->ticket_id] = [
                "name"       => $ticket->event_schedule->event->name,
                "events_id"  => $ticket->event_schedule->event->id,
                "type"       => $ticket->ticket_type->name,
                "quantity"   => $request->quantity,
                "price"      => $ticket->price,
                "image"      => asset('assets/img/easytix_login_bg.png')
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Tiket berhasil ditambahkan ke keranjang!');
    }

    public function viewCart()
    {
        return view('user.cart');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Keranjang berhasil diperbarui!');
        }
    }

    public function removeFromCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Tiket dihapus dari keranjang!');
        }
    }

    public function checkout()
    {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->route('user.buyTickets')->with('error', 'Keranjang masih kosong!');

        return view('user.checkout', compact('cart'));
    }

    public function processOrder(Request $request)
    {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->route('user.buyTickets');

        $total = 0;
        $firstEventId = null;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
            if (!$firstEventId) {
                // Coba ambil dari cart data, atau langsung query dari ticket
                if (!empty($details['events_id'])) {
                    $firstEventId = $details['events_id'];
                } else {
                    // Fallback: ambil event id dari ticket
                    $ticket = Ticket::with('event_schedule.event')->find($id);
                    if ($ticket && $ticket->event_schedule && $ticket->event_schedule->event) {
                        $firstEventId = $ticket->event_schedule->event->id;
                    }
                }
            }
        }

        // Jika masih null, ambil event pertama yang ada
        if (!$firstEventId) {
            $anyEvent = \App\Models\Event::first();
            $firstEventId = $anyEvent ? $anyEvent->id : null;
        }

        $paymentMethod = $request->payment_method ?? 'QRIS';

        // Generate ID manual karena kolom id adalah varchar(35), bukan auto-increment
        $year = date('Y');
        $lastOrder = Order::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        if ($lastOrder) {
            $lastNum = (int) substr($lastOrder->id, -3);
            $newNum = str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNum = '001';
        }
        $orderId = "ORD-{$year}-{$newNum}";

        // Insert ke tabel orders yang sudah ada (users_id, events_id, total_amount)
        $order = Order::create([
            'id'          => $orderId,
            'users_id'    => auth()->id(),
            'events_id'   => $firstEventId,
            'total_amount' => $total,
        ]);

        // Simpan snapshot cart ke session untuk ditampilkan di my-tickets
        $orderSnapshot = [
            'id'             => $order->id,
            'payment_method' => $paymentMethod,
            'total_amount'   => $total,
            'created_at'     => now()->format('d M Y, H:i'),
            'items'          => [],
        ];

        foreach($cart as $ticketId => $details) {
            for ($i = 0; $i < $details['quantity']; $i++) {
                $orderDetailId = 'DET-' . date('Y') . '-' . strtoupper(Str::random(6));

                $qrString = "VERIFY-" . $orderDetailId;
                $fileName = 'qr_' . time() . '_' . $orderDetailId . '.svg';
                $qrPath = 'qrcodes/' . $fileName;

                if(!file_exists(public_path('qrcodes'))) {
                    mkdir(public_path('qrcodes'), 0777, true);
                }
                
                QrCode::size(200)->generate($qrString, public_path('qrcodes/'.$fileName));

                $orderDetail = OrderDetail::create([
                    'id'          => $orderDetailId,
                    'owner_name'  => auth()->user()->name ?? 'Guest',
                    'status'      => 'valid',
                    'tickets_id'  => $ticketId,
                    'orders_id'   => $order->id,
                    'qr_code'     => $qrPath,
                ]);

                $orderSnapshot['items'][] = [
                    'ticket_id'    => $ticketId,
                    'name'         => $details['name'],
                    'type'         => $details['type'],
                    'quantity'     => 1, // Individual ticket
                    'price'        => $details['price'],
                    'subtotal'     => $details['price'], // Subtotal for one ticket
                    'qr_code'      => $qrPath,
                    'qr_string'    => $qrString,
                    'ticket_index' => $i + 1,
                    'total_qty'    => $details['quantity']
                ];
            }
        }

        // Tambahkan ke histori pesanan di session
        $orderHistory = session()->get('order_history', []);
        $orderHistory[] = $orderSnapshot;
        session()->put('order_history', $orderHistory);

        session()->forget('cart');

        $methodLabel = $paymentMethod === 'QRIS' ? 'QRIS' : 'Virtual Account';
        return redirect()->route('user.myTickets')
            ->with('success', "Pembayaran Berhasil via $methodLabel! Tiket Anda telah diterbitkan.");
    }

    public function myTickets()
    {
        $orderHistory = session()->get('order_history', []);
        return view('user.my-tickets', compact('orderHistory'));
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('user.buyTickets')->with('success', 'Keranjang berhasil direset. Silakan tambah tiket kembali.');
    }
}

