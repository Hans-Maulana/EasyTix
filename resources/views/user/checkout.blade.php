@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .checkout-wrapper { font-family: 'Outfit', sans-serif; }
    .payment-option {
        border: 2px solid #eee; border-radius: 15px; padding: 20px;
        margin-bottom: 20px; transition: all 0.3s; cursor: pointer;
    }
    .payment-option:hover { border-color: #F4D03F; }
    .payment-option.active { border-color: #F4D03F; background: rgba(244, 208, 63, 0.05); }
    .btn-premium {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000; border: none; font-weight: 700; border-radius: 50px;
        padding: 15px 40px; transition: all 0.3s ease;
    }
    .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(244, 208, 63, 0.3); }
    .btn-premium:disabled {
        background: #ccc !important; color: #666 !important; cursor: not-allowed;
        transform: none !important; box-shadow: none !important;
    }

    /* Premium Radio Gender Style */
    .gender-radio {
        display: none;
    }
    .gender-label {
        display: inline-block;
        padding: 10px 25px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        color: #ffffffff;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
        font-size: 0.9rem;
    }
    .gender-label:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(244, 208, 63, 0.3);
        color: #fff;
    }
    .gender-radio:checked + .gender-label {
        background: var(--premium-gold-grad);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(244, 208, 63, 0.3);
        transform: scale(1.05);
    }
    .gender-label i {
        font-size: 1rem;
    }

    .form-check-input:checked {
        background-color: #F4D03F !important;
        border-color: #F4D03F !important;
    }
</style>
@endsection

@section('content')
<div class="container checkout-wrapper pt-5 pb-4">
    <div class="page-inner">
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down" data-aos-delay="100">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.buyTickets') }}" class="text-muted text-decoration-none">Beli Tiket</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #ffffffff; font-weight: 700;">Checkout</li>
            </ol>
        </nav>

        <div class="mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3" data-aos="fade-down">
            <div>
                <h2 class="fw-bold mb-0"><i class="fas fa-ticket-alt text-warning me-2"></i> Detail <span class="text-warning">Pesanan</span></h2>
                <p class="text-muted small mt-2 mb-0">Pastikan data pemilik tiket sesuai dengan kartu identitas (KTP/SIM/Paspor).</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="timer-badge p-2 px-3 rounded-pill" style="background: rgba(244, 208, 63, 0.1); border: 1px solid rgba(244, 208, 63, 0.3); color: #F4D03F;">
                   <i class="fas fa-clock me-2"></i> <span id="checkout-timer" style="font-weight: 800; font-size: 1.1rem;">05:00</span>
                </div>
            </div>
        </div>

        <form action="{{ route('user.saveDetails') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7" data-aos="fade-right">
                    <!-- Informasi Kontak Global -->
                    <div class="card border-0 rounded-4 p-4 shadow-sm mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="fw-bold mb-0"><i class="fas fa-id-card text-warning me-2"></i> Kontak Pengiriman Tiket</h5>
                            <div class="self-data-container p-2 px-3 rounded-pill" style="background: rgba(244, 208, 63, 0.1); border: 1px solid rgba(244, 208, 63, 0.4);">
                                <div class="form-check m-0 d-flex align-items-center">
                                    <input class="form-check-input border-warning me-2" type="checkbox" id="isSelf" onchange="toggleSelfData(this)" style="cursor: pointer; transform: scale(1.1);">
                                    <label class="form-check-label text-warning small fw-bolder mt-1" for="isSelf" style="cursor: pointer;">
                                        GUNAKAN DATA SAYA
                                    </label>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-4">E-ticket PDF dan QR Code akan dikirimkan ke Email ini.</p>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-light">Email Pengiriman Tiket</label>
                                <input type="email" name="global_email" id="global_email" class="form-control rounded-pill req-field" placeholder="example@mail.com" required oninput="syncContactData()">
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 rounded-4 p-4 shadow-sm mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Detail Pemilik Tiket</h5>
                        </div>
                        @php $ticketIdx = 0; @endphp
                        @foreach($cart as $id => $details)
                            @for($i = 0; $i < $details['quantity']; $i++)
                                @php 
                                    $ticketIdx++;
                                    $isFirst = $ticketIdx == 1;
                                    $prevData = $ticketDetails[$id][$i] ?? [];
                                @endphp
                                <div class="ticket-detail-group mb-4 p-4 border border-light border-opacity-10 rounded-4 bg-transparent ticket-form-card" data-index="{{ $ticketIdx }}">
                                    <h6 class="fw-bold text-warning mb-4 d-flex align-items-center">
                                        <span class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">{{ $ticketIdx }}</span>
                                        {{ $details['name'] }} ({{ $details['type'] }})
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label small fw-bold text-light">Nama Lengkap Sesuai KTP</label>
                                            <input type="text" name="tickets[{{ $id }}][{{ $i }}][name]" id="{{ $isFirst ? 'first_name' : '' }}" class="form-control rounded-pill req-field" placeholder="Nama Lengkap" value="{{ $prevData['name'] ?? '' }}" required>
                                        </div>
                                        
                                        <!-- Hidden inputs for backend compatibility -->
                                        <input type="hidden" name="tickets[{{ $id }}][{{ $i }}][email]" class="ticket-email-input" value="{{ $prevData['email'] ?? '' }}">
                                        <input type="hidden" name="tickets[{{ $id }}][{{ $i }}][gender]" value="Laki-laki">
                                        <input type="hidden" name="tickets[{{ $id }}][{{ $i }}][age]" value="20">
                                    </div>
                                </div>
                            @endfor
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: rgba(255,255,255,0.05);">
                        <h5 class="fw-bold mb-4">Total Bayar</h5>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $details)
                            @php $total += $details['price'] * $details['quantity']; @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">{{ $details['name'] }} ({{ $details['quantity'] }}x)</span>
                                <span class="small fw-bold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-4 mt-3">
                            <span class="fs-4">Total Amount</span>
                            <span class="fs-4 fw-bold text-warning">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" id="btnContinue" class="btn btn-premium w-100 py-3 mb-2" disabled>
                            LANJUT KE PEMBAYARAN <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <a href="{{ route('user.buyTickets') }}" class="btn btn-outline-light w-100 rounded-pill py-2 mt-2" style="border-width: 2px; font-weight: 600;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('ExtraJS')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.syncContactData = function() {
        const email = document.getElementById('global_email').value;
        
        document.querySelectorAll('.ticket-email-input').forEach(input => {
            input.value = email;
        });
    };

    window.toggleSelfData = function(checkbox) {
        const nameInput = document.getElementById('first_name');
        const globalEmailInput = document.getElementById('global_email');
        
        if (checkbox.checked) {
            if (nameInput) nameInput.value = "{{ auth()->user()->name }}";
            globalEmailInput.value = "{{ auth()->user()->email }}";
        } else {
            if (nameInput) nameInput.value = "";
            globalEmailInput.value = "";
        }
        
        window.syncContactData();
        
        // Trigger manual update check
        const event = new Event('formUpdate');
        document.dispatchEvent(event);
    };

    document.addEventListener("DOMContentLoaded", function() {
        // Initial sync if data exists
        window.syncContactData();
        // Initialize or update timer
        let expiryTime = localStorage.getItem('payment_expiry');
        if (!expiryTime) {
            expiryTime = new Date().getTime() + (5 * 60 * 1000);
            localStorage.setItem('payment_expiry', expiryTime);
        }

        const timerDisplay = document.getElementById('checkout-timer');
        const timerInterval = setInterval(() => {
            const now = new Date().getTime();
            const diff = expiryTime - now;

            if (diff <= 0) {
                clearInterval(timerInterval);
                localStorage.removeItem('payment_expiry');
                window.location.href = "{{ route('user.buyTickets') }}?payment_failed=true";
            } else {
                const m = Math.floor(diff / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                timerDisplay.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
        }, 1000);

        const btnContinue = document.getElementById('btnContinue');
        const reqFields = document.querySelectorAll('.req-field');

        function checkForm() {
            let allFilled = true;
            reqFields.forEach(field => {
                if (!field.value || field.value.trim() === "") {
                    allFilled = false;
                }
            });
            btnContinue.disabled = !allFilled;
        }

        reqFields.forEach(field => {
            field.addEventListener('input', checkForm);
            field.addEventListener('change', checkForm);
        });

        document.addEventListener('formUpdate', checkForm);

        checkForm();
        AOS.init({ once: true, duration: 800 });
    });
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
@endsection
