@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@php
    $themeColor = '#00569c'; // Default BCA Blue
    $logo = 'bca.jpg';
    if($bank == 'Mandiri') {
        $themeColor = '#ffc107'; // Mandiri Yellow
        $logo = 'mandiri.jpg';
    } elseif($bank == 'BNI') {
        $themeColor = '#f15a24'; // BNI Orange
        $logo = 'bni.jpg';
    }
@endphp
<style>
    .va-wrapper { font-family: 'Outfit', sans-serif; min-height: 80vh; display: flex; align-items: center; }
    .va-card {
        border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.4); background: rgba(15, 25, 45, 0.95);
        backdrop-filter: blur(20px); color: #fff;
    }
    .va-header {
        background: {{ $themeColor }}; color: {{ $bank == 'Mandiri' ? '#000' : '#fff' }};
        padding: 30px; text-align: center;
    }
    .timer-circle {
        border: 4px solid {{ $themeColor }}; width: 100px; height: 100px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px; font-weight: 800; font-size: 1.2rem; color: {{ $themeColor }};
    }
    .va-number-box {
        background: rgba(255,255,255,0.05); border: 2px dashed {{ $themeColor }};
        border-radius: 15px; padding: 20px; position: relative;
    }
    .va-number-box h3 { color: #fff; }
    .btn-copy {
        position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
        color: {{ $themeColor }}; cursor: pointer; border: none; background: none;
    }
    .copy-toast {
        background: {{ $themeColor == '#ffc107' ? '#000' : $themeColor }}; color: #fff;
        font-size: 0.7rem; padding: 4px 10px; border-radius: 5px;
        position: absolute; right: 50px; top: 50%; transform: translateY(-50%);
        display: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 10;
    }
    .btn-va-finish {
        background: {{ $themeColor }}; color: {{ $bank == 'Mandiri' ? '#000' : '#fff' }};
        border: none; font-weight: 700; border-radius: 50px; padding: 15px 40px;
        width: 100%; transition: all 0.3s ease;
    }
    .btn-va-finish:hover { opacity: 0.9; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); color: {{ $bank == 'Mandiri' ? '#000' : '#fff' }}; }
</style>
@endsection

@section('content')
<div class="container va-wrapper py-5">
    <div class="row justify-content-center w-100">
        <div class="col-lg-6" data-aos="fade-up">
            <div class="va-card">
                <div class="va-header">
                    <img src="{{ asset('assets/img/'.$logo) }}" alt="{{ $bank }}" style="height: 50px; margin-bottom: 10px; border-radius: 5px;">
                    <h4 class="fw-bold mb-0">Virtual Account {{ $bank }}</h4>
                </div>
                
                <div class="p-5 text-center">
                    <div class="timer-circle">
                        <span id="vaCountdown">02:00</span>
                    </div>
                    <p class="text-muted small mb-4">Selesaikan pembayaran sebelum waktu habis</p>

                    <div class="text-start mb-4">
                        <label class="small fw-bold text-muted mb-2">NOMOR VIRTUAL ACCOUNT</label>
                        <div class="va-number-box">
                            <h3 class="fw-bold mb-0" id="vaNumber">{{ rand(1000000000, 9999999999) }}{{ rand(1000, 9999) }}</h3>
                            <span id="copyToast" class="copy-toast">Tersalin!</span>
                            <button class="btn-copy" onclick="copyVA()" title="Salin Nomor">
                                <i class="far fa-copy fs-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="text-start mb-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Pembayaran</span>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $details)
                                @php $total += $details['price'] * $details['quantity']; @endphp
                            @endforeach
                            <span class="fw-bold text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Metode</span>
                            <span class="fw-bold text-white">VA {{ $bank }}</span>
                        </div>
                    </div>

                    <form action="{{ route('user.processOrder') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="Virtual Account {{ $bank }}">
                        <button type="submit" class="btn btn-va-finish shadow-sm">
                            SELESAIKAN PEMBAYARAN <i class="fas fa-check-circle ms-2"></i>
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('user.payment') }}" class="text-muted small text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Batal & Ganti Metode
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
<script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
<script>
    function copyVA() {
        const vaNum = document.getElementById('vaNumber').innerText;
        const toast = document.getElementById('copyToast');
        
        navigator.clipboard.writeText(vaNum).then(() => {
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 2000);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        let timeLeft = 120; // 2 minutes
        const timerDisplay = document.getElementById('vaCountdown');

        const timer = setInterval(() => {
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(timer);
                window.location.href = "{{ route('user.payment') }}?timeout=true";
            } else {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerDisplay.innerText = 
                    (minutes < 10 ? '0' : '') + minutes + ":" + 
                    (seconds < 10 ? '0' : '') + seconds;
            }
        }, 1000);
    });
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
