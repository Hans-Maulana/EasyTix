<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            \Illuminate\Support\Facades\Auth::logout();
            return redirect()->route('login')->with('status', 'Email Anda sudah terverifikasi sebelumnya. Silakan login.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        \Illuminate\Support\Facades\Auth::logout();
        return redirect()->route('login')->with('status', 'Email Anda berhasil diverifikasi. Silakan login kembali untuk melanjutkan.');
    }
}
