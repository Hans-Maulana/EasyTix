<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Ubah Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Lama')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Password Baru')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button id="btn-save-password" type="submit" data-confirm="Apakah anda yakin ingin mengubah password?">{{ __('Simpan Perubahan') }}</x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSavePassword = document.getElementById('btn-save-password');
            const newPassword = document.getElementById('update_password_password');
            const confirmPassword = document.getElementById('update_password_password_confirmation');
            const currentPassword = document.getElementById('update_password_current_password');

            // Initially disabled
            btnSavePassword.disabled = true;
            btnSavePassword.style.opacity = '0.5';
            btnSavePassword.style.cursor = 'not-allowed';

            const validatePasswords = () => {
                const valNew = newPassword.value;
                const valConfirm = confirmPassword.value;
                const valCurrent = currentPassword.value;

                // Ensure both new and confirm are not empty, they match, and current is not empty
                const isMatch = valNew === valConfirm && valNew.trim() !== '' && valConfirm.trim() !== '' && valCurrent.trim() !== '';

                if (isMatch) {
                    btnSavePassword.disabled = false;
                    btnSavePassword.style.opacity = '1';
                    btnSavePassword.style.cursor = 'pointer';
                } else {
                    btnSavePassword.disabled = true;
                    btnSavePassword.style.opacity = '0.5';
                    btnSavePassword.style.cursor = 'not-allowed';
                }
            };

            [newPassword, confirmPassword, currentPassword].forEach(input => {
                input.addEventListener('input', validatePasswords);
            });
        });
    </script>
</section>
