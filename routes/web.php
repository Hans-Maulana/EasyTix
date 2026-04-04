<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WaitingListController;
use App\Http\Controllers\EventRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PerformerController;
use App\Http\Controllers\EventScheduleController;
use App\Http\Controllers\OrganizerController;

Route::get('/', function () {
    $events = \App\Models\Event::with(['category', 'performers', 'event_schedule.tickets'])
        ->where('status', 'active')
        ->latest()
        ->get();
    return view('landing-page', compact('events'));
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

    // Manage Request Access
    Route::post('/admin/requests/{request}/approve', [EventRequestController::class, 'approveRequest'])->name('admin.approveRequest');
    Route::post('/admin/requests/{request}/reject', [EventRequestController::class, 'rejectRequest'])->name('admin.rejectRequest');

    // Manage Schedule
    Route::get('/admin/events/{event}/schedule', [EventScheduleController::class, 'scheduleEvent'])->name('admin.scheduleEvent');
    Route::get('/admin/events/{event}/schedule/create', [EventScheduleController::class, 'createSchedule'])->name('admin.createSchedule');
    Route::post('/admin/events/{event}/schedule', [EventScheduleController::class, 'storeSchedule'])->name('admin.storeSchedule');
    Route::get('/admin/events/{event}/schedule/{schedule}/edit', [EventScheduleController::class, 'editSchedule'])->name('admin.editSchedule');
    Route::put('/admin/events/{event}/schedule/{schedule}', [EventScheduleController::class, 'updateSchedule'])->name('admin.updateSchedule');
    Route::delete('/admin/events/{event}/schedule/{schedule}', [EventScheduleController::class, 'deleteSchedule'])->name('admin.deleteSchedule');

    // Manage Ticket Types
    Route::get('/admin/ticket-types', [TicketTypeController::class, 'manageTicketTypes'])->name('admin.manageTicketTypes');
    Route::get('/admin/ticket-types/create', [TicketTypeController::class, 'createTicketType'])->name('admin.createTicketType');

    // Manage Requests (Event access & Waiting List)
    Route::get('/admin/requests-organizer', [EventRequestController::class, 'manageRequestsOrganizer'])->name('admin.requestsOrganizer');
    Route::post('/admin/waiting-list/{waitingList}/approve', [WaitingListController::class, 'approve'])->name('admin.approveWaitingList');
    Route::post('/admin/waiting-list/{waitingList}/reject', [WaitingListController::class, 'reject'])->name('admin.rejectWaitingList');
    Route::post('/admin/ticket-types', [TicketTypeController::class, 'storeTicketType'])->name('admin.storeTicketType');
    Route::get('/admin/ticket-types/{ticketType}/edit', [TicketTypeController::class, 'editTicketType'])->name('admin.editTicketType');
    Route::put('/admin/ticket-types/{ticketType}', [TicketTypeController::class, 'updateTicketType'])->name('admin.updateTicketType');
    Route::delete('/admin/ticket-types/{ticketType}', [TicketTypeController::class, 'deleteTicketType'])->name('admin.deleteTicketType');

    // Manage Categories
    Route::get('/admin/categories', [CategoryController::class, 'manageCategories'])->name('admin.manageCategories');
    Route::get('/admin/categories/create', [CategoryController::class, 'createCategory'])->name('admin.createCategory');
    Route::post('/admin/categories', [CategoryController::class, 'storeCategory'])->name('admin.storeCategory');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'editCategory'])->name('admin.editCategory');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'updateCategory'])->name('admin.updateCategory');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'deleteCategory'])->name('admin.deleteCategory');

    // Manage Performers
    Route::get('/admin/performers', [PerformerController::class, 'managePerformers'])->name('admin.managePerformers');
    Route::get('/admin/performers/create', [PerformerController::class, 'createPerformer'])->name('admin.createPerformer');
    Route::post('/admin/performers', [PerformerController::class, 'storePerformer'])->name('admin.storePerformer');
    Route::get('/admin/performers/{performer}/edit', [PerformerController::class, 'editPerformer'])->name('admin.editPerformer');
    Route::put('/admin/performers/{performer}', [PerformerController::class, 'updatePerformer'])->name('admin.updatePerformer');
    Route::delete('/admin/performers/{performer}', [PerformerController::class, 'deletePerformer'])->name('admin.deletePerformer');

    // Manage Genres
    Route::get('/admin/genres', [GenreController::class, 'manageGenres'])->name('admin.manageGenres');
    Route::get('/admin/genres/create', [GenreController::class, 'createGenre'])->name('admin.createGenre');
    Route::post('/admin/genres', [GenreController::class, 'storeGenre'])->name('admin.storeGenre');
    Route::get('/admin/genres/{genre}/edit', [GenreController::class, 'editGenre'])->name('admin.editGenre');
    Route::put('/admin/genres/{genre}', [GenreController::class, 'updateGenre'])->name('admin.updateGenre');
    Route::delete('/admin/genres/{genre}', [GenreController::class, 'deleteGenre'])->name('admin.deleteGenre');

    // Admin Report
    Route::get('/admin/reports', [UserController::class, 'adminReport'])->name('admin.reports');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/schedule', [UserController::class, 'schedule'])->name('user.schedule');

    // Profile Management
    Route::get('/profile/view', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Order & Booking System
    Route::get('/user/buy-tickets', [OrderController::class, 'index'])->name('user.buyTickets');
    Route::get('/user/event/{id}/tickets', [OrderController::class, 'showEventTickets'])->name('user.eventTickets');
    Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/waitlist', [WaitingListController::class, 'joinWaitingList'])->name('cart.waitlist');
    Route::get('/user/cart', [OrderController::class, 'viewCart'])->name('cart.view');
    Route::patch('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove', [OrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/user/checkout', [OrderController::class, 'checkout'])->name('user.checkout');
    Route::post('/user/payment', [OrderController::class, 'saveDetails'])->name('user.saveDetails');
    Route::get('/user/payment', [OrderController::class, 'showPayment'])->name('user.payment');
    Route::get('/user/va-payment', [OrderController::class, 'vaPayment'])->name('user.vaPayment');
    Route::post('/user/process-order', [OrderController::class, 'processOrder'])->name('user.processOrder');
    Route::get('/user/my-tickets', [OrderController::class, 'myTickets'])->name('user.myTickets');
    Route::get('/user/notifications', [UserController::class, 'notifications'])->name('user.notifications');
    Route::get('/cart/clear', [OrderController::class, 'clearCart'])->name('cart.clear');
});


Route::middleware(['auth', 'organizer'])->group(function () {
    Route::get('/organizer/dashboard', [UserController::class, 'index'])->name('organizer.dashboard');
    Route::get('/organizer/my-events', [OrganizerController::class, 'myEvents'])->name('organizer.myEvents');
    Route::get('/organizer/events', [OrganizerController::class, 'organizerEvents'])->name('organizer.events');
    Route::post('/organizer/events/{event}/request', [EventRequestController::class, 'requestAccess'])->name('organizer.requestAccess');
    
    // Ticket Verification
    Route::get('/organizer/verify-ticket', [OrganizerController::class, 'selectEventVerification'])->name('organizer.selectEventVerification');
    Route::get('/organizer/verify-ticket/{event}', [OrganizerController::class, 'verifyTicketDetail'])->name('organizer.verifyTicketDetail');
    Route::get('/organizer/verify-ticket/schedule/{schedule}', [OrganizerController::class, 'verifySchedule'])->name('organizer.verifySchedule');
    Route::post('/organizer/verify-ticket/schedule/{schedule}', [OrganizerController::class, 'processVerification'])->name('organizer.processVerification');
    
    // Attendees & Waiting List
    Route::get('/organizer/my-events/{event}', [OrganizerController::class, 'myEventsDetail'])->name('organizer.myEventsDetail');
    Route::get('/organizer/schedule/{schedule}/attendees', [OrganizerController::class, 'attendees'])->name('organizer.attendees');
    Route::get('/organizer/schedule/{schedule}/waiting-list', [WaitingListController::class, 'organizerIndex'])->name('organizer.waitingList');
    Route::post('/organizer/waiting-list/{waitingList}/request-admin', [WaitingListController::class, 'requestToAdmin'])->name('organizer.requestWaitingListAdmin');
    
    // Sales Report
    Route::get('/organizer/sales-report', [OrganizerController::class, 'salesReport'])->name('organizer.salesReport');
});

require __DIR__.'/auth.php';
