<x-auth-modern-layout>
    <div class="auth-logo" style="display: flex; justify-content: center; margin-bottom: 24px; align-items: center;">
        <a href="/" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('assets/img/logo_easy_tix.jpeg') }}" alt="EasyTix Logo" style="height: 60px; border-radius: 12px;">
        </a>
    </div>

    <div class="auth-header">
        <h1 class="auth-title">Lupa Kata Sandi?</h1>
        <p class="auth-subtitle">Masukkan email yang terdaftar pada akun anda untuk reset password</p>
    </div>


    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda" />
            @if ($errors->has('email'))
                <div class="form-error" style="color: #ff6b6b; margin-top: 5px; font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20" style="vertical-align: middle;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <button type="submit" class="submit-btn mt-2">
            Kirim Tautan Reset
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-left: 5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>

        <div class="register-text" style="margin-top: 25px;">
            Masih ingat kata sandi? <a href="{{ route('login') }}" class="auth-link">Kembali ke Login</a>
        </div>
    </form>
</x-auth-modern-layout>
