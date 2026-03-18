<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GenreController;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard/manage-users', [UserController::class, 'manageUsers'])->name('admin.manageUsers');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [UserController::class, 'index'])->name('admin.dashboard');

    // Manage Users
    Route::get('/admin/users', [UserController::class, 'manageUsers'])->name('admin.manageUsers');
    Route::get('/admin/users/create', [UserController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/users', [UserController::class, 'storeUser'])->name('admin.storeUser');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'editUser'])->name('admin.editUser');
    Route::put('/admin/users/{user}', [UserController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/admin/users/{user}', [UserController::class, 'deleteUser'])->name('admin.deleteUser');

    // Manage Events
    Route::get('/admin/events', [EventController::class, 'manageEvents'])->name('admin.manageEvents');
    Route::get('/admin/events/create', [EventController::class, 'createEvent'])->name('admin.createEvent');
    Route::post('/admin/events', [EventController::class, 'storeEvent'])->name('admin.storeEvent');
    Route::get('/admin/events/{event}/edit', [EventController::class, 'editEvent'])->name('admin.editEvent');
    Route::put('/admin/events/{event}', [EventController::class, 'updateEvent'])->name('admin.updateEvent');
    Route::delete('/admin/events/{event}', [EventController::class, 'deleteEvent'])->name('admin.deleteEvent');

    // Manage Schedule
    Route::get('/admin/events/{event}/schedule', [EventController::class, 'scheduleEvent'])->name('admin.scheduleEvent');
    Route::get('/admin/events/{event}/schedule/create', [EventController::class, 'createSchedule'])->name('admin.createSchedule');
    Route::post('/admin/events/{event}/schedule', [EventController::class, 'storeSchedule'])->name('admin.storeSchedule');
    Route::get('/admin/events/{event}/schedule/{schedule}/edit', [EventController::class, 'editSchedule'])->name('admin.editSchedule');
    Route::put('/admin/events/{event}/schedule/{schedule}', [EventController::class, 'updateSchedule'])->name('admin.updateSchedule');
    Route::delete('/admin/events/{event}/schedule/{schedule}', [EventController::class, 'deleteSchedule'])->name('admin.deleteSchedule');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
