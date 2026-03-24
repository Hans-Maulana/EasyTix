<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                "name" => $ticket->event_schedule->event->name,
                "type" => $ticket->ticket_type->name,
                "quantity" => $request->quantity,
                "price" => $ticket->price,
                "image" => asset('assets/img/easytix_login_bg.png') // Default image
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
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => 'paid', // Simulated success
            'payment_method' => $request->payment_method ?? 'QRIS'
        ]);

        foreach($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'ticket_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'qr_code' => Str::upper(Str::random(12)) // Simulated unique QR data
            ]);
        }

        session()->forget('cart');

        return redirect()->route('user.myTickets')->with('success', 'Pembayaran Berhasil! Tiket Anda telah diterbitkan.');
    }

    public function myTickets()
    {
        $orders = Order::with(['orderItems.ticket.event_schedule.event', 'orderItems.ticket.ticket_type'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('user.my-tickets', compact('orders'));
    }
}
