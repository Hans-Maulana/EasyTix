@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .buy-tickets-body { font-family: 'Outfit', sans-serif; } /* Removed background: #fdfdfd */

    .event-section-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 25px; }

    .event-grid-card {
        border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.05); height: 100%; display: block;
        text-decoration: none !important; color: inherit !important;
        backdrop-filter: blur(10px);
    }
    .event-grid-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        background: rgba(255, 255, 255, 0.08); border-color: rgba(255,255,255,0.2);
    }

    .event-grid-img-wrapper { width: 100%; height: 200px; overflow: hidden; }
    .event-grid-img-wrapper img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.5s ease;
    }
    .event-grid-card:hover img { transform: scale(1.05); }

    .event-grid-info { padding: 18px 20px; }
    .event-grid-name {
        font-weight: 800; font-size: 1rem; color: #fff;
        text-transform: uppercase; margin-bottom: 10px;
        line-height: 1.4; min-height: 2.8em;
    }
    .event-grid-meta { font-size: 0.82rem; color: #cbd5e1; margin-bottom: 5px; display: flex; align-items: center; gap: 6px; }
    .event-grid-meta i { color: #a0aec0; width: 14px; }

    .event-card-footer {
        padding: 14px 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex; justify-content: space-between; align-items: center;
    }
    .price-label { font-size: 0.72rem; color: #a0aec0; display: block; margin-bottom: 1px; }
    .price-value { font-weight: 800; font-size: 1rem; color: var(--premium-gold); }

    .btn-pesan {
        background: var(--premium-gold-grad); color: #000; border: none;
        font-weight: 700; font-size: 0.82rem; border-radius: 8px;
        padding: 8px 18px; transition: 0.2s; white-space: nowrap;
    }
    .btn-pesan:hover { transform: scale(1.04); box-shadow: 0 4px 15px rgba(244, 208, 63, 0.4); }
</style>
@endsection

@section('content')
<div class="container buy-tickets-body pt-5 pb-4">
    <div class="page-inner">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #cbd5e1; font-weight: 700;">Event</li>
            </ol>
        </nav>
        <h2 class="event-section-title">Event</h2>

        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-lg-4 col-md-6">
                <div class="event-grid-card">
                    <div class="event-grid-img-wrapper">
                        <img src="{{ $event->banner ? asset('storage/'.$event->banner) : asset('assets/img/easytix_login_bg.png') }}"
                             alt="{{ $event->name }}">
                    </div>
                    <div class="event-grid-info">
                        <h4 class="event-grid-name">{{ $event->name }}</h4>
                        <div class="event-grid-meta">
                            <i class="far fa-calendar-alt"></i>
                            {{ $event->date_display }}
                        </div>
                        <div class="event-grid-meta">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $event->location }}
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <div>
                            <span class="price-label">Mulai dari</span>
                            <span class="price-value">Rp {{ number_format($event->min_price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('user.eventTickets', $event->id) }}" class="btn-pesan">
                            Pesan Tiket &rarr;
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times text-muted mb-4" style="font-size: 5rem; opacity: 0.2;"></i>
                <h4 class="text-muted fw-bold">Belum ada event tersedia.</h4>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
