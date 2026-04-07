<x-auth-modern-layout>
    <div class="auth-logo" style="display: flex; justify-content: center; margin-bottom: 24px; align-items: center;">
        <a href="/" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('assets/img/logo_easytix_new.png') }}" alt="EasyTix Logo" style="height: 60px;">
        </a>
    </div>

    <div class="auth-header">
        <h1 class="auth-title">Buat Sandi Baru</h1>
        <p class="auth-subtitle">Silakan masukkan kata sandi baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly style="background-color: rgba(255,255,255,0.05); color: #888; cursor: not-allowed;" />
            @if ($errors->has('email'))
                <div class="form-error" style="color: #ff6b6b; margin-top: 5px; font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20" style="vertical-align: middle;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi Baru</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            @if ($errors->has('password'))
                <div class="form-error" style="color: #ff6b6b; margin-top: 5px; font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20" style="vertical-align: middle;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Sandi Baru</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            @if ($errors->has('password_confirmation'))
                <div class="form-error" style="color: #ff6b6b; margin-top: 5px; font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20" style="vertical-align: middle;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ $errors->first('password_confirmation') }}
                </div>
            @endif
        </div>

        <button type="submit" class="submit-btn mt-2">
            Reset Password
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-left: 5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </button>
    </form>
</x-auth-modern-layout>
