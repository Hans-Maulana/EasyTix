@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Banner Utama (Carousel) -->
        @if($mainBanners->count() > 0)
        <div class="row mb-5">
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
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <h4 class="fw-bold"><i class="fas fa-fire text-danger me-2"></i> Penawaran Spesial</h4>
            </div>
            @foreach($cardBanners as $banner)
            <div class="col-md-4 mb-4">
                <div class="card card-post card-round shadow-sm h-100 border-0 overflow-hidden banner-card">
                    <img class="card-img-top" src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-3">
                        <h5 class="card-title fw-bold mb-3 text-truncate">{{ $banner->title }}</h5>
                        <a href="{{ $banner->link ?? '#' }}" class="btn btn-outline-primary btn-sm btn-round w-100">
                            Beli Tiket Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Fokus ke Banner Promo (Carousel & Card) -->
    </div>
</div>


<style>
    .banner-card { transition: transform 0.3s ease; }
    .banner-card:hover { transform: translateY(-10px); }
    .carousel-item img { max-height: 450px; }
</style>
@endsection
