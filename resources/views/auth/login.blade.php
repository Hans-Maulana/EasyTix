<x-auth-modern-layout>
    <div class="auth-logo" style="display: flex; justify-content: center; margin-bottom: 24px; align-items: center;">
        <a href="/" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('assets/img/logo_easytix_new.png') }}" alt="EasyTix Logo" style="height: 60px;">
        </a>
    </div>




    <div class="auth-header">
        <h1 class="auth-title">Selamat Datang!</h1>
        <p class="auth-subtitle">Masuk untuk mengelola tiket dan temukan pengalaman seru bersama kami.</p>
    </div>

    <!-- Session Status -->
    <div class="mb-4">
        <x-auth-session-status :status="session('status')" />
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
            @if ($errors->has('email'))
                <div class="form-error">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            @if ($errors->has('password'))
                <div class="form-error">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <!-- Remember & Forgot -->
        <div class="auth-footer-links">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Lupa sandi?</a>
            @endif
        </div>

        <button type="submit" class="submit-btn">
            Masuk Sekarang
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>

        <div class="register-text">
            Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar di sini</a>
        </div>
    </form>
</x-auth-modern-layout>



