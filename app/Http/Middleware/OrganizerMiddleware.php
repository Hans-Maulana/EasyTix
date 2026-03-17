<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizerMiddleware
{
   public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 'organizer') {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }

}
