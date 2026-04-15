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
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
        
        $userId = auth()->id();
        $purchasedCount = OrderDetail::whereHas('order', function($q) use ($userId) {
            $q->where('users_id', $userId);
        })->whereHas('ticket.event_schedule', function($q) use ($id) {
            $q->where('event_id', $id);
        })->whereIn('status', ['valid', 'used'])->count();

        $pendingWLCount = \App\Models\WaitingList::where('user_id', $userId)
            ->whereHas('ticket.event_schedule', function($q) use ($id) {
                $q->where('event_id', $id);
            })
            ->whereIn('status', ['pending', 'requested'])
            ->sum('quantity');

        $approvedWLCount = \App\Models\WaitingList::where('user_id', $userId)
            ->whereHas('ticket.event_schedule', function($q) use ($id) {
                $q->where('event_id', $id);
            })
            ->where('status', 'approved')
            ->sum('quantity');

        // Untuk tampilan badge dan limit dasar JS
        // Kita hitung yang PASIF (sudah lunas + sedang antri). 
        // Yang approved akan dihitung oleh input UI.
        $totalUsedCount = $purchasedCount + $pendingWLCount + $approvedWLCount;
        $baseLimitCount = $purchasedCount + $pendingWLCount;

        $cart = session()->get('cart', []);
        $cartCount = 0;
        foreach($cart as $item) {
            if (isset($item['events_id']) && $item['events_id'] == $id) {
                $cartCount += $item['quantity'];
            }
        }
        $activeWaitingLists = \App\Models\WaitingList::where('user_id', $userId)
            ->whereIn('status', ['pending', 'requested', 'approved'])
            ->get()
            ->keyBy('ticket_id');
        
        return view('user.event-tickets', compact('event', 'purchasedCount', 'pendingWLCount', 'approvedWLCount', 'totalUsedCount', 'baseLimitCount', 'cartCount', 'activeWaitingLists'));
    }

    public function bulkAddToCart(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'tickets' => 'required|array',
            'tickets.*' => 'integer|min:0',
        ]);

        $maxTickets = 10;
        $userId = auth()->id();
        $eventId = $request->event_id;

        // 1. Hitung tiket yang sudah dimiliki (valid/used) UNTUK EVENT INI
        $purchasedCount = OrderDetail::whereHas('order', function($q) use ($userId) {
            $q->where('users_id', $userId);
        })->whereHas('ticket.event_schedule', function($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->whereIn('status', ['valid', 'used'])->count();

        // Count active waiting list entries
        $waitingListCount = \App\Models\WaitingList::where('user_id', $userId)
            ->whereHas('ticket.event_schedule', function($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->whereIn('status', ['pending', 'approved'])
            ->sum('quantity');

        $totalUsedCount = $purchasedCount + $waitingListCount;

        // 2. Hitung total tiket yang akan ditambahkan
        $addingCount = array_sum($request->tickets);
        
        if ($addingCount <= 0) {
            return redirect()->back()->with('error', 'Silakan masukkan jumlah tiket terlebih dahulu.');
        }

        // 3. Cek apakah penambahan ini melebihi limit
        if (($totalUsedCount + $addingCount) > $maxTickets) {
            $remaining = $maxTickets - $totalUsedCount;
            $msgPart = $waitingListCount > 0 ? " ($purchasedCount terbayar + $waitingListCount di antrian)" : "";
            $errMsg = $remaining > 0 
                ? "Batas pembelian maksimal adalah 10 tiket per event. Anda saat ini memiliki $totalUsedCount tiket$msgPart untuk event ini. Anda hanya bisa membeli $remaining tiket lagi."
                : "Batas pembelian maksimal adalah 10 tiket per event. Anda sudah mencapai batas maksimal (termasuk tiket di antrian) untuk event ini.";
            return redirect()->back()->with('error', $errMsg);
        }

        // Clear existing cart before processing bulk add
        session()->forget('cart');
        session()->forget('ticket_details');
        $cart = [];

        // Pre-fetch all approved/purchased waiting lists for this event
        $allWLs = \App\Models\WaitingList::whereHas('ticket.event_schedule', function($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })->whereIn('status', ['approved', 'purchased'])->get();

        foreach ($request->tickets as $ticketId => $qty) {
            if ($qty <= 0) continue;

            $ticketModel = Ticket::with(['ticket_type', 'event_schedule.event'])->find($ticketId);
            if (!$ticketModel) continue;

            // 1. Calculate Reserved Slots for this specific ticket (Approved but not yet purchased)
            $totalReserved = $allWLs->where('ticket_id', $ticketId)->where('status', 'approved')->sum('quantity');
            $userWL = $allWLs->where('ticket_id', $ticketId)->where('user_id', $userId)->where('status', 'approved')->first();
            $userReservedQty = $userWL ? $userWL->quantity : 0;

            // Public Availability = Total Capacity - All Reserved
            $availableForPublic = $ticketModel->capacity - $totalReserved;

            // Limit for THIS specific user = Public Pool + Their specific reservation
            $limitForThisUser = $availableForPublic + $userReservedQty;

            if ($qty > $limitForThisUser) {
                $reason = $userReservedQty > 0 
                    ? "Anda tidak dapat memesan lebih dari ({$userReservedQty} + {$availableForPublic}) tiket."
                    : "Maaf, sisa tiket yang tersedia untuk umum saat ini hanyalah {$availableForPublic} tiket.";
                
                return redirect()->back()->with('error', "Gagal menambah tiket '{$ticketModel->ticket_type->name}': " . $reason);
            }

            // Force WL user to buy AT LEAST their reserved amount
            if ($userReservedQty > 0 && $qty < $userReservedQty) {
                return redirect()->back()->with('error', "Anda memiliki jatah prioritas {$userReservedQty} tiket untuk '{$ticketModel->ticket_type->name}'. Anda harus mengambil minimal jumlah tersebut.");
            }

            // Prepare cart item
            $eventName = $ticketModel->event_schedule->event->name;
            $typeName = $ticketModel->ticket_type->name;
            $totalSchedules = \App\Models\EventSchedule::where('event_id', $eventId)->count();
            if ($totalSchedules > 1) {
                $schedules = \App\Models\EventSchedule::where('event_id', $eventId)->orderBy('event_date', 'asc')->pluck('id')->toArray();
                $dayIndex = array_search($ticketModel->event_schedule->id, $schedules) + 1;
                $typeName .= " - Day $dayIndex (" . \Carbon\Carbon::parse($ticketModel->event_schedule->event_date)->translatedFormat('d M Y') . ")";
            }

            $cart[$ticketId] = [
                "name"       => $eventName,
                "events_id"  => $eventId,
                "type"       => $typeName,
                "quantity"   => $qty,
                "price"      => $ticketModel->price,
                "image"      => asset('assets/img/easytix_login_bg.png')
            ];
        }



        session()->put('cart', $cart);
        return redirect()->route('user.checkout')->with('success', 'Tiket berhasil dipilih! Selesaikan & lengkapi detail pemesanan.');
    }



    public function viewCart()
    {
        return redirect()->route('user.buyTickets');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $oldQty = $cart[$request->id]["quantity"];
            $diff = $request->quantity - $oldQty;

            if ($diff > 0) {
                $maxTickets = 10;
                $userId = auth()->id();
                $ticket = Ticket::with('event_schedule')->find($request->id);
                $eventId = $ticket ? $ticket->event_schedule->event_id : null;

                if ($eventId) {
                    $purchasedCount = OrderDetail::whereHas('order', function($q) use ($userId) {
                        $q->where('users_id', $userId);
                    })->whereHas('ticket.event_schedule', function($q) use ($eventId) {
                        $q->where('event_id', $eventId);
                    })->whereIn('status', ['valid', 'used'])->count();

                    $cartCount = 0;
                    foreach($cart as $item) {
                        if (isset($item['events_id']) && $item['events_id'] == $eventId) {
                            $cartCount += $item['quantity'];
                        }
                    }

                    if (($purchasedCount + $cartCount + $diff) > $maxTickets) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Gagal memperbarui! Total tiket (beli + keranjang) untuk event ini melebihi batas 10 tiket.'
                        ], 400);
                    }
                }
            }

            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Keranjang berhasil diperbarui!');
            return response()->json(['status' => 'success']);
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
        session()->put('global_email', $request->global_email);

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

        // FINAL CHECK: Re-verify 10 ticket limit per event before processing
        $maxTickets = 10;
        $userId = auth()->id();
        
        // Group cart by event_id for validation
        $cartByEvent = [];
        foreach($cart as $item) {
            $eid = $item['events_id'] ?? null;
            if ($eid) {
                $cartByEvent[$eid] = ($cartByEvent[$eid] ?? 0) + $item['quantity'];
            }
        }

        foreach ($cartByEvent as $eid => $qtyInCart) {
            $purchasedCount = OrderDetail::whereHas('order', function($q) use ($userId) {
                $q->where('users_id', $userId);
            })->whereHas('ticket.event_schedule', function($q) use ($eid) {
                $q->where('event_id', $eid);
            })->whereIn('status', ['valid', 'used'])->count();

            if (($purchasedCount + $qtyInCart) > $maxTickets) {
                return redirect()->route('user.buyTickets')->with('error', "Pesanan gagal diproses karena jumlah tiket untuk salah satu event melebihi batas 10 tiket.");
            }
        }

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
            'email'          => session()->get('global_email'),
        ]);

        // Mark Waiting List as PURCHASED
        foreach ($cart as $ticketId => $details) {
            \App\Models\WaitingList::where('user_id', auth()->id())
                ->where('ticket_id', $ticketId)
                ->where('status', 'approved')
                ->update(['status' => 'purchased']);
        }

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
                $fileName = 'qr_' . time() . '_' . $i . '_' . $orderDetailId . '.svg';
                $qrPath = 'qrcodes/' . $fileName;

                // Simpan ke storage/app/qrcodes (bukan public)
                $qrDir = storage_path('app/qrcodes');
                if (!file_exists($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }

                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate($qrString, $qrDir . '/' . $fileName);


                $ticketData = $ticketDetails[$ticketId][$i] ?? [];

                $orderDetail = OrderDetail::create([
                    'id'           => $orderDetailId,
                    'owner_name'   => $ticketData['name'] ?? (auth()->user()->name ?? 'Guest'),
                    'status'       => 'valid',
                    'tickets_id'   => $ticketId,
                    'orders_id'    => $order->id,
                    'qr_code'      => $qrPath,
                    'ticket_code'  => $qrString,
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

        // Kirim email dengan QR code ke email yang diinput saat checkout (fallback ke email login)
        try {
            $targetEmail = $order->email ?: auth()->user()->email;
            Mail::to($targetEmail)->send(
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
        $allTickets = [];

        foreach ($orders as $order) {
            $orderDetails = OrderDetail::where('orders_id', $order->id)->get();
            if ($orderDetails->isEmpty()) continue;

            $groupedDetails = $orderDetails->groupBy('tickets_id');

            foreach ($groupedDetails as $ticketId => $detailsGroup) {
                $ticket = Ticket::with(['ticket_type', 'event_schedule.event'])->find($ticketId);
                if (!$ticket) continue;
                
                $totalQty = $detailsGroup->count();
                $index = 1;
                foreach ($detailsGroup as $detail) {
                    $allTickets[] = [
                        'order_id'       => $order->id,
                        'order_status'   => $order->status ?? 'paid',
                        'payment_method' => $order->payment_method ?? 'QRIS',
                        'created_at'     => $order->created_at, // Keep as object for sorting
                        'created_at_fmt' => $order->created_at->format('d M Y, H:i'),
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
                        'status'       => $detail->status ?? 'valid',
                        'ticket_index' => $index++,
                        'total_qty'    => $totalQty
                    ];
                }
            }
        }

        // Sort: Valid tickets first, then sort by newest date within those groups
        usort($allTickets, function($a, $b) {
            // Check status (valid = 1, others = 0)
            $aValid = ($a['status'] === 'valid') ? 1 : 0;
            $bValid = ($b['status'] === 'valid') ? 1 : 0;

            if ($aValid !== $bValid) {
                return $bValid - $aValid; // 1 comes before 0
            }

            // If same status, sort by date desc
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return view('user.my-tickets', compact('allTickets'));
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
        $mime = str_ends_with($filename, '.svg') ? 'image/svg+xml' : 'image/png';
        return response()->file($path, ['Content-Type' => $mime]);
    }
}

