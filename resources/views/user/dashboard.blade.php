@extends('layouts.master')

@section('ExtraCSS')
<style>
    .welcome-card::after {
        content: '\f005';
        font-family: 'Font Awesome 5 Solid';
        position: absolute;
        right: -30px;
        bottom: -30px;
        font-size: 15rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }
    
    .premium-promo-card {
        border-radius: 20px !important;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
    }
    .premium-promo-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }
    .promo-img-container {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .promo-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .premium-promo-card:hover .promo-img-container img {
        transform: scale(1.08);
    }
    .promo-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
    }
    
    .premium-btn {
        background: linear-gradient(135deg, #142E5E 0%, #071120 100%);
        color: #fff !important;
        border-radius: 50px;
        padding: 12px 20px;
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .premium-btn:hover {
        background: var(--premium-gold-grad);
        color: #000 !important;
        box-shadow: 0 8px 20px rgba(244, 208, 63, 0.4);
    }
    .premium-btn .transition-icon {
        transition: transform 0.3s ease;
    }
    .premium-btn:hover .transition-icon {
        transform: translateX(5px);
    }

    .carousel-item img { max-height: 450px; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Welcome Hero -->
        <div class="welcome-card fade-in-up" style="animation-delay: 0.1s;">
            <div class="row align-items-center">
                <div class="col-md-8 position-relative z-index-1">
                    <h2 class="fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                    <p class="fs-5 opacity-75 mb-4">Temukan event favoritmu dan nikmati pengalaman tak terlupakan bersama EasyTix.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('user.buyTickets') }}" class="btn btn-dark btn-round px-4 py-2 fw-bold">
                            Cari Tiket <i class="fas fa-search ms-2"></i>
                        </a>
                        <a href="{{ route('user.myTickets') }}" class="btn btn-white btn-round px-4 py-2 fw-bold border shadow-sm">
                            Tiket Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Utama (Carousel) -->
        @if($mainBanners->count() > 0)
        <div class="row mb-5 fade-in-up" style="animation-delay: 0.2s;">
            <div class="col-md-12">
                <div id="mainCarousel" class="carousel slide shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($mainBanners as $index => $banner)
                            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner bg-dark">
                        @foreach($mainBanners as $index => $banner)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="d-flex align-items-center justify-content-center" style="height: 450px; background: #000;">
                                <img src="{{ asset('storage/' . $banner->image) }}" class="d-block mw-100 mh-100" alt="{{ $banner->title }}" style="object-fit: contain;">
                            </div>
                            <div class="carousel-caption d-none d-md-block text-start p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.8)); left:0; right:0; bottom:0;">
                                <h2 class="fw-bold">{{ $banner->title }}</h2>
                                <a href="{{ $banner->link ?? '#' }}" class="btn btn-primary btn-lg btn-round mt-2">
                                    <i class="fas fa-ticket-alt me-2"></i> Beli Tiket Sekarang
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Banner Card (Promo) -->
        @if($cardBanners->count() > 0)
        <div class="row mb-5 mt-4">
            <div class="col-md-12 mb-4 d-flex align-items-center justify-content-between fade-in-up" style="animation-delay: 0.3s;">
                <h3 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                    <i class="fas fa-gem text-warning me-2"></i> Penawaran Spesial
                </h3>
            </div>
            @foreach($cardBanners as $banner)
            <div class="col-md-4 mb-4 fade-in-up" style="animation-delay: {{ 0.4 + ($loop->index * 0.1) }}s;">
                <div class="card premium-promo-card h-100 border-0">
                    <div class="promo-img-container">
                        <img class="card-img-top" src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                        <div class="promo-overlay">
                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase shadow-sm" style="letter-spacing: 1px;"><i class="fas fa-tag me-1"></i> Promo</span>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <h4 class="card-title fw-bold mb-3 text-dark" style="font-family: 'Outfit', sans-serif;">{{ $banner->title }}</h4>
                        <div class="mt-auto pt-3 text-center">
                            <a href="{{ $banner->link ?? '#' }}" class="btn premium-btn w-100 fw-bold">
                                Beli Tiketnya Sekarang <i class="fas fa-arrow-right ms-2 transition-icon"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection