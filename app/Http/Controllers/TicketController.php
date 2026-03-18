<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\EventSchedule;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function manageTickets()
    {
        $tickets = Ticket::with(['ticket_type', 'event_schedule.event'])->get();
        return view('admin.manage-tickets', compact('tickets'));
    }

    public function createTicket()
    {
        $ticketTypes = TicketType::all();
        $schedules = EventSchedule::with('event')->get();
        return view('admin.create-ticket', compact('ticketTypes', 'schedules'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'event_schedules_id' => 'required|exists:event_schedules,id',
            'ticket_types_id'    => 'required|exists:ticket_types,id',
            'capacity'           => 'required|integer|min:1',
            'price'              => 'required|numeric|min:0',
        ]);

        try {
            Ticket::create($request->all());
            return redirect()->route('admin.manageTickets')->with('success', 'Ticket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan ticket: ' . $e->getMessage());
        }
    }

    public function editTicket(Ticket $ticket)
    {
        $ticketTypes = TicketType::all();
        $schedules = EventSchedule::with('event')->get();
        return view('admin.edit-ticket', compact('ticket', 'ticketTypes', 'schedules'));
    }

    public function updateTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'event_schedules_id' => 'required|exists:event_schedules,id',
            'ticket_types_id'    => 'required|exists:ticket_types,id',
            'capacity'           => 'required|integer|min:1',
            'price'              => 'required|numeric|min:0',
        ]);

        try {
            $ticket->update($request->all());
            return redirect()->route('admin.manageTickets')->with('success', 'Ticket berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupdate ticket: ' . $e->getMessage());
        }
    }

    public function deleteTicket(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.manageTickets')->with('success', 'Ticket berhasil dihapus!');
    }
}
