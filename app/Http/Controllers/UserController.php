<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\EventSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

use App\Models\EventRequest;

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
            $requests = EventRequest::latest()->get();
            return view('admin.dashboard', compact('totalUsers', 'totalEvents', 'totalPendingRequests', 'requests'));
        } elseif (auth()->user()->role === 'organizer') {
            // Dashboard Organizer
            $approvedRequests = EventRequest::where('users_id', auth()->id())
                                ->where('status', 'approved')
                                ->with('event')
                                ->get();
            $totalMyEvents = $approvedRequests->count();
            return view('organizer.dashboard', compact('totalMyEvents', 'approvedRequests'));
        } else {
            // Dashboard User
            $mainBanners = Banner::where('status', 'active')->where('type', 'main')->get();
            $cardBanners = Banner::where('status', 'active')->where('type', 'card')->get();
            $events = Event::where('status', 'active')->latest()->limit(3)->get();
            return view('user.dashboard', compact('mainBanners', 'cardBanners', 'events'));
        }
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
}
