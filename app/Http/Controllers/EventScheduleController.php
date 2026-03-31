<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventScheduleController extends Controller
{
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
