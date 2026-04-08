<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRequest;
use App\Models\EventSchedule;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Menampilkan event yang sudah disetujui (access granted) dan status event masih active
        $approvedRequests = EventRequest::where('users_id', auth()->id())
                            ->where('status', 'approved')
                            ->whereHas('event', function($query) {
                                $query->where('status', 'active');
                            })
                            ->with(['event.category', 'event.performers.genres'])
                            ->get();
        return view('organizer.my-events', compact('approvedRequests'));
    }

    public function selectEventVerification()
    {
        // View yang menampilkan daftar event untuk dipilih mana yang mau diverifikasi
        // Hanya tampilkan event yang statusnya aktif
        $approvedRequests = EventRequest::where('users_id', auth()->id())
                            ->where('status', 'approved')
                            ->whereHas('event', function($query) {
                                $query->where('status', 'active');
                            })
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

        return view('organizer.attendees', compact('schedule', 'attendees'));
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
        $totalTicketsSold = OrderDetail::whereIn('orders_id', $orders->pluck('id'))->count();
        
        // Attendance Stats for Organizer
        $attendanceStats = OrderDetail::whereIn('orders_id', $orders->pluck('id'))
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        $totalAttendance = $attendanceStats->sum('count');
        $usedAttendance = $attendanceStats->where('status', 'used')->first()->count ?? 0;
        $attendanceRate = $totalAttendance > 0 ? round(($usedAttendance / $totalAttendance) * 100) : 0;
        
        // Detail per Event
        $eventStats = [];
        $events = Event::whereIn('id', $eventIds)->get();
        foreach($events as $event) {
            $eventOrders = $orders->where('events_id', $event->id);
            $revenue = $eventOrders->sum('total_amount');
            
            // Ambil ID detail pesanan untuk event ini
            $orderDetailIds = OrderDetail::whereIn('orders_id', $eventOrders->pluck('id'))->pluck('id');
            $ticketsCount = $orderDetailIds->count();
            
            $eventUsed = OrderDetail::whereIn('id', $orderDetailIds)
                ->where('status', 'used')
                ->count();
            
            $eventAttendanceRate = $ticketsCount > 0 ? round(($eventUsed / $ticketsCount) * 100) : 0;
            
            $eventStats[] = (object) [
                'id' => $event->id,
                'name' => $event->name,
                'revenue' => $revenue,
                'tickets_sold' => $ticketsCount,
                'attendance_rate' => $eventAttendanceRate,
                'orders_count' => $eventOrders->count()
            ];
        }

        // Monthly Trend (6 months)
        $monthlyLabels = [];
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $label = $monthDate->format('M Y');
            $rev = $orders->filter(function($order) use ($monthDate) {
                return $order->created_at->format('Y-m') == $monthDate->format('Y-m');
            })->sum('total_amount');
            
            $monthlyLabels[] = $label;
            $monthlyRevenue[] = (float)$rev;
        }

        return view('organizer.sales-report', compact(
            'orders', 
            'totalRevenue', 
            'totalTicketsSold', 
            'attendanceRate', 
            'eventStats', 
            'monthlyLabels', 
            'monthlyRevenue',
            'attendanceStats'
        ));
    }
}
