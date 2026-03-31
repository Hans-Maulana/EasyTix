@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .cart-wrapper { font-family: 'Outfit', sans-serif; }
    .cart-item {
        border-radius: 20px; overflow: hidden; background: #fff;
        border: 1px solid rgba(0,0,0,0.05); margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .btn-premium {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000; border: none; font-weight: 700; border-radius: 50px;
        padding: 15px 40px; transition: all 0.3s ease;
    }
    .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(244, 208, 63, 0.3); }
</style>
@endsection

@section('content')
<div class="container cart-wrapper pt-5 pb-4">
    <div class="page-inner">
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down" data-aos-delay="100">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #000; font-weight: 700;">Keranjang</li>
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
                                <h6 class="fw-bold text-dark">Rp {{ number_format($details['price'], 0, ',', '.') }}</h6>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart rounded-pill" data-id="{{ $id }}" min="1">
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-outline-danger btn-sm rounded-circle remove-from-cart" data-id="{{ $id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4" data-aos="fade-left">
                <div class="card border-0 rounded-4 p-4 shadow-sm">
                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Items</span>
                        <span class="fw-bold text-dark">{{ count(session('cart')) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-4 border-bottom">
                        <span class="fs-5">Subtotal</span>
                        <span class="fs-5 fw-bold text-warning">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('user.checkout') }}" class="btn btn-premium w-100 mb-3">
                        Checkout Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('user.buyTickets') }}" class="btn btn-outline-dark w-100 rounded-pill py-3 fw-bold">
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

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Yakin ingin menghapus tiket ini?")) {
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

</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
