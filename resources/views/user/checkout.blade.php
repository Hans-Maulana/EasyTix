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
</style>
@endsection

@section('content')
<div class="container checkout-wrapper py-4">
    <div class="page-inner">
        <div class="mb-5" data-aos="fade-down">
            <h2 class="fw-bold mb-0"><i class="fas fa-credit-card text-warning me-2"></i> Konfirmasi <span class="text-warning">Pembayaran</span></h2>
        </div>

        <form action="{{ route('user.processOrder') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7" data-aos="fade-right">
                    <h5 class="fw-bold mb-4">Metode Pembayaran</h5>
                    
                    <div class="payment-option active" onclick="selectPayment('QRIS')">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-qrcode fs-2 me-3 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-0">QRIS (OVO, GoPay, ShopeePay)</h6>
                                <small class="text-muted italic">Cepat, Aman & Terverifikasi Otomatis</small>
                            </div>
                            <input type="radio" name="payment_method" value="QRIS" checked class="ms-auto">
                        </div>
                    </div>

                    <div class="payment-option" onclick="selectPayment('Virtual Account')">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-university fs-2 me-3 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Virtual Account (BCA, Mandiri, BNI)</h6>
                                <small class="text-muted italic">Transfer manual lewat bank favoritmu</small>
                            </div>
                            <input type="radio" name="payment_method" value="Virtual Account" class="ms-auto">
                        </div>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                    <div class="card border-0 rounded-4 p-4 shadow-sm bg-light">
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
                        <button type="submit" class="btn btn-premium w-100 py-3 mb-2">
                            BAYAR SEKARANG
                        </button>
                        <p class="text-center text-muted small mt-3">
                            <i class="fas fa-lock me-1"></i> Pembayaran Aman & Terenkripsi
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('ExtraJS')
<script>
    function selectPayment(method) {
        document.querySelector(`input[value="${method}"]`).checked = true;
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
