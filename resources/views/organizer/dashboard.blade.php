@extends('layouts.master')

@section('ExtraCSS')
<style>
    .welcome-card::after {
        content: '\f3ff';
        font-family: 'Font Awesome 5 Solid';
        position: absolute;
        right: -30px;
        bottom: -30px;
        font-size: 15rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Welcome Hero -->
        <div class="welcome-card fade-in-up" style="animation-delay: 0.1s;">
            <div class="row align-items-center">
                <div class="col-md-8 position-relative z-index-1">
                    <h2 class="fw-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="fs-5 opacity-75 mb-4">Senang melihat Anda kembali. Semua event dan penjualan tiket Anda terpantau aman hari ini.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('organizer.events') }}" class="btn btn-dark btn-round px-4 py-2 fw-bold">
                            Tamba Event Baru <i class="fas fa-plus ms-2"></i>
                        </a>
                        <a href="{{ route('organizer.salesReport') }}" class="btn btn-white btn-round px-4 py-2 fw-bold border shadow-sm">
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row">
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.2s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-primary me-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Event Aktif</p>
                                <h3 class="fw-bold mb-0">{{ $totalMyEvents }}</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                        </div>
                        <small class="text-muted">Meningkat 12% dari bulan lalu</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.3s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-success me-3">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Tiket Valid</p>
                                <h3 class="fw-bold mb-0">{{ number_format($totalTicketsValid, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 60%"></div>
                        </div>
                        <small class="text-muted">Siap untuk diverifikasi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.4s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-warning me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Estimasi Revenue</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                        </div>
                        <small class="text-muted">Target tercapai 85%</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Events Table -->
        <div class="row pt-4">
            <div class="col-md-12 fade-in-up" style="animation-delay: 0.5s;">
                <div class="card shadow-none">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4">
                        <h4 class="fw-bold mb-0">Event Yang Anda Kelola</h4>
                        <a href="{{ route('organizer.myEvents') }}" class="text-primary fw-bold text-decoration-none">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-premium">
                                <thead>
                                    <tr>
                                        <th>Nama Event</th>
                                        <th>Lokasi</th>
                                        <th>Tipe Event</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($approvedRequests as $req)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <span class="avatar-title rounded-circle bg-light text-dark fw-bold">{{ substr($req->event->name, 0, 1) }}</span>
                                                </div>
                                                <span class="fw-bold text-dark">{{ $req->event->name }}</span>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $req->event->location }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark border me-1">{{ $req->event->category->name ?? 'Uncategorized' }}</span>
                                            @php
                                                $allGenres = collect();
                                                foreach($req->event->performers as $p) {
                                                    $allGenres = $allGenres->merge($p->genres);
                                                }
                                            @endphp
                                            @foreach($allGenres->unique('id')->take(2) as $genre)
                                                <span class="badge bg-light text-dark border me-1">{{ $genre->name }}</span>
                                            @endforeach
                                        </td>
                                        <td><span class="badge badge-pill bg-success text-white">ACTIVE</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('organizer.myEventsDetail', $req->event->id) }}" class="btn btn-light btn-round border shadow-sm btn-sm px-3">
                                                Manage <i class="fas fa-cog ms-1 text-muted"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <img src="https://img.icons8.com/bubbles/200/calendar.png" alt="Empty" style="width: 150px;">
                                            <p class="text-muted mt-3">Belum ada event. Mulai kolaborasi sekarang!</p>
                                            <a href="{{ route('organizer.events') }}" class="btn btn-primary btn-round">Request Event</a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
