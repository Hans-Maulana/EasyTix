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
            DB::beginTransaction();

            // 1. Check if there are any orders for this event
            $hasOrders = OrderDetail::whereHas('ticket.event_schedule', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })->exists();

            // 2. Check if there are any organizers "holding" this event (approved requests)
            $isHeldByOrganizer = EventRequest::where('event_id', $event->id)
                ->where('status', 'approved')
                ->exists();

            if ($hasOrders || $isHeldByOrganizer) {
                // Jika sudah ada order atau dipegang organizer, ubah status menjadi nonactive
                $event->update(['status' => 'nonactive']);

                // Update status EventRequest menjadi 'cancelled' dan beri notifikasi ke organizer
                $approvedRequests = EventRequest::where('event_id', $event->id)
                    ->where('status', 'approved')
                    ->get();
                
                foreach ($approvedRequests as $request) {
                    $request->update(['status' => 'cancelled']);
                    
                    Notification::create([
                        'user_id' => $request->users_id,
                        'type'    => 'event_cancelled',
                        'title'   => 'Event Dibatalkan',
                        'message' => "Event '{$event->name}' telah dibatalkan oleh Admin. Status kerjasama Anda kini dibatalkan.",
                        'link'    => route('organizer.myEvents'),
                    ]);
                }

                // Proses Refund dan Notifikasi ke User yang sudah membeli tiket
                $orders = Order::with('user')->where('events_id', $event->id)->get();
                foreach ($orders as $order) {
                    // Update Status Order menjadi 'refunded'
                    $order->update(['status' => 'refunded']);

                    // Update SEMUA detail tiket menjadi 'cancelled' agar tidak muncul sebagai tiket valid
                    OrderDetail::where('orders_id', $order->id)->update(['status' => 'cancelled']);

                    if ($order->user) {
                        // Buat Notifikasi di sistem
                        Notification::create([
                            'user_id' => $order->users_id,
                            'type'    => 'refund',
                            'title'   => 'Refund Tiket Event',
                            'message' => "Event '{$event->name}' telah dibatalkan. Dana sebesar Rp " . number_format($order->total_amount, 0, ',', '.') . " akan direfund ke metode pembayaran Anda ({$order->payment_method}).",
                            'link'    => route('user.myTickets'),
                        ]);

                        // Kirim Email Refund
                        try {
                            Mail::to($order->user->email)->send(new EventCancelledRefund($event, $order));
                        } catch (\Exception $e) {
                            Log::error("Gagal mengirim email refund ke {$order->user->email}: " . $e->getMessage());
                        }
                    }
                }
                
                DB::commit();
                return redirect()->route('admin.manageEvents')->with('success', 'Event tidak dapat dihapus karena sudah memiliki transaksi atau dipegang organizer. Status diubah menjadi Nonactive, refund dan notifikasi telah dikirim.');
            } else {
                // Jika belum ada order dan belum dipegang organizer, hapus event dan relasinya
                
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
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('admin.manageEvents')->with('error', 'Gagal memproses penghapusan event: ' . $e->getMessage());
        }
    }
}
