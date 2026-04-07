<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role !== 'user') {
            // Redirect admin to admin dashboard, organizer to organizer dashboard
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Halaman ini hanya untuk pengguna biasa.');
            }
            if (auth()->user()->role === 'organizer') {
                return redirect()->route('organizer.dashboard')
                    ->with('error', 'Halaman ini hanya untuk pengguna biasa.');
            }
        }
        return $next($request);
    }
}
