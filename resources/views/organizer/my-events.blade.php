@extends('layouts.master')

@section('ExtraCSS')
<style>
    .event-card-premium {
        border-radius: 2rem !important;
        overflow: hidden !important;
        background: rgba(20, 46, 94, 0.4) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        height: 100%;
    }
    .event-card-premium:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.1) !important;
    }
    .event-img-header {
        height: 200px;
        background: var(--premium-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: rgba(255,255,255,0.1);
        position: relative;
    }
    .status-badge-p {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--premium-gold-grad);
        color: #000;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.75rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .event-content-p {
        padding: 2rem;
    }
    .event-title-p {
        font-weight: 800;
        font-size: 1.4rem;
        margin-bottom: 0.8rem;
        color: #0d1b2a;
    }
    .event-meta-p {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 0.5rem;
    }
    .btn-manage-p {
        width: 100%;
        border-radius: 1.2rem !important;
        padding: 0.8rem !important;
        font-weight: 700 !important;
        margin-top: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container pb-5">
    <div class="page-inner">
        <div class="page-header mb-5">
            <div>
                <h3 class="fw-bold display-6 mb-2">Semua event</h3>
                <p class="text-muted">Koleksi acara yang sedang Anda kelola melalui EasyTix.</p>
            </div>
            <div class="ms-md-auto">
                <a href="{{ route('organizer.events') }}" class="btn btn-primary btn-round px-4 py-2 shadow-sm">
                    <i class="fas fa-plus me-2"></i> Request Akses Baru
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse ($approvedRequests as $request)
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="card event-card-premium">
                        <div class="event-img-header">
                            @if ($request->event->banner)
                                <img src="{{ asset('storage/' . $request->event->banner) }}"
                                     alt="{{ $request->event->name }}"
                                     style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;">
                            @else
                                <img src="{{ asset('assets/img/easytix_login_bg.png') }}"
                                     alt="{{ $request->event->name }}"
                                     style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0; filter: brightness(0.7);">
                            @endif
                            <span class="status-badge-p">APPROVED</span>
                        </div>
                        <div class="event-content-p">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-warning text-dark border px-3 py-2 rounded-pill small fw-bold">{{ $request->event->category->name ?? 'Uncategorized' }}</span>
                                @php
                                    $allGenres = collect();
                                    foreach($request->event->performers as $p) {
                                        $allGenres = $allGenres->merge($p->genres);
                                    }
                                    $uniqueGenres = $allGenres->unique('id')->take(2);
                                @endphp
                                @foreach($uniqueGenres as $genre)
                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill small">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            <h4 class="event-title-p">{{ $request->event->name }}</h4>
                            <div class="event-meta-p">
                                <i class="fas fa-map-marker-alt me-2 text-warning"></i> {{ $request->event->location }}
                            </div>
                            <div class="event-meta-p">
                                <i class="fas fa-id-badge me-2 text-primary"></i> ID: #{{ $request->event->id }}
                            </div>
                            <div class="event-meta-p">
                                <i class="fas fa-calendar-alt me-2 text-success"></i> Multi-schedules Ready
                            </div>
                            
                            <a href="{{ route('organizer.myEventsDetail', $request->event->id) }}" class="btn btn-dark btn-manage-p shadow-sm">
                                Manage Event <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 fade-in-up">
                    <div class="card border-0 bg-transparent">
                        <div class="card-body">
                            <img src="https://img.icons8.com/bubbles/200/empty-box.png" alt="No data" style="width: 200px;">
                            <h3 class="fw-bold mt-4">Belum Ada Event Aktif</h3>
                            <p class="text-muted fs-5 mb-4">Anda belum memiliki akses ke event manapun saat ini.</p>
                            <a href="{{ route('organizer.events') }}" class="btn btn-primary btn-round px-5 py-3 fw-bold shadow-lg">Cari Event Sekarang</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
