<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function manageTickets()
    {
        $tickets = Ticket::all();
        return view('admin.manage-tickets', compact('tickets'));
    }

    public function createTicket()
    {
        return view('admin.create-ticket');
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'genre_ids' => 'required|array',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.event_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $ticket = Ticket::create([
                'name' => $request->name,
                'location' => $request->location,
                'status' => $request->status,
                'genre_ids' => $request->genre_ids,
            ]);

            DB::commit();
            return redirect()->route('admin.manageTickets')->with('success', 'Ticket berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan ticket: ' . $e->getMessage());
        }
    }

    public function editTicket(Ticket $ticket)
    {
        return view('admin.edit-ticket', compact('ticket'));
    }

    public function updateTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'genre_ids' => 'required|array',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.event_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $ticket->update([
                'name' => $request->name,
                'location' => $request->location,
                'status' => $request->status,
                'genre_ids' => $request->genre_ids,
            ]);

            DB::commit();
            return redirect()->route('admin.manageTickets')->with('success', 'Ticket berhasil diupdate!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengupdate ticket: ' . $e->getMessage());
        }
    }

    public function deleteTicket(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.manageTickets')->with('success', 'Ticket berhasil dihapus!');
    }
}
