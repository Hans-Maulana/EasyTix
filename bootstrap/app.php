<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'organizer' => \App\Http\Middleware\OrganizerMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function () {
            if (auth()->check()) {
                if (auth()->user()->role === 'admin') {
                    return '/admin/dashboard';
                } elseif (auth()->user()->role === 'organizer') {
                    return '/organizer/dashboard';
                }
                return '/user/dashboard';
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
