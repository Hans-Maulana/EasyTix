<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\BannerController;

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

    // Manage Ticket Types
    Route::get('/admin/ticket-types', [TicketTypeController::class, 'manageTicketTypes'])->name('admin.manageTicketTypes');
    Route::get('/admin/ticket-types/create', [TicketTypeController::class, 'createTicketType'])->name('admin.createTicketType');
    Route::post('/admin/ticket-types', [TicketTypeController::class, 'storeTicketType'])->name('admin.storeTicketType');
    Route::get('/admin/ticket-types/{ticketType}/edit', [TicketTypeController::class, 'editTicketType'])->name('admin.editTicketType');
    Route::put('/admin/ticket-types/{ticketType}', [TicketTypeController::class, 'updateTicketType'])->name('admin.updateTicketType');
    Route::delete('/admin/ticket-types/{ticketType}', [TicketTypeController::class, 'deleteTicketType'])->name('admin.deleteTicketType');

    // Manage Banners
    Route::get('/admin/banners', [BannerController::class, 'manageBanners'])->name('admin.manageBanners');
    Route::get('/admin/banners/create', [BannerController::class, 'createBanner'])->name('admin.createBanner');
    Route::post('/admin/banners', [BannerController::class, 'storeBanner'])->name('admin.storeBanner');
    Route::get('/admin/banners/{banner}/edit', [BannerController::class, 'editBanner'])->name('admin.editBanner');
    Route::put('/admin/banners/{banner}', [BannerController::class, 'updateBanner'])->name('admin.updateBanner');
    Route::delete('/admin/banners/{banner}', [BannerController::class, 'deleteBanner'])->name('admin.deleteBanner');

    // Manage Genres
    Route::get('/admin/genres', [GenreController::class, 'manageGenres'])->name('admin.manageGenres');
    Route::get('/admin/genres/create', [GenreController::class, 'createGenre'])->name('admin.createGenre');
    Route::post('/admin/genres', [GenreController::class, 'storeGenre'])->name('admin.storeGenre');
    Route::get('/admin/genres/{genre}/edit', [GenreController::class, 'editGenre'])->name('admin.editGenre');
    Route::put('/admin/genres/{genre}', [GenreController::class, 'updateGenre'])->name('admin.updateGenre');
    Route::delete('/admin/genres/{genre}', [GenreController::class, 'deleteGenre'])->name('admin.deleteGenre');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
