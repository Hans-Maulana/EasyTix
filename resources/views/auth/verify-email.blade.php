<x-auth-modern-layout>
    <div class="auth-logo" style="display: flex; justify-content: center; margin-bottom: 24px; align-items: center;">
        <a href="/" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('assets/img/logo_easy_tix.jpeg') }}" alt="EasyTix Logo" style="height: 60px; border-radius: 12px;">
        </a>
    </div>

    <div class="auth-header">
        <h1 class="auth-title">Verifikasi Email</h1>
        <p class="auth-subtitle">Terima kasih telah mendaftar! Silahkan cek kotak masuk atau folder spam email Anda untuk link verifikasi sebelum melanjutkan.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background-color: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; padding: 12px; border-radius: 4px; margin-bottom: 24px;">
            <p style="color: #10b981; font-size: 14px; margin: 0; font-weight: 500;">
                <i class="fas fa-check-circle" style="margin-right: 5px;"></i> Email verifikasi baru telah dikirim ke alamat email Anda!
            </p>
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 10px;">
        <form method="POST" action="{{ route('verification.send') }}" style="width: 100%;">
            @csrf
            <button type="submit" class="submit-btn" style="width: 100%;">
                Kirim Ulang Email Verifikasi
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-left:8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="width: 100%; text-align: center; margin-top: 10px;">
            @csrf
            <button type="submit" class="auth-link" style="background: transparent; border: none; cursor: pointer; font-size: 14px;">
                <i class="fas fa-sign-out-alt"></i> Gunakan Akun Lain (Logout)
            </button>
        </form>
    </div>
</x-auth-modern-layout>
