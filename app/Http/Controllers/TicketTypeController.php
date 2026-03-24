<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function manageTicketTypes()
    {   
       $ticketTypes = TicketType::all();    
       return view('admin.manage-ticket-types', compact('ticketTypes'));
    }

    public function createTicketType()
    {
        return view('admin.create-ticket-type');
    }

    public function storeTicketType(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ticket_types,name',
        ]);

        try {
            TicketType::create($request->all());
            return redirect()->route('admin.manageTicketTypes')->with('success', 'Tipe tiket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan tipe tiket: ' . $e->getMessage());
        }
    }

    public function editTicketType(TicketType $ticketType)
    {
        return view('admin.edit-ticket-type', compact('ticketType'));
    }

    public function updateTicketType(Request $request, TicketType $ticketType)
    {
        $request->validate([
            'name' => 'required|unique:ticket_types,name,' . $ticketType->id,
        ]);

        try {
            $ticketType->update($request->all());
            return redirect()->route('admin.manageTicketTypes')->with('success', 'Tipe tiket berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui tipe tiket: ' . $e->getMessage());
        }
    }

    public function deleteTicketType(TicketType $ticketType)
    {
        try {
            $ticketType->delete();
            return redirect()->route('admin.manageTicketTypes')->with('success', 'Tipe tiket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.manageTicketTypes')->with('error', 'Gagal menghapus tipe tiket: ' . $e->getMessage());
        }
    }
}
