<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['auth', 'organizer'])->group(function () {
    Route::get('/organizer/dashboard', [OrganizerController::class, 'index'])->name('organizer.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manageUsers');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Manage Users
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.manageUsers');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.storeUser');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
