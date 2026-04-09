<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRequest;
use App\Models\EventSchedule;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function organizerEvents()
    {
        // Menampilkan semua event yang aktif untuk di-request access-nya
        $events = Event::where('status', 'active')->with(['category', 'performers.genres'])->get();
        
        // Ambil semua request milik user ini agar bisa ditampilkan statusnya di view
        $myRequests = EventRequest::where('users_id', auth()->id())
                        ->pluck('status', 'event_id'); // ['event_id' => 'status']
        
        return view('organizer.events', compact('events', 'myRequests'));
    }

    public function myEvents()
    {
        // Menampilkan event yang sudah disetujui (access granted)
        $approvedRequests = EventRequest::where('users_id', auth()->id())
                            ->where('status', 'approved')
                            ->with(['event.category', 'event.performers.genres'])
                            ->get();
        return view('organizer.my-events', compact('approvedRequests'));
    }

    public function selectEventVerification()
    {
        // View yang menampilkan daftar event untuk dipilih mana yang mau diverifikasi
        $approvedRequests = EventRequest::where('users_id', auth()->id())
                            ->where('status', 'approved')
                            ->with('event')
                            ->get();
        return view('organizer.verify-ticket', compact('approvedRequests'));
    }

    public function verifyTicketDetail(Event $event)
    {
        // View yang menampilkan daftar jadwal dari event tertentu
        $event->load('event_schedule');
        return view('organizer.verify-ticket-detail', compact('event'));
    }

    public function verifySchedule(EventSchedule $schedule)
    {
        // View yang berisi form input kode tiket untuk jadwal tertentu
        $schedule->load('event');
        
        // Fetch verifikasi hari ini untuk jadwal ini
        $todayVerifications = OrderDetail::where('status', 'used')
            ->whereDate('updated_at', now()->toDateString())
            ->whereHas('ticket', function($q) use ($schedule) {
                $q->where('event_schedules_id', $schedule->id);
            })
            ->with(['ticket.ticket_type'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('organizer.verify-ticket-input', compact('schedule', 'todayVerifications'));
    }

    public function processVerification(Request $request, EventSchedule $schedule)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $code = $request->ticket_code;
        
        $detail = OrderDetail::with('ticket')->where('ticket_code', $code)->first();

        if(!$detail || !$detail->ticket) {
            return back()->with('error', 'Tiket tidak ditemukan atau data tiket tidak valid!');
        }

        if($detail->ticket->event_schedules_id != $schedule->id) {
            return back()->with('error', 'Tiket ini bukan untuk jadwal ini!');
        }

        if($detail->status === 'used') {
            return back()->with('error', 'Tiket sudah digunakan sebelumnya!');
        }

        if($detail->status !== 'valid') {
            return back()->with('error', 'Status tiket tidak valid!');
        }

        $detail->update(['status' => 'used']);

        return back()->with('success', 'Verifikasi Berhasil! Tiket milik ' . $detail->owner_name . ' valid.');
    }

    public function myEventsDetail(Event $event)
    {
        // View yang menampilkan daftar jadwal dari event tertentu untuk daftar peserta
        $event->load('event_schedule');
        return view('organizer.my-events-detail', compact('event'));
    }

    public function attendees(EventSchedule $schedule)
    {
        // Tampilkan daftar peserta untuk jadwal tertentu (diambil dari order_details)
        $schedule->load('event');
        
        $attendees = OrderDetail::whereIn('status', ['valid', 'used'])
            ->whereHas('ticket', function($q) use ($schedule) {
                $q->where('event_schedules_id', $schedule->id);
            })
            ->with(['ticket.ticket_type'])
            ->get();

        $totalCount = $attendees->count();
        $hadirCount = $attendees->where('status', 'used')->count();
        $tidakHadirCount = $attendees->where('status', 'valid')->count();
        $attendanceRate = $totalCount > 0 ? round(($hadirCount / $totalCount) * 100, 1) : 0;

        return view('organizer.attendees', compact(
            'schedule', 
            'attendees', 
            'totalCount', 
            'hadirCount', 
            'tidakHadirCount', 
            'attendanceRate'
        ));
    }

    public function salesReport()
    {
        $organizerId = auth()->id();
        
        $eventIds = EventRequest::where('users_id', $organizerId)
                    ->where('status', 'approved')
                    ->pluck('event_id');
        
        $orders = Order::whereIn('events_id', $eventIds)
                    ->with(['event', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $totalRevenue = $orders->sum('total_amount');
        $totalTickets = OrderDetail::whereIn('orders_id', $orders->pluck('id'))->count();
        
        $eventStats = [];
        foreach($eventIds as $id) {
            $event = Event::find($id);
            if(!$event) continue;
            
            $eventOrders = $orders->where('events_id', $id);
            $revenue = $eventOrders->sum('total_amount');
            $tickets = OrderDetail::whereIn('orders_id', $eventOrders->pluck('id'))->count();
            
            $eventStats[] = [
                'name'         => $event->name,
                'revenue'      => $revenue,
                'tickets'      => $tickets,
                'orders_count' => $eventOrders->count()
            ];
        }

        // Data untuk Chart (Penjualan 7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d M');
            $revenue = $orders->filter(function($order) use ($date) {
                return $order->created_at->format('Y-m-d') == $date;
            })->sum('total_amount');
            
            $chartLabels[] = $label;
            $chartData[] = (float)$revenue;
        }

        return view('organizer.sales-report', compact('orders', 'totalRevenue', 'totalTickets', 'eventStats', 'chartLabels', 'chartData'));
    }
}
