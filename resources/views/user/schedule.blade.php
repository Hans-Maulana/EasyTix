@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    /* Premium Styling */
    .schedule-wrapper {
        font-family: 'Outfit', sans-serif;
    }
    
    .gold-gradient {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
    }
    .text-gold { color: #F4D03F !important; }
    
    .btn-premium {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000;
        border: none;
        box-shadow: 0 8px 20px rgba(244, 208, 63, 0.3);
        font-weight: 600;
        border-radius: 50px;
        padding: 10px 25px;
        transition: all 0.3s ease;
    }
    .btn-premium:hover {
        transform: translateY(-3px) scale(1.02);
        color: #000;
        box-shadow: 0 15px 30px rgba(244, 208, 63, 0.4);
    }
    
    .concert-card {
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.4s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .concert-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border-color: #F4D03F;
    }
    .concert-img-wrapper {
        position: relative;
        height: 250px;
        overflow: hidden;
    }
    .concert-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .concert-card:hover .concert-img-wrapper img {
        transform: scale(1.1);
    }
    .date-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(7, 17, 32, 0.9);
        backdrop-filter: blur(10px);
        padding: 10px 15px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        font-weight: 700;
        line-height: 1.2;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        z-index: 2;
    }
    .date-badge span { display: block; font-size: 0.8rem; color: #F4D03F; }

    .header-section {
        background: #071120;
        border-radius: 24px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .header-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(244, 208, 63, 0.1) 0%, transparent 100%);
        z-index: 0;
    }
    .header-content { position: relative; z-index: 1; }
    
</style>
@endsection

@section('content')
<div class="container schedule-wrapper py-4">
    <div class="page-inner">
        <!-- Page Header -->
        <div class="header-section text-center text-white" data-aos="fade-down">
            <div class="header-content">
                <h1 class="fw-bold mb-3">Jadwal <span style="background: linear-gradient(135deg, #F4D03F, #E67E22); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Konser & Event</span></h1>
                <p class="text-light opacity-75 fs-5 mb-0 mx-auto" style="max-width: 600px;">Jelajahi berbagai event spektakuler yang akan datang dan amankan tiketmu sebelum kehabisan!</p>
            </div>
        </div>

        <!-- Filter / Search Section -->
        <div class="row align-items-center mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-6 mb-3 mb-md-0">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-list text-warning me-2"></i> 
                    @if(request('search'))
                        Hasil Pencarian: "{{ request('search') }}"
                    @else
                        Semua Event Tersedia
                    @endif
                </h4>
            </div>
            <div class="col-md-6 text-md-end">
                <form action="{{ route('user.schedule') }}" method="GET">
                    <div class="input-group" style="max-width: 300px; margin-left: auto;">
                        <input type="text" name="search" class="form-control border-secondary" placeholder="Cari nama atau lokasi event..." style="border-radius: 20px 0 0 20px;" value="{{ request('search') }}">
                        <button class="btn btn-dark" type="submit" style="border-radius: 0 20px 20px 0;"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="row">
            @forelse($events as $event)
                @php
                    // Try to get dynamic schedule data if available
                    $eventDate = $event->event_schedule && $event->event_schedule->isNotEmpty() ? $event->event_schedule->first()->date : null;
                    if($eventDate) {
                        try {
                            $parsedDate = \Carbon\Carbon::parse($eventDate);
                            $month = $parsedDate->translatedFormat('M');
                            $day = $parsedDate->format('d');
                            $fullDateText = $parsedDate->translatedFormat('d F Y, H:i');
                        } catch(\Exception $e) {
                            $month = "TBA";
                            $day = "--";
                            $fullDateText = $eventDate;
                        }
                    } else {
                        $month = "TBA";
                        $day = "--";
                        $fullDateText = "Tanggal & Waktu Belum Ditentukan";
                    }

                    // Check if an existing banner matches this event's name
                    $matchingBanner = \App\Models\Banner::where('title', 'LIKE', '%' . $event->name . '%')
                                        ->whereNotNull('image')
                                        ->first();

                    if ($matchingBanner) {
                        $imgSrc = asset('storage/' . $matchingBanner->image);
                    } else {
                        // Uniform placeholder image since Event model doesn't have one
                        $imgSrc = asset('assets/img/easytix_login_bg.png');
                    }
                @endphp
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="concert-card">
                        <div class="concert-img-wrapper">
                            <div class="date-badge">
                                <span class="text-uppercase">{{ $month }}</span>
                                {{ $day }}
                            </div>
                            <!-- Fast selling badge occasionally -->
                            @if($loop->iteration % 3 == 0)
                            <span class="badge bg-danger position-absolute" style="top: 20px; right: 20px; z-index:2; border-radius: 20px; padding: 6px 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"><i class="fas fa-bolt me-1"></i> Hampir Habis</span>
                            @endif
                            <img src="{{ $imgSrc }}" alt="{{ $event->name }}">
                        </div>
                        <div class="p-4 flex-grow-1 d-flex flex-column">
                            <h4 class="fw-bold mb-2 text-dark">{{ $event->name }}</h4>
                            <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-warning me-2"></i> {{ $event->location ?? 'Lokasi Event' }}</p>
                            <p class="text-muted small mb-4"><i class="far fa-clock text-warning me-2"></i> {{ $fullDateText }}</p>
                            
                            <div class="mt-auto pt-3 border-top border-light d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">Mulai dari</small>
                                    <span class="fw-bold text-dark fs-5">Rp {{ number_format(150000 + ($loop->index % 4 * 50000), 0, ',', '.') }}</span>
                                </div>
                                <a href="#" class="btn btn-premium btn-sm px-4 py-2"><i class="fas fa-shopping-cart me-2"></i>Beli</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5" data-aos="fade-in">
                    <div class="mb-4 text-muted" style="font-size: 5rem;">
                        <i class="far fa-calendar-times"></i>
                    </div>
                    <h3 class="text-muted fw-bold">Belum Ada Jadwal Tersedia</h3>
                    <p class="text-muted">Pantau terus EasyTix untuk konser-konser seru selanjutnya!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
    });
</script>
@endsection
