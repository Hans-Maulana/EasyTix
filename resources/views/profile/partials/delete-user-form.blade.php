<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Hapus Akun Permanen') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data yang terkait dengannya akan dihapus secara permanen. Sebelum menghapus akun, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    {{-- Hidden form for actual submission --}}
    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" style="display:none;">
        @csrf
        @method('delete')
        <input type="hidden" id="delete-password-input" name="password" value="">
    </form>

    <x-danger-button id="btn-delete-account-trigger" type="button">
        {{ __('Hapus Akun Permanen') }}
    </x-danger-button>

    @if($errors->userDeletion->isNotEmpty())
        <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('btn-delete-account-trigger');
            if (!btn) return;

            btn.addEventListener('click', function () {
                swalPremium.fire({
                    title: '⚠️ Hapus Akun Permanen?',
                    html: `
                        <p style="color:#cbd5e1; font-size:0.95rem; margin-bottom:20px;">
                            Tindakan ini <strong style="color:#ef4444;">tidak dapat dibatalkan</strong>. Semua data pesanan dan tiket Anda akan hilang selamanya.
                        </p>
                        <p style="color:#94a3b8; font-size:0.85rem; margin-bottom:12px;">
                            Masukkan password Anda untuk melanjutkan:
                        </p>
                        <input
                            type="password"
                            id="swal-delete-password"
                            placeholder="Masukkan password Anda"
                            style="
                                width: 100%;
                                padding: 12px 16px;
                                border-radius: 10px;
                                border: 1px solid rgba(255,255,255,0.15);
                                background: rgba(255,255,255,0.08);
                                color: #000000ff;
                                font-size: 0.95rem;
                                outline: none;
                                box-sizing: border-box;
                            "
                        >
                        <p id="swal-pw-hint" style="color:#ef4444; font-size:0.8rem; margin-top:8px; display:none;">
                            Password tidak boleh kosong.
                        </p>
                    `,
                    icon: 'warning',
                    background: '#0d1b2a',
                    color: '#fff',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus Akun Saya',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#475569',
                    customClass: {
                        popup: 'border border-danger shadow-lg',
                        confirmButton: 'swal-confirm-btn'
                    },
                    preConfirm: () => {
                        const pw = document.getElementById('swal-delete-password').value;
                        if (!pw || pw.trim() === '') {
                            document.getElementById('swal-pw-hint').style.display = 'block';
                            Swal.showValidationMessage('Password tidak boleh kosong.');
                            return false;
                        }
                        return pw;
                    },
                    didOpen: () => {
                        // Disable confirm button initially
                        const confirmBtn = Swal.getConfirmButton();
                        confirmBtn.disabled = true;
                        confirmBtn.style.opacity = '0.45';
                        confirmBtn.style.cursor = 'not-allowed';

                        setTimeout(() => {
                            const inp = document.getElementById('swal-delete-password');
                            if (!inp) return;
                            inp.focus();

                            inp.addEventListener('input', () => {
                                document.getElementById('swal-pw-hint').style.display = 'none';
                                Swal.resetValidationMessage();

                                if (inp.value.trim() !== '') {
                                    confirmBtn.disabled = false;
                                    confirmBtn.style.opacity = '1';
                                    confirmBtn.style.cursor = 'pointer';
                                } else {
                                    confirmBtn.disabled = true;
                                    confirmBtn.style.opacity = '0.45';
                                    confirmBtn.style.cursor = 'not-allowed';
                                }
                            });
                        }, 100);
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        // Put the password into the hidden form and submit
                        document.getElementById('delete-password-input').value = result.value;
                        document.getElementById('delete-account-form').submit();
                    }
                });
            });
        });
    </script>
</section>
