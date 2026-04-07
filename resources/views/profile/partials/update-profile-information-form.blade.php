<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update informasi profil dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="mb-4">
            <x-input-label for="profile_photo" :value="__('Foto Profil (Otomatis)')" />
            <div class="d-flex align-items-center mt-3 gap-4">
                <div class="avatar avatar-xl">
                    <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 4px solid #F4D03F; box-shadow: 0 4px 15px rgba(244, 208, 63, 0.2);">
                        <img id="profile-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F4D03F&color=000&size=200" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted small mb-0"><i class="fas fa-magic me-1"></i> Foto profil dibuat otomatis berdasarkan nama Anda untuk menjaga keaslian identitas.</p>
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" placeholder="Masukkan nomor telepon Anda" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button id="btn-save-profile" type="submit" data-confirm="Apakah anda yakin ingin menyimpan perubahan profil ini?">{{ __('Simpan Perubahan') }}</x-primary-button>
        </div>
    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSaveProfile = document.getElementById('btn-save-profile');
            const profileForm = btnSaveProfile.closest('form');
            const inputs = profileForm.querySelectorAll('input:not([type="hidden"])');
            
            // Store initial values
            const initialValues = {};
            inputs.forEach(input => {
                initialValues[input.name] = input.value;
            });

            // Initial state: disabled
            btnSaveProfile.disabled = true;
            btnSaveProfile.style.opacity = '0.5';
            btnSaveProfile.style.cursor = 'not-allowed';

            const checkChanges = () => {
                let hasChanged = false;
                inputs.forEach(input => {
                    if (input.value !== initialValues[input.name]) {
                        hasChanged = true;
                    }
                });

                if (hasChanged) {
                    btnSaveProfile.disabled = false;
                    btnSaveProfile.style.opacity = '1';
                    btnSaveProfile.style.cursor = 'pointer';
                } else {
                    btnSaveProfile.disabled = true;
                    btnSaveProfile.style.opacity = '0.5';
                    btnSaveProfile.style.cursor = 'not-allowed';
                }
            };

            inputs.forEach(input => {
                input.addEventListener('input', checkChanges);
            });
        });

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profile-preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</section>
