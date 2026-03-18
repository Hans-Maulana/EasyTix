<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Genre;
use App\Models\EventSchedule;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

            $event->update($request->only('name', 'location', 'status'));

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
        $event->delete();
        return redirect()->route('admin.manageEvents');
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
}
