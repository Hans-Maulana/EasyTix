@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .payment-wrapper { font-family: 'Outfit', sans-serif; }
    .payment-option {
        border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 25px;
        margin-bottom: 20px; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer; position: relative; background: rgba(255, 255, 255, 0.05); color: #fff;
    }
    .payment-option:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.3); border-color: #F4D03F; background: rgba(255, 255, 255, 0.08); }
    .payment-option.active { border-color: #F4D03F; background: rgba(244, 208, 63, 0.1); box-shadow: 0 5px 20px rgba(244, 208, 63, 0.2); }
    
    .payment-option .check-mark {
        position: absolute; top: 20px; right: 20px; width: 24px; height: 24px;
        border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center;
        transition: all 0.3s;
    }
    .payment-option.active .check-mark { background: #F4D03F; border-color: #F4D03F; color: #000; }
    .payment-option.active .check-mark::after { content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900; font-size: 12px; }

    .bank-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px; }
    .bank-card {
        border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 15px; padding: 15px; text-align: center;
        transition: all 0.3s; cursor: pointer; background: rgba(255, 255, 255, 0.05); position: relative;
    }
    .bank-card:hover { border-color: #F4D03F; transform: scale(1.05); background: rgba(255,255,255,0.1); }
    .bank-card.active { border-color: #F4D03F; background: rgba(244, 208, 63, 0.15); box-shadow: 0 5px 15px rgba(244, 208, 63, 0.2); }
    .bank-card img { height: 45px; width: 100%; object-fit: contain; transition: all 0.3s; filter: brightness(0.9); }
    .bank-card.active img { transform: scale(1.1); filter: brightness(1); }

    .btn-premium {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000; border: none; font-weight: 800; border-radius: 50px;
        padding: 15px 40px; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-premium:disabled { background: #ccc !important; color: #666 !important; cursor: not-allowed; transform: none !important; box-shadow: none !important; }

    #qrisOverlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px);
        z-index: 9999; display: none; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .qris-modal {
        background: rgba(15, 25, 45, 0.95); border-radius: 30px; padding: 30px;
        max-width: 600px; width: 95%; text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5); transform: scale(0.9); transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1); color: #fff;
    }
    #qrisOverlay.active { display: flex; }
    #qrisOverlay.active .qris-modal { transform: scale(1); }
    .timer-badge {
        background: rgba(244, 208, 63, 0.15); color: #F4D03F; padding: 6px 15px;
        border-radius: 50px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;
        border: 1px solid rgba(244, 208, 63, 0.3); margin-bottom: 15px;
        backdrop-filter: blur(10px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); font-size: 0.85rem;
    }
    .timer-badge.warning {
        background: rgba(231, 76, 60, 0.15); color: #e74c3c; border-color: rgba(231, 76, 60, 0.3);
        animation: pulse 1s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endsection

@section('content')
<div class="container payment-wrapper py-4">
    <div class="page-inner">
        <div class="mb-5" data-aos="fade-down">
            <h2 class="fw-bold mb-0"><i class="fas fa-credit-card text-warning me-2"></i> Konfirmasi <span class="text-warning">Pembayaran</span></h2>
        </div>

        <form id="paymentForm" action="{{ route('user.processOrder') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Pilih Metode Pembayaran</h5>
                        <div class="timer-badge" id="mainTimerContainer">
                            <i class="fas fa-clock"></i>
                            <span id="mainTimer">03:00</span>
                        </div>
                    </div>
                    
                    <div class="payment-option" id="optQRIS" onclick="selectPayment('QRIS')">
                        <div class="check-mark"></div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning-subtle p-3 rounded-4 me-3">
                                <i class="fas fa-qrcode fs-2 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">QRIS (OVO, GoPay, ShopeePay)</h6>
                                <small class="text-muted">Cepat, Aman & Terverifikasi Otomatis</small>
                            </div>
                            <input type="radio" name="payment_method" value="QRIS" class="ms-auto" style="display:none;">
                        </div>
                    </div>

                    <div class="payment-option" id="optVA" onclick="selectPayment('Virtual Account')">
                        <div class="check-mark"></div>
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-warning-subtle p-3 rounded-4 me-3">
                                <i class="fas fa-university fs-2 text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0">Virtual Account</h6>
                                <small class="text-muted">BCA, Mandiri, BNI</small>
                            </div>
                            <input type="radio" name="payment_method" value="Virtual Account" class="ms-auto" style="display:none;">
                        </div>
                        
                        <div id="bankSelection" class="mt-4 animate__animated animate__fadeIn" style="display:none;">
                            <label class="small fw-bold text-muted mb-3">PILIH BANK TUJUAN:</label>
                            <div class="bank-grid">
                                <div class="bank-card" onclick="selectBank('BCA')">
                                    <img src="{{ asset('assets/img/bca.jpg') }}" alt="BCA">
                                </div>
                                <div class="bank-card" onclick="selectBank('Mandiri')">
                                    <img src="{{ asset('assets/img/mandiri.jpg') }}" alt="Mandiri">
                                </div>
                                <div class="bank-card" onclick="selectBank('BNI')">
                                    <img src="{{ asset('assets/img/bni.jpg') }}" alt="BNI">
                                </div>
                            </div>
                            <input type="hidden" id="selectedBank" value="">
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-4 border-start border-warning border-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <small class="text-white d-block mb-1"><i class="fas fa-info-circle me-1"></i> Informasi:</small>
                        <p class="small mb-0 text-light">Tiket elektronik Anda akan langsung dikirim ke menu <strong>Tiket Saya</strong> dan email setelah pembayaran dikonfirmasi oleh sistem.</p>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: rgba(255,255,255,0.05);">
                        <h5 class="fw-bold mb-4 text-white">Ringkasan Pembayaran</h5>
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
                            <span class="fs-4">Total Bayar</span>
                            <span class="fs-4 fw-bold text-warning">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button type="button" id="btnNext" class="btn btn-premium w-100 py-3 mb-2" disabled>
                            Lanjutkan <i class="fas fa-arrow-right ms-2 transition-icon"></i>
                        </button>
                        <a href="{{ route('user.checkout') }}" class="btn btn-outline-light w-100 rounded-pill py-2 mt-2" style="border-width: 2px; font-weight: 600;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- QRIS Overlay -->
<div id="qrisOverlay">
    <div class="qris-modal" data-aos="zoom-in">
        <div class="timer-badge warning">
            <i class="fas fa-history me-2"></i> <span id="qrisTimer">03:00</span>
        </div>
        <h3 class="fw-bold mb-3">Scan QRIS</h3>
        <div class="p-3 border rounded-4 mb-3 bg-white shadow-sm" style="display:inline-block; border: 2px solid #f0f0f0;">
            <img src="{{ asset('assets/img/qr_payment.jpeg') }}" class="img-fluid" style="max-height: 300px; width: auto;" alt="QRIS Code">
        </div>
        <div class="text-center mb-4">
            <h5 class="text-muted small mb-1">TOTAL BAYAR</h5>
            <h3 class="fw-bold text-warning fs-2">Rp {{ number_format($total, 0, ',', '.') }}</h3>
        </div>
        <button type="button" onclick="finalizePayment()" class="btn btn-premium w-100 py-3 rounded-pill shadow mb-3">
            SELESAIKAN PEMBAYARAN <i class="fas fa-check-circle ms-2"></i>
        </button>
        <button type="button" onclick="closeQrisOverlay()" class="btn btn-link text-muted text-decoration-none small">
            <i class="fas fa-times me-1"></i> Batal & Pilih Metode Lain
        </button>
    </div>
</div>
@endsection

@section('ExtraJS')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize timer if not already set
        let expiryTime = localStorage.getItem('payment_expiry');
        if (!expiryTime) {
            expiryTime = new Date().getTime() + (3 * 60 * 1000);
            localStorage.setItem('payment_expiry', expiryTime);
        }
        
        const mainTimerDisplay = document.getElementById('mainTimer');
        const mainTimerContainer = document.getElementById('mainTimerContainer');
        const qrisTimerDisplay = document.getElementById('qrisTimer');

        const timerInterval = setInterval(() => {
            const now = new Date().getTime();
            const diff = expiryTime - now;
            
            if(diff <= 0) {
                clearInterval(timerInterval);
                localStorage.removeItem('payment_expiry');
                window.location.href = "{{ route('user.buyTickets') }}?payment_failed=true";
            } else {
                let m = Math.floor(diff / 60000);
                let s = Math.floor((diff % 60000) / 1000);
                let timeString = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                
                mainTimerDisplay.innerText = timeString;
                qrisTimerDisplay.innerText = timeString;

                if(diff <= 60000) {
                    mainTimerContainer.classList.add('warning');
                }
            }
        }, 1000);

        AOS.init({ once: true, duration: 800 });
    });

    function selectPayment(method) {
        document.querySelector(`input[name="payment_method"][value="${method}"]`).checked = true;
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
        
        const bankSelection = document.getElementById('bankSelection');
        if(method === 'QRIS') {
            document.getElementById('optQRIS').classList.add('active');
            bankSelection.style.display = 'none';
        } else {
            document.getElementById('optVA').classList.add('active');
            bankSelection.style.display = 'block';
        }
        
        document.getElementById('btnNext').disabled = false;
    }

    function selectBank(bank) {
        document.querySelectorAll('.bank-card').forEach(card => card.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('selectedBank').value = bank;
        document.getElementById('btnNext').disabled = false;
    }

    document.getElementById('btnNext').addEventListener('click', function() {
        const methodSelection = document.querySelector('input[name="payment_method"]:checked');
        if(!methodSelection) {
            Swal.fire({
                icon: 'warning',
                title: 'Metode Pembayaran',
                text: 'Silakan pilih metode pembayaran terlebih dahulu.',
                confirmButtonColor: '#F4D03F',
                background: '#071120',
                color: '#fff'
            });
            return;
        }

        const method = methodSelection.value;
        if(method === 'QRIS') {
            openQrisOverlay();
        } else {
            const bank = document.getElementById('selectedBank').value;
            if(!bank) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pilih Bank',
                    text: 'Silakan pilih salah satu bank untuk pembayaran Virtual Account.',
                    confirmButtonColor: '#F4D03F',
                    background: '#071120',
                    color: '#fff'
                });
                return;
            }
            window.location.href = "{{ route('user.vaPayment') }}?bank=" + bank;
        }
    });

    function openQrisOverlay() {
        document.getElementById('qrisOverlay').classList.add('active');
    }

    function closeQrisOverlay() {
        document.getElementById('qrisOverlay').classList.remove('active');
    }

    function finalizePayment() {
        localStorage.removeItem('payment_expiry');
        document.getElementById('paymentForm').submit();
    }
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
@endsection
