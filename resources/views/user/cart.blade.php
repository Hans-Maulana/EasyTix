@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .cart-wrapper { font-family: 'Outfit', sans-serif; }
    .cart-item {
        border-radius: 20px; overflow: hidden; background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2); backdrop-filter: blur(10px);
        transition: all 0.3s ease; color: #fff;
    }
    .cart-item:hover { background: rgba(255,255,255,0.08); }
    .btn-premium {
        background: var(--premium-gold-grad);
        color: #000; border: none; font-weight: 700; border-radius: 50px;
        padding: 15px 40px; transition: all 0.3s ease;
    }
    .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(244, 208, 63, 0.4); color: #000; }
    
    .qty-control { width: 100%; max-width: 130px; display: flex; align-items: center; justify-content: space-between; background: rgba(0,0,0,0.3); border-radius: 50px; padding: 5px; border: 1px solid rgba(255,255,255,0.1); }
    .qty-control button { border: none; background: rgba(255,255,255,0.1); color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; transition: 0.2s; outline: none; }
    .qty-control button:hover { background: rgba(255,255,255,0.2); }
    .qty-control input { border: none; background: transparent; text-align: center; font-weight: 800; width: 40px; color: #fff; -moz-appearance: textfield; outline: none; }
    .qty-control input::-webkit-outer-spin-button,
    .qty-control input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection

@section('content')
<div class="container cart-wrapper pt-5 pb-4">
    <div class="page-inner">
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down" data-aos-delay="100">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #cbd5e1; font-weight: 700;">Keranjang</li>
            </ol>
        </nav>
        <div class="mb-5" data-aos="fade-down">
            <h2 class="fw-bold mb-0"><i class="fas fa-shopping-basket text-warning me-2"></i> Keranjang <span class="text-warning">Kamu</span></h2>
        </div>

        @if(session('cart') && count(session('cart')) > 0)
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                @php $total = 0; @endphp
                @foreach(session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <div class="cart-item p-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ $details['image'] }}" class="w-100 rounded-4" style="height: 100px; object-fit: cover;">
                            </div>
                            <div class="col-md-5">
                                <h5 class="fw-bold mb-1">{{ $details['name'] }}</h5>
                                <span class="badge bg-warning text-dark mb-2">{{ $details['type'] }}</span>
                                <h6 class="fw-bold text-light">Rp {{ number_format($details['price'], 0, ',', '.') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="qty-control my-2 my-md-0 mx-auto mx-md-0">
                                    <button type="button" class="btn-qty-minus" data-id="{{ $id }}">-</button>
                                    <input type="number" value="{{ $details['quantity'] }}" class="quantity update-cart qty-input-{{ $id }}" data-id="{{ $id }}" min="1" readonly>
                                    <button type="button" class="btn-qty-plus" data-id="{{ $id }}">+</button>
                                </div>
                            </div>
                            <div class="col-md-1 text-end">
                                <button class="btn btn-outline-danger btn-sm rounded-circle remove-from-cart" data-id="{{ $id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4" data-aos="fade-left">
                <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1)!important;">
                    <h5 class="fw-bold mb-4 text-white"><i class="fas fa-file-invoice-dollar text-warning me-2"></i> Ringkasan Pesanan</h5>
                    
                    <div class="border-bottom border-light border-opacity-10 pb-3 mb-3">
                        @php $totalItems = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                        @php $totalItems += $details['quantity']; @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-white">
                                <div class="fw-bold text-warning" style="font-size: 0.95rem;">{{ $details['name'] }}</div>
                                <div class="text-light" style="font-size: 0.85rem;">{{ $details['type'] }} <span class="ms-1 px-2 py-1 rounded" style="background: rgba(255,255,255,0.1); font-size: 0.75rem;">x{{ $details['quantity'] }}</span></div>
                            </div>
                            <span class="fw-bold text-white text-end" style="font-size: 0.95rem;">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 pt-1 text-light">
                        <span>Total Kuantitas Tiket</span>
                        <span class="fw-bold">{{ $totalItems }} Tiket</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-4 border-bottom border-light border-opacity-10 mt-2">
                        <span class="fs-5 text-white">Subtotal Total</span>
                        <span class="fs-5 fw-bold text-warning">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('user.checkout') }}" class="btn btn-premium w-100 mb-3">
                        Checkout Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('user.buyTickets') }}" class="btn btn-outline-light w-100 rounded-pill py-3 fw-bold">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5" data-aos="fade-up">
            <i class="fas fa-shopping-basket text-muted mb-4" style="font-size: 5rem; opacity: 0.3;"></i>
            <h3 class="fw-bold text-muted">Wah, keranjangmu masih kosong nih!</h3>
            <p class="text-muted mb-5">Yuk, cari konser seru dan pesan tiket pertamamu mendayu-dayu.</p>
            <a href="{{ route('user.buyTickets') }}" class="btn btn-premium">Cari Tiket Sekarang</a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('ExtraJS')
<script type="text/javascript">
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.attr("data-id"), 
                quantity: ele.val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    $(".btn-qty-minus").click(function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var input = $(".qty-input-" + id);
        var currentVal = parseInt(input.val());
        if(currentVal > 1) {
            input.val(currentVal - 1);
            input.trigger("change");
        }
    });

    $(".btn-qty-plus").click(function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var input = $(".qty-input-" + id);
        var currentVal = parseInt(input.val());
        input.val(currentVal + 1);
        input.trigger("change");
    });

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        
        swalPremium.fire({
            title: 'Hapus Tiket?',
            text: "Yakin ingin menghapus tiket ini dari keranjang?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: ele.attr("data-id")
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
    });

</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
