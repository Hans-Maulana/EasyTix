<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Genre;
use App\Models\EventSchedule;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\EventRequest;
use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function manageEvents()
    {
        $events = Event::all();
        return view('admin.manage-events', compact('events'));
    }
    public function createEvent()
    {
        $genres = Genre::all();
        $ticketTypes = TicketType::all();
        return view('admin.create-event', compact('genres', 'ticketTypes'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'genre_ids' => 'required|array',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.event_date' => 'required|date',
            'schedules.*.tickets.*.ticket_types_id' => 'required|exists:ticket_types,id',
            'schedules.*.tickets.*.capacity' => 'required|integer|min:1',
            'schedules.*.tickets.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $event = Event::create([
                'name' => $request->name,
                'location' => $request->location,
                'status' => $request->status,
                'users_id' => auth()->id(),
            ]);

            // Lampirkan genre
            $event->genres()->attach($request->genre_ids);

            // Simpan semua schedule terkait event
            if ($request->has('schedules')) {
                foreach ($request->schedules as $scheduleData) {
                    $schedule = EventSchedule::create([
                        'event_id'    => $event->id,
                        'start_time'  => $scheduleData['start_time'],
                        'end_time'    => $scheduleData['end_time'],
                        'event_date'  => $scheduleData['event_date'],
                        'description' => $scheduleData['description'] ?? null,
                        'status'      => $scheduleData['status'] ?? 'scheduled',
                    ]);

                    // Simpan tiket untuk setiap schedule
                    if (isset($scheduleData['tickets'])) {
                        foreach ($scheduleData['tickets'] as $ticketData) {
                            Ticket::create([
                                'event_schedules_id' => $schedule->id,
                                'ticket_types_id'    => $ticketData['ticket_types_id'],
                                'capacity'           => $ticketData['capacity'],
                                'price'              => $ticketData['price'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.manageEvents')->with('success', 'Event berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error($e);
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan event: ' . $e->getMessage());
        }
    }

    public function editEvent(Event $event)
    {
        $genres = Genre::all();
        $ticketTypes = TicketType::all();
        $event->load('event_schedule.tickets');
        return view('admin.edit-event', compact('event', 'genres', 'ticketTypes'));
    } 

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'genre_ids' => 'required|array',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.event_date' => 'required|date',
            'schedules.*.tickets.*.ticket_types_id' => 'required|exists:ticket_types,id',
            'schedules.*.tickets.*.capacity' => 'required|integer|min:1',
            'schedules.*.tickets.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $event->update([
                'name' => $request->name,
                'location' => $request->location,
                'status' => $request->status,
            ]);

            // Sinkronisasi genre
            $event->genres()->sync($request->genre_ids);

            // Handle Schedules
            $submittedScheduleIds = [];
            if ($request->has('schedules')) {
                foreach ($request->schedules as $scheduleData) {
                    $schedule = EventSchedule::updateOrCreate(
                        ['id' => $scheduleData['id'] ?? null],
                        [
                            'event_id'    => $event->id,
                            'start_time'  => $scheduleData['start_time'],
                            'end_time'    => $scheduleData['end_time'],
                            'event_date'  => $scheduleData['event_date'],
                            'description' => $scheduleData['description'] ?? null,
                            'status'      => $scheduleData['status'] ?? 'scheduled',
                        ]
                    );
                    $submittedScheduleIds[] = $schedule->id;

                    // Handle Tickets for this schedule
                    $submittedTicketIds = [];
                    if (isset($scheduleData['tickets'])) {
                        foreach ($scheduleData['tickets'] as $ticketData) {
                            $ticket = Ticket::updateOrCreate(
                                ['id' => $ticketData['id'] ?? null],
                                [
                                    'event_schedules_id' => $schedule->id,
                                    'ticket_types_id'    => $ticketData['ticket_types_id'],
                                    'capacity'           => $ticketData['capacity'],
                                    'price'              => $ticketData['price'],
                                ]
                            );
                            $submittedTicketIds[] = $ticket->id;
                        }
                    }
                    // Delete tickets not in the submitted list for this schedule
                    Ticket::where('event_schedules_id', $schedule->id)
                          ->whereNotIn('id', $submittedTicketIds)
                          ->delete();
                }
            }

            // Delete schedules not in the submitted list for this event
            EventSchedule::where('event_id', $event->id)
                         ->whereNotIn('id', $submittedScheduleIds)
                         ->delete();

            DB::commit();
            return redirect()->route('admin.manageEvents')->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui event: ' . $e->getMessage());
        }
    }

    public function deleteEvent(Event $event)
    {
        try {
            $event->delete();
            return redirect()->route('admin.manageEvents')->with('success', 'Event berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.manageEvents')->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
    }

    public function scheduleEvent(Event $event)
    {
        $event->load('event_schedule.tickets.ticket_type');
        return view('admin.schedule-event', compact('event'));
    }

    public function createSchedule(Event $event)
    {
        $ticketTypes = TicketType::all();
        return view('admin.create-schedule', compact('event', 'ticketTypes'));
    }

    public function storeSchedule(Request $request, Event $event)
    {
        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'tickets'      => 'required|array',
            'tickets.*.ticket_types_id' => 'required|exists:ticket_types,id',
            'tickets.*.capacity'       => 'required|integer|min:1',
            'tickets.*.price'          => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $schedule = EventSchedule::create([
                'event_id'    => $event->id,
                'event_date'  => $request->event_date,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
                'description' => $request->description,
                'status'      => 'scheduled',
            ]);

            foreach ($request->tickets as $ticketData) {
                Ticket::create([
                    'event_schedules_id' => $schedule->id,
                    'ticket_types_id'    => $ticketData['ticket_types_id'],
                    'capacity'           => $ticketData['capacity'],
                    'price'              => $ticketData['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.scheduleEvent', $event->id)->with('success', 'Jadwal dan tiket berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage());
        }
    }

    public function editSchedule(Event $event, EventSchedule $schedule)
    {
        $ticketTypes = TicketType::all();
        $schedule->load('tickets');
        return view('admin.edit-schedule', compact('event', 'schedule', 'ticketTypes'));
    }

    public function updateSchedule(Request $request, Event $event, EventSchedule $schedule)
    {
        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'tickets'      => 'required|array',
            'tickets.*.ticket_types_id' => 'required|exists:ticket_types,id',
            'tickets.*.capacity'       => 'required|integer|min:1',
            'tickets.*.price'          => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $schedule->update([
                'event_date'  => $request->event_date,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
                'description' => $request->description,
            ]);

            $schedule->tickets()->delete();

            foreach ($request->tickets as $ticketData) {
                Ticket::create([
                    'event_schedules_id' => $schedule->id,
                    'ticket_types_id'    => $ticketData['ticket_types_id'],
                    'capacity'           => $ticketData['capacity'],
                    'price'              => $ticketData['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.scheduleEvent', $event->id)->with('success', 'Jadwal dan tiket berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function deleteSchedule(Event $event, EventSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.scheduleEvent', $event->id)->with('success', 'Jadwal berhasil dihapus!');
    }

    // --- ORGANIZER METHODS ---

    public function organizerEvents()
    {
        // Menampilkan semua event yang aktif untuk di-request access-nya
        $events = Event::where('status', 'active')->get();
        return view('organizer.events', compact('events'));
    }

    public function myEvents()
    {
        // Menampilkan event yang sudah disetujui (access granted)
        $approvedRequests = EventRequest::where('users_id', auth()->id())
                            ->where('status', 'approved')
                            ->with('event')
                            ->get();
        return view('organizer.my-events', compact('approvedRequests'));
    }

    public function requestAccess(Event $event)
    {
        EventRequest::updateOrCreate(
            [
                'event_id' => $event->id,
                'users_id' => auth()->id(),
            ],
            [
                'status' => 'pending',
            ]
        );
        return back()->with('success', 'Permintaan akses ke event ' . $event->name . ' telah dikirim!');
    }

    public function approveRequest(EventRequest $request)
    {
        $request->update(['status' => 'approved']);
        return back()->with('success', 'Permintaan akses disetujui!');
    }

    public function rejectRequest(EventRequest $request)
    {
        $request->update(['status' => 'rejected']);
        return back()->with('success', 'Permintaan akses ditolak!');
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

    public function selectEventVerification()
    {
        // View yang menampilkan daftar event untuk dipilih mana yang mau diverifikasi
        $approvedRequests = EventRequest::where('users_id', auth()->id())
                            ->where('status', 'approved')
                            ->with('event')
                            ->get();
        return view('organizer.verify-ticket', compact('approvedRequests'));
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
