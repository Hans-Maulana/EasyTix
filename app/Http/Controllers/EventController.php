<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Performer;
use App\Models\EventSchedule;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\OrderDetail;
use App\Models\EventRequest;
use App\Models\Order;
use App\Models\Notification;
use App\Mail\EventCancelledRefund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function manageEvents()
    {
        $events = Event::with(['category', 'performers'])->get();
        
        // Cek apakah tiap event sudah memiliki tiket yang dibeli
        foreach ($events as $event) {
            $event->has_orders = OrderDetail::whereHas('ticket.event_schedule', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })->exists();
        }

        return view('admin.manage-events', compact('events'));
    }
    public function createEvent()
    {
        $categories = Category::all();
        $performers = Performer::with('genres')->get();
        $ticketTypes = TicketType::all();
        return view('admin.create-event', compact('categories', 'performers', 'ticketTypes'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'category_id' => 'required|exists:categories,id',
            'performer_ids' => 'required|array',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.event_date' => 'required|date',
            'schedules.*.tickets.*.ticket_types_id' => 'required|exists:ticket_types,id',
            'schedules.*.tickets.*.capacity' => 'required|integer|min:1',
            'schedules.*.tickets.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $bannerPath = null;
            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->store('events/banners', 'public');
            }

            $event = Event::create([
                'name'        => $request->name,
                'location'    => $request->location,
                'status'      => $request->status,
                'users_id'    => auth()->id(),
                'category_id' => $request->category_id,
                'banner'      => $bannerPath,
                'description' => $request->description,
            ]);

            // Lampirkan performer
            $event->performers()->attach($request->performer_ids);

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
            Log::error($e);
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan event: ' . $e->getMessage());
        }
    }

    public function editEvent(Event $event)
    {
        $categories = Category::all();
        $performers = Performer::with('genres')->get();
        $ticketTypes = TicketType::all();
        $event->load(['event_schedule.tickets', 'performers.genres', 'category']);
        return view('admin.edit-event', compact('event', 'categories', 'performers', 'ticketTypes'));
    } 

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'category_id' => 'required|exists:categories,id',
            'performer_ids' => 'required|array',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
            'schedules.*.event_date' => 'required|date',
            'schedules.*.tickets.*.ticket_types_id' => 'required|exists:ticket_types,id',
            'schedules.*.tickets.*.capacity' => 'required|integer|min:1',
            'schedules.*.tickets.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name'        => $request->name,
                'location'    => $request->location,
                'status'      => $request->status,
                'category_id' => $request->category_id,
                'description' => $request->description,
            ];
            if ($request->hasFile('banner')) {
                // Hapus banner lama jika ada
                if ($event->banner) {
                    Storage::disk('public')->delete($event->banner);
                }
                $updateData['banner'] = $request->file('banner')->store('events/banners', 'public');
            }
            $event->update($updateData);

            // Sinkronisasi performer
            $event->performers()->sync($request->performer_ids);

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
                            // Capture old capacity for waiting list logic
                            $oldTicket = Ticket::find($ticketData['id'] ?? null);
                            $oldCapacity = $oldTicket ? $oldTicket->capacity : 0;

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

                            // Waiting List Auto-Fulfillment Logic (FIFO)
                            $newCapacity = $ticket->capacity;
                            if ($newCapacity > $oldCapacity) {
                                $addedPool = $newCapacity - $oldCapacity;

                                $waitingEntries = \App\Models\WaitingList::where('ticket_id', $ticket->id)
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'asc')
                                    ->get();

                                foreach ($waitingEntries as $wl) {
                                    if ($addedPool >= $wl->quantity) {
                                        $wl->update(['status' => 'approved']);
                                        $addedPool -= $wl->quantity;

                                        \App\Models\Notification::create([
                                            'user_id' => $wl->user_id,
                                            'type'    => 'success',
                                            'title'   => 'Antrian Waiting List Anda Tersedia!',
                                            'message' => "Tiket '{$ticket->ticket_type->name}' untuk event '{$event->name}' sudah tersedia sebanyak {$wl->quantity} tiket sesuai request Anda. Silakan checkout segera!",
                                            'link'    => route('user.buyTickets'),
                                        ]);
                                    }
                                }
                            }
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
            DB::beginTransaction();

            // 1. Check if there are any orders for this event
            $hasOrders = OrderDetail::whereHas('ticket.event_schedule', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })->exists();

            // 2. Check if there are any organizers "holding" this event (approved requests)
            $isHeldByOrganizer = EventRequest::where('event_id', $event->id)
                ->where('status', 'approved')
                ->exists();

            if ($hasOrders) {
                return redirect()->route('admin.manageEvents')->with('error', 'Event tidak boleh dihapus atau dibatalkan karena sudah ada tiket yang terjual. Pastikan semua transaksi selesai terlebih dahulu.');
            }

            if ($isHeldByOrganizer) {
                // Notifikasi ke Organizer bahwa Event dinonaktifkan
                $approvedRequests = EventRequest::where('event_id', $event->id)
                    ->where('status', 'approved')
                    ->get();

                foreach ($approvedRequests as $req) {
                    \App\Models\Notification::create([
                        'user_id' => $req->users_id,
                        'type' => 'danger',
                        'title' => 'Event Dinonaktifkan Admin',
                        'message' => 'Mohon maaf, event ' . $event->name . ' telah dinonaktifkan oleh Admin dan akses Anda ditarik.',
                        'link' => route('organizer.events')
                    ]);
                }

                // Jika hanya dipegang organizer tapi belum ada order, ubah status menjadi nonactive
                $event->update(['status' => 'nonactive']);

                // Update status EventRequest menjadi 'cancelled'
                EventRequest::where('event_id', $event->id)
                    ->where('status', 'approved')
                    ->update(['status' => 'cancelled']);

                DB::commit();
                return redirect()->route('admin.manageEvents')->with('warning', 'Event diubah menjadi Nonactive karena sedang dipegang oleh Organizer.');
            }

            // Jika belum ada order dan belum dipegang organizer, hapus event dan relasinya
            // Hapus duplikat DB::beginTransaction() yang menyebabkan bug transaksi mengambang

            // Detach performers (pivot table)
            $event->performers()->detach();
                
                // Hapus semua tiket yang terkait dengan schedule event ini
                $scheduleIds = $event->event_schedule()->pluck('id');
                Ticket::whereIn('event_schedules_id', $scheduleIds)->delete();
                
                // Hapus semua schedule event
                $event->event_schedule()->delete();
                
                // Hapus semua request (jika ada yang masih pending/rejected/cancelled)
                $event->requests()->delete();

                // Hapus event itu sendiri
                $event->delete();

                DB::commit();
                return redirect()->route('admin.manageEvents')->with('success', 'Event berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('admin.manageEvents')->with('error', 'Gagal memproses penghapusan event: ' . $e->getMessage());
        }
    }
}
