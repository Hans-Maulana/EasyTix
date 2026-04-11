<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    // User Method
    public function userIndex()
    {
        $waitingLists = \App\Models\WaitingList::where('user_id', auth()->id())
            ->with(['ticket.ticket_type', 'ticket.event_schedule.event'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.waiting-list', compact('waitingLists'));
    }
    public function joinWaitingList(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $ticket = \App\Models\Ticket::with('event_schedule.event.requests')->findOrFail($request->ticket_id);

        $existing = \App\Models\WaitingList::where('user_id', auth()->id())
            ->where('ticket_id', $request->ticket_id)
            ->whereIn('status', ['pending', 'requested'])
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'Anda sudah terdaftar di Waiting List untuk tiket ini. Silakan tunggu informasi selanjutnya.');
        }

        $maxPriority = \App\Models\WaitingList::where('ticket_id', $request->ticket_id)->max('priority') ?? 0;
        
        \App\Models\WaitingList::create([
            'user_id' => auth()->id(),
            'ticket_id' => $request->ticket_id,
            'quantity' => $request->quantity,
            'priority' => $maxPriority + 1,
            'status' => 'pending'
        ]);

        $organizers = \App\Models\EventRequest::where('event_id', $ticket->event_schedule->event->id)
            ->where('status', 'approved')
            ->get();
            
        foreach($organizers as $org) {
            \App\Models\Notification::create([
                'user_id' => $org->users_id,
                'type' => 'info',
                'title' => 'Request Waiting List Baru',
                'message' => auth()->user()->name . ' meminta masuk ke waiting list untuk tiket di event ' . $ticket->event_schedule->event->name,
                'link' => route('organizer.waitingList', $ticket->event_schedule->id)
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil bergabung ke Waiting List. Organizer kami sedang di-notifikasi.');
    }

    // Organizer Methods
    public function organizerIndex(\App\Models\EventSchedule $schedule)
    {
        $schedule->load('event');
        $waitingLists = \App\Models\WaitingList::whereHas('ticket', function($q) use ($schedule) {
            $q->where('event_schedules_id', $schedule->id);
        })->with(['user', 'ticket.ticket_type'])->orderBy('priority', 'asc')->get();

        return view('organizer.waiting-list', compact('schedule', 'waitingLists'));
    }

    public function requestToAdmin(Request $request, \App\Models\WaitingList $waitingList)
    {
        if ($waitingList->status === 'pending') {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);
            $waitingList->update([
                'status' => 'requested',
                'requested_quantity' => $request->quantity
            ]);
            // Notify Admin
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'info',
                    'title' => 'Request Waiting List dari Organizer',
                    'message' => 'Ada request untuk membuka ' . $waitingList->requested_quantity . ' slot tiket waiting list untuk ' . $waitingList->user->name . ' (User minta ' . $waitingList->quantity . ').',
                    'link' => route('admin.requestsOrganizer')
                ]);
            }
            return redirect()->back()->with('success', 'Request penambahan kuota Waiting List sebanyak ' . $waitingList->requested_quantity . ' telah dikirim ke Admin.');
        }
        return redirect()->back()->with('error', 'Status wating list tidak valid.');
    }

    // Admin Methods
    public function approve(\App\Models\WaitingList $waitingList)
    {
        if ($waitingList->status === 'requested') {
            $waitingList->update(['status' => 'approved']);
            
            $amountToOpen = $waitingList->requested_quantity;
            
            // Tambah stok karena disetujui
            $waitingList->ticket->increment('capacity', $amountToOpen);
            
            // Beritahu user yang menunggu
            \App\Models\Notification::create([
                'user_id' => $waitingList->user_id,
                'type' => 'success',
                'title' => 'Tiket Waiting List Tersedia!',
                'message' => 'Kabar baik! Request waiting list untuk tiket ' . $waitingList->ticket->ticket_type->name . ' telah disetujui sebanyak ' . $amountToOpen . ' tiket. Silakan checkout sekarang juga.',
                'link' => route('user.buyTickets')
            ]);

            return redirect()->back()->with('success', 'Waiting list disetujui! Kapasitas tiket telah ditambahkan dan User sudah dinotifikasi.');
        }
        return redirect()->back()->with('error', 'Status waiting list tidak valid.');
    }

    public function reject(\App\Models\WaitingList $waitingList)
    {
        if ($waitingList->status === 'requested') {
            $waitingList->update(['status' => 'rejected']);
            
            \App\Models\Notification::create([
                'user_id' => $waitingList->user_id,
                'type' => 'danger',
                'title' => 'Waiting List Ditolak',
                'message' => 'Maaf, request waiting list Anda untuk tiket ' . $waitingList->ticket->ticket_type->name . ' tidak dapat dikabulkan saat ini.',
                'link' => '#'
            ]);

            return redirect()->back()->with('success', 'Waiting list ditolak.');
        }
        return redirect()->back()->with('error', 'Status waiting list tidak valid.');
    }

    public function cancel(\App\Models\WaitingList $waitingList)
    {
        if ($waitingList->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $ticketId = $waitingList->ticket_id;
        $qtyRelease = $waitingList->quantity;
        $oldStatus = $waitingList->status;

        $waitingList->update(['status' => 'cancelled']);

        // Redistribution Logic: If an approved slot is cancelled, give it to the next in line
        if ($oldStatus === 'approved') {
            $remainingPool = $qtyRelease;

            while ($remainingPool > 0) {
                $nextInLine = \App\Models\WaitingList::where('ticket_id', $ticketId)
                    ->where('status', 'pending')
                    ->orderBy('priority', 'asc')
                    ->first();

                if ($nextInLine && $remainingPool >= $nextInLine->quantity) {
                    $nextInLine->update(['status' => 'approved']);
                    $remainingPool -= $nextInLine->quantity;

                    \App\Models\Notification::create([
                        'user_id' => $nextInLine->user_id,
                        'type'    => 'success',
                        'title'   => 'Slot Waiting List Tersedia!',
                        'message' => "Ada user yang membatalkan antrian, kini giliran Anda! Tiket '{$nextInLine->ticket->ticket_type->name}' sudah bisa Anda beli sebanyak {$nextInLine->quantity} tiket.",
                        'link'    => route('user.buyTickets'),
                    ]);
                } else {
                    break;
                }
            }
            
            // Sisa kuota (jika ada) dibiarkan tetap di capacity agar bisa dibeli oleh umum
        }

        return redirect()->back()->with('success', 'Antrian waiting list Anda telah dibatalkan.');
    }
}
