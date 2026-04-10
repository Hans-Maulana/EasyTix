<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventRequestController extends Controller
{
    // User / Organizer Methods
    public function requestAccess(\App\Models\Event $event)
    {
        // Cek apakah sudah ada request dari user ini untuk event ini
        $existingRequest = \App\Models\EventRequest::where('event_id', $event->id)
                            ->where('users_id', auth()->id())
                            ->first();
        
        if ($existingRequest) {
            if ($existingRequest->status === 'approved') {
                return back()->with('error', 'Anda sudah memiliki akses ke event ' . $event->name . '!');
            }
            if ($existingRequest->status === 'pending') {
                return back()->with('error', 'Permintaan akses Anda untuk event ' . $event->name . ' sedang menunggu persetujuan!');
            }
            // Jika status 'rejected', izinkan request ulang
            $existingRequest->update(['status' => 'pending']);
            return back()->with('success', 'Permintaan akses ke event ' . $event->name . ' telah dikirim ulang!');
        }

        \App\Models\EventRequest::create([
            'event_id' => $event->id,
            'users_id' => auth()->id(),
            'status' => 'pending',
        ]);
        
        // Notifikasi ke Admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type' => 'info',
                'title' => 'Request Event Baru',
                'message' => auth()->user()->name . ' meminta akses untuk event: ' . $event->name,
                'link' => route('admin.requestsOrganizer'),
            ]);
        }

        return back()->with('success', 'Permintaan akses ke event ' . $event->name . ' telah dikirim!');
    }

    // Admin Methods
    public function manageRequestsOrganizer()
    {
        // Event Access Requests
        $eventRequests = \App\Models\EventRequest::with(['user', 'event'])->latest()->get();
        // Waiting List Requests
        $waitlistRequests = \App\Models\WaitingList::with(['user', 'ticket.event_schedule.event'])
            ->whereIn('status', ['requested', 'approved', 'rejected'])
            ->latest()->get();

        return view('admin.requests-organizer', compact('eventRequests', 'waitlistRequests'));
    }

    public function approveRequest(\App\Models\EventRequest $request)
    {
        $request->update(['status' => 'approved']);
        
        // Notifikasi ke Organizer
        \App\Models\Notification::create([
            'user_id' => $request->users_id,
            'type' => 'success',
            'title' => 'Request Akses Disetujui',
            'message' => 'Admin telah menyetujui akses Anda untuk event: ' . $request->event->name,
            'link' => route('organizer.myEvents'),
        ]);

        return back()->with('success', 'Permintaan akses disetujui!');
    }

    public function rejectRequest(\App\Models\EventRequest $request)
    {
        $request->update(['status' => 'rejected']);
        
        // Notifikasi ke Organizer
        \App\Models\Notification::create([
            'user_id' => $request->users_id,
            'type' => 'offer',
            'title' => 'Request Akses Ditolak',
            'message' => 'Maaf, permintaan akses Anda untuk event ' . $request->event->name . ' ditolak.',
            'link' => route('organizer.events'),
        ]);

        return back()->with('success', 'Permintaan akses ditolak!');
    }
}
