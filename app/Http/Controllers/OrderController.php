<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class OrderController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'active')
            ->whereHas('event_schedule.tickets')
            ->with(['event_schedule.tickets'])
            ->get()
            ->map(function ($event) {
                $event->min_price = $event->event_schedule->flatMap->tickets->min('price');

                $schedules = $event->event_schedule->sortBy('event_date');
                if($schedules->count() > 1) {
                    $start = \Carbon\Carbon::parse($schedules->first()->event_date);
                    $end = \Carbon\Carbon::parse($schedules->last()->event_date);
                    if($start->format('M Y') == $end->format('M Y')) {
                        $event->date_display = $start->format('d') . ' - ' . $end->format('d F Y');
                    } else {
                        $event->date_display = $start->format('d M') . ' - ' . $end->format('d M Y');
                    }
                } else {
                    $event->date_display = \Carbon\Carbon::parse($schedules->first()->event_date)->translatedFormat('d F Y');
                }
                return $event;
            });
            
        $banners = $events->take(3); 
        return view('user.buy-tickets', compact('events', 'banners'));
    }

    public function showEventTickets($id)
    {
        $event = Event::with(['event_schedule.tickets.ticket_type'])
            ->findOrFail($id);
        
        return view('user.event-tickets', compact('event'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $ticket = Ticket::with(['ticket_type', 'event_schedule.event'])->findOrFail($request->ticket_id);
        $eventId = $ticket->event_schedule->event->id;
        $eventName = $ticket->event_schedule->event->name;
        
        $cart = session()->get('cart', []);
        if (!empty($cart)) {
            $firstItem = reset($cart);
            if ($firstItem['events_id'] != $eventId) {
                $cart = [];
                session()->flash('info', "Keranjang dibersihkan karena Anda memilih event baru: $eventName");
            }
        }

        if(isset($cart[$request->ticket_id])) {
            $newQuantity = $cart[$request->ticket_id]['quantity'] + $request->quantity;
        } else {
            $newQuantity = $request->quantity;
        }

        if ($newQuantity > $ticket->capacity) {
            return redirect()->back()->with('waiting_list_prompt', [
                'ticket_id' => $request->ticket_id,
                'quantity' => $request->quantity,
                'message' => 'Stok tiket tidak mencukupi atau sudah habis (Sisa: ' . $ticket->capacity . '). Apakah Anda ingin masuk ke Waiting List untuk mendapatkan informasi jika kuota bertambah?'
            ]);
        }

        if(isset($cart[$request->ticket_id])) {
            $cart[$request->ticket_id]['quantity'] = $newQuantity;
        } else {
            $totalSchedules = \App\Models\EventSchedule::where('event_id', $eventId)->count();
            $scheduleDate = \Carbon\Carbon::parse($ticket->event_schedule->event_date)->translatedFormat('d M Y');
            $typeName = $ticket->ticket_type->name;
            
            if ($totalSchedules > 1) {
                $schedules = \App\Models\EventSchedule::where('event_id', $eventId)->orderBy('event_date', 'asc')->pluck('id')->toArray();
                $dayIndex = array_search($ticket->event_schedule->id, $schedules) + 1;
                $typeName .= " - Day $dayIndex ($scheduleDate)";
            }

            $cart[$request->ticket_id] = [
                "name"       => $eventName,
                "events_id"  => $eventId,
                "type"       => $typeName,
                "quantity"   => $newQuantity,
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

        $ticketDetails = session()->get('ticket_details', []);

        return view('user.checkout', compact('cart', 'ticketDetails'));
    }

    public function saveDetails(Request $request)
    {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->route('user.buyTickets');

        // Simpan data tiket ke session agar bisa dipakai di step selanjutnya
        session()->put('ticket_details', $request->tickets);

        return redirect()->route('user.payment');
    }

    public function showPayment()
    {
        $cart = session()->get('cart');
        $ticketDetails = session()->get('ticket_details');

        if(!$cart || !$ticketDetails) return redirect()->route('user.checkout');

        return view('user.payment', compact('cart'));
    }

    public function vaPayment(Request $request)
    {
        $cart = session()->get('cart');
        $ticketDetails = session()->get('ticket_details');
        $bank = $request->query('bank');

        if(!$cart || !$ticketDetails || !$bank) return redirect()->route('user.payment');

        return view('user.va-payment', compact('cart', 'bank'));
    }

    public function processOrder(Request $request)
    {
        $cart = session()->get('cart');
        $ticketDetails = session()->get('ticket_details');

        if(!$cart || !$ticketDetails) return redirect()->route('user.buyTickets');

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
        $orders = Order::where('id', 'like', "ORD-{$year}-%")->get();
        
        $maxNum = 0;
        foreach ($orders as $o) {
            $idStr = trim($o->id);
            if (preg_match('/ORD-' . $year . '-(\d+)/', $idStr, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $newNum = str_pad($maxNum + 1, 3, '0', STR_PAD_LEFT);
        $orderId = "ORD-{$year}-{$newNum}";
        
        // Pastikan tidak ada duplikasi
        while (Order::where('id', $orderId)->exists()) {
            $maxNum++;
            $newNum = str_pad($maxNum + 1, 3, '0', STR_PAD_LEFT);
            $orderId = "ORD-{$year}-{$newNum}";
        }

        // Insert ke tabel orders yang sudah ada (users_id, events_id, total_amount)
        $order = Order::create([
            'id'             => $orderId,
            'users_id'       => auth()->id(),
            'events_id'      => $firstEventId,
            'total_amount'   => $total,
            'payment_method' => $paymentMethod,
        ]);

        foreach($cart as $ticketId => $details) {
            // Kurangi stok (capacity) tiket yang dibeli
            $ticketModel = Ticket::find($ticketId);
            if ($ticketModel) {
                // Pastikan tidak minus, meski idealnya divalidasi juga sebelum checkout
                $newCapacity = max(0, $ticketModel->capacity - $details['quantity']);
                $ticketModel->update(['capacity' => $newCapacity]);
            }

            for ($i = 0; $i < $details['quantity']; $i++) {
                $orderDetailId = 'DET-' . date('Y') . '-' . strtoupper(Str::random(6));

                $qrString = "VERIFY-" . $orderDetailId;
                $fileName = 'qr_' . time() . '_' . $i . '_' . $orderDetailId . '.png';
                $qrPath = 'qrcodes/' . $fileName;

                // Simpan ke storage/app/qrcodes (bukan public)
                $qrDir = storage_path('app/qrcodes');
                if (!file_exists($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }

                $qrResult = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrString)
                    ->encoding(new Encoding('UTF-8'))
                    ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                    ->size(200)
                    ->margin(10)
                    ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                    ->build();

                $qrResult->saveToFile($qrDir . '/' . $fileName);


                $ticketData = $ticketDetails[$ticketId][$i] ?? [];

                $orderDetail = OrderDetail::create([
                    'id'           => $orderDetailId,
                    'owner_name'   => $ticketData['name'] ?? (auth()->user()->name ?? 'Guest'),
                    'phone_number' => $ticketData['phone'] ?? null,
                    'email'        => $ticketData['email'] ?? null,
                    'gender'       => $ticketData['gender'] ?? null,
                    'age'          => $ticketData['age'] ?? null,
                    'status'       => 'valid',
                    'tickets_id'   => $ticketId,
                    'orders_id'    => $order->id,
                    'qr_code'      => $qrPath,
                    'ticket_code'  => $qrString, // Store the verification string
                ]);
            }
        }

        // Tambahkan Notifikasi ke Inbox
        Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'success',
            'title'   => 'Pembayaran Berhasil! 🎫',
            'message' => "Tiket untuk pesanan #{$order->id} telah diterbitkan. Silakan cek di menu Tiket Saya.",
            'link'    => route('user.myTickets'),
        ]);

        // Kumpulkan data tiket untuk email
        $emailOrderItems = [];
        $allOrderDetails = OrderDetail::where('orders_id', $order->id)->get();
        foreach ($allOrderDetails as $detail) {
            $ticket = Ticket::with(['ticket_type', 'event_schedule.event'])->find($detail->tickets_id);
            $emailOrderItems[] = [
                'event_name'  => $ticket && $ticket->event_schedule && $ticket->event_schedule->event
                                    ? $ticket->event_schedule->event->name : 'Unknown Event',
                'ticket_type' => $ticket && $ticket->ticket_type
                                    ? $ticket->ticket_type->name : 'Ticket',
                'owner_name'  => $detail->owner_name,
                'ticket_code' => $detail->ticket_code,
                'qr_code'     => $detail->qr_code,
            ];
        }

        // Kirim email dengan QR code ke user yang login
        try {
            Mail::to(auth()->user()->email)->send(
                new TicketPurchased($order, $emailOrderItems, auth()->user()->name)
            );
        } catch (\Exception $e) {
            // Log error tapi jangan gagalkan order
            \Log::error('Gagal mengirim email tiket: ' . $e->getMessage());
        }

        session()->forget('cart');
        session()->forget('ticket_details');

        $methodLabel = $paymentMethod === 'QRIS' ? 'QRIS' : 'Virtual Account';
        return redirect()->route('user.myTickets')
            ->with('success', "Pembayaran Berhasil via $methodLabel! Tiket Anda telah diterbitkan.");
    }

    public function myTickets()
    {
        $orders = Order::where('users_id', auth()->id())->orderBy('created_at', 'desc')->get();
        $orderHistory = [];

        foreach ($orders as $order) {
            $orderDetails = OrderDetail::where('orders_id', $order->id)->get();
            if ($orderDetails->isEmpty()) continue;

            $items = [];
            $groupedDetails = $orderDetails->groupBy('tickets_id');

            foreach ($groupedDetails as $ticketId => $detailsGroup) {
                // Fetch ticket to get event name and ticket type
                $ticket = Ticket::with(['ticket_type', 'event_schedule.event'])->find($ticketId);
                if (!$ticket) continue;
                
                $totalQty = $detailsGroup->count();
                $index = 1;
                foreach ($detailsGroup as $detail) {
                    $items[] = [
                        'ticket_id'    => $ticketId,
                        'name'         => $ticket->event_schedule->event->name ?? 'Unknown Event',
                        'type'         => $ticket->ticket_type->name ?? 'Ticket',
                        'quantity'     => 1,
                        'price'        => $ticket->price,
                        'subtotal'     => $ticket->price,
                        'qr_code'      => $detail->qr_code,
                        'qr_string'    => $detail->ticket_code,
                        'owner_name'   => $detail->owner_name,
                        'phone_number' => $detail->phone_number,
                        'email'        => $detail->email,
                        'gender'       => $detail->gender,
                        'age'          => $detail->age,
                        'status'       => $detail->status,
                        'ticket_index' => $index++,
                        'total_qty'    => $totalQty
                    ];
                }
            }

            if (empty($items)) continue;

            $orderHistory[] = [
                'id'             => $order->id,
                'payment_method' => $order->payment_method ?? 'QRIS',
                'total_amount'   => $order->total_amount,
                'created_at'     => $order->created_at->format('d M Y, H:i'),
                'items'          => $items,
            ];
        }

        return view('user.my-tickets', compact('orderHistory'));
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('user.buyTickets')->with('success', 'Keranjang berhasil direset. Silakan tambah tiket kembali.');
    }

    public function serveQrCode($filename)
    {
        $path = storage_path('app/qrcodes/' . basename($filename));
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path, ['Content-Type' => 'image/png']);
    }
}

