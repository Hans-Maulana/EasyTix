<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/login')->with('status', 'Email Anda telah diperbarui. Silakan verifikasi email baru Anda melalui tautan yang kami kirimkan, lalu login kembali.');
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Cek apakah user memiliki data yang berafiliasi (Pemesanan atau Waiting List)
        $hasOrders = \App\Models\Order::where('users_id', $user->id)->exists();
        $hasWaitingList = \App\Models\WaitingList::where('user_id', $user->id)->exists();

        Auth::logout();

        if ($hasOrders || $hasWaitingList) {
            // Jika ada data penting, jangan hapus barisnya (biar tidak error FK)
            // Cukup nonaktifkan dan ganti email agar email aslinya bisa buat daftar lagi
            $oldEmail = $user->email;
            $user->update([
                'is_active' => false,
                'email' => 'deleted_' . $user->id . '_' . time() . '_' . $oldEmail,
                'phone_number' => '0000000000', // Reset nomor telepon agar tidak konflik jika ada unik
            ]);
        } else {
            // Jika benar-benar bersih, hapus total
            $user->delete();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Akun Anda telah berhasil dihapus/dinonaktifkan.');
    }
}
