<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\EventSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

use App\Models\EventRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $totalUsers = User::count();
            $totalEvents = Event::count();
            $totalPendingRequests = EventRequest::where('status', 'pending')->count();
            $totalTicketsSold = OrderDetail::count();
            $requests = EventRequest::latest()->get();
            return view('admin.dashboard', compact('totalUsers', 'totalEvents', 'totalPendingRequests', 'totalTicketsSold', 'requests'));
        } elseif (auth()->user()->role === 'organizer') {
            // Dashboard Organizer
            $approvedRequests = EventRequest::where('users_id', auth()->id())
                                ->where('status', 'approved')
                                ->with(['event.category', 'event.performers.genres'])
                                ->get();
            $totalMyEvents = $approvedRequests->count();
            
            $myEventIds = $approvedRequests->pluck('event_id');
            
            // Hitung Tiket Valid (yang statusnya valid/belum digunakan)
            $totalTicketsValid = OrderDetail::whereHas('ticket.event_schedule.event', function($q) use ($myEventIds) {
                $q->whereIn('id', $myEventIds);
            })->where('status', 'valid')->count();

            // Hitung Total Pendapatan
            $totalRevenue = Order::whereIn('events_id', $myEventIds)->sum('total_amount');

            return view('organizer.dashboard', compact('totalMyEvents', 'approvedRequests', 'totalTicketsValid', 'totalRevenue'));
        } else {
            // Dashboard User - Ambil event terbaru yang punya banner untuk dijadikan slide
            $mainBanners = Event::where('status', 'active')->whereNotNull('banner')->latest()->limit(5)->get();
            // Untuk card, kita bisa ambil event yang berbeda atau sekadar limit
            $cardBanners = Event::where('status', 'active')->whereNotNull('banner')->latest()->offset(5)->limit(3)->get();
            
            // Jika offset 5 kosong, kita ambil saja yang ada
            if ($cardBanners->isEmpty()) {
                $cardBanners = Event::where('status', 'active')->whereNotNull('banner')->limit(3)->get();
            }

            $events = Event::where('status', 'active')->latest()->limit(3)->get();
            return view('user.dashboard', compact('mainBanners', 'cardBanners', 'events'));
        }
    }

    public function schedule(Request $request)
    {
        $query = Event::whereHas('event_schedule.tickets')
            ->with(['event_schedule.tickets'])
            ->where('status', 'active');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('location', 'LIKE', '%' . $search . '%');
            });
        }

        $events = $query->orderBy('created_at', 'desc')->get();
        return view('user.schedule', compact('events'));
    }

    public function manageUsers()
    {   
        $users = User::all();
        return view('admin.manage-users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.create-user');
    }   

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone_number' => 'required',
            'role' => 'required',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => $request->role,
            ]);
            return redirect()->route('admin.manageUsers')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }   

    public function updateUser(Request $request, User $user)
    {   
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'required|min:8|confirmed',
            'phone_number' => 'required',

        ]); 
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);
            return redirect()->route('admin.manageUsers')->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }   

    public function deleteUser(User $user)
    {
        if(auth()->user()->id == $user->id){
            return redirect()->route('admin.manageUsers')->with('error', 'Anda tidak bisa menghapus diri sendiri!');
        }
        try {
            $user->delete();
            return redirect()->route('admin.manageUsers')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.manageUsers')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return view('user.notifications', compact('notifications'));
    }

    public function adminReport()
    {
        // 1. Stat Cards
        $totalRevenue = Order::sum('total_amount');
        $totalTicketsSold = OrderDetail::count();
        
        $attendanceStats = DB::table('order_details')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        $totalAttendance = $attendanceStats->sum('count');
        $usedAttendance = $attendanceStats->where('status', 'used')->first()->count ?? 0;
        $attendanceRate = $totalAttendance > 0 ? round(($usedAttendance / $totalAttendance) * 100) : 0;

        // 2. Laporan penjualan tiket per tipe tiket
        $ticketTypeStats = DB::table('order_details')
            ->join('tickets', 'order_details.tickets_id', '=', 'tickets.id')
            ->join('ticket_types', 'tickets.ticket_types_id', '=', 'ticket_types.id')
            ->select(
                'ticket_types.name as category_name', 
                DB::raw('count(*) as total_sold'),
                DB::raw('sum(tickets.price) as total_revenue')
            )
            ->groupBy('ticket_types.name')
            ->get();

        // 3. Pendapatan per kategori event
        $categoryRevenue = DB::table('orders')
            ->join('events', 'orders.events_id', '=', 'events.id')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('sum(orders.total_amount) as revenue'))
            ->groupBy('categories.name')
            ->get();

        // 4. Laporan penjualan perbulan
        $monthlySales = DB::table('orders')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('sum(total_amount) as revenue'),
                DB::raw('count(*) as order_count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $capacityPerCategory = DB::table('tickets')
            ->join('ticket_types', 'tickets.ticket_types_id', '=', 'ticket_types.id')
            ->select('ticket_types.name as category_name', DB::raw('sum(capacity) as total_capacity'))
            ->groupBy('ticket_types.name')
            ->get();

        $quotaStats = $capacityPerCategory->map(function($item) use ($ticketTypeStats) {
            $sold = $ticketTypeStats->firstWhere('category_name', $item->category_name)->total_sold ?? 0;
            return (object) [
                'category_name' => $item->category_name,
                'total_capacity' => $item->total_capacity,
                'remaining' => $item->total_capacity - $sold
            ];
        });

        return view('admin.reports', compact(
            'totalRevenue', 
            'totalTicketsSold', 
            'attendanceRate',
            'ticketTypeStats',
            'categoryRevenue',
            'monthlySales',
            'quotaStats',
            'attendanceStats'
        ));
    }

    public function downloadMonthlyReport()
    {
        $monthlySales = DB::table('orders')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('sum(total_amount) as revenue'),
                DB::raw('count(*) as order_count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.monthly-report-pdf', compact('monthlySales'));
        return $pdf->download('laporan-penjualan-bulanan.pdf');
    }
}
