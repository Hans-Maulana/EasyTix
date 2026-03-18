<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Genre;
use App\Models\EventSchedule;
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
        return view('admin.create-event', compact('genres'));
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
                foreach ($request->schedules as $schedule) {
                    EventSchedule::create([
                        'event_id'    => $event->id,
                        'start_time'  => $schedule['start_time'],
                        'end_time'    => $schedule['end_time'],
                        'event_date'  => $schedule['event_date'],
                        'description' => $schedule['description'] ?? null,
                        'status'      => $schedule['status'] ?? 'scheduled',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.manageEvents')->with('success', 'Event berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan event: ' . $e->getMessage());
        }
    }

    public function editEvent(Event $event)
    {
        $genres = Genre::all();
        return view('admin.edit-event', compact('event', 'genres'));
    } 

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'status' => 'required',
            'genre_ids' => 'required|array',
        ]);
        
        try {
            DB::beginTransaction();

            $event->update($request->only('name', 'location', 'status'));
            
            // Sinkronisasi genre
            $event->genres()->sync($request->genre_ids);

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
        return view('admin.schedule-event', compact('event'));
    }
}
