<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->from('noreply@easytix.id', 'EasyTix')
                ->subject('Reset Password Akun EasyTix Anda')
                ->view('emails.reset-password', [
                    'url' => $url,
                    'user' => $notifiable
                ]);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->from('noreply@easytix.id', 'EasyTix')
                ->subject('Verifikasi Akun EasyTix Anda')
                ->view('emails.verify-email', [
                    'url' => $url,
                    'user' => $notifiable
                ]);
        });
    }
}
