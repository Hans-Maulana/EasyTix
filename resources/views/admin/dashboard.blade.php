@extends('layouts.master')

@section('ExtraCSS')
<style>
    .welcome-card::after {
        content: '\f3ed';
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
                    <h2 class="fw-bold mb-2">Dashboard Admin 👋</h2>
                    <p class="fs-5 opacity-75 mb-4">Pantau dan kelola seluruh ekosistem EasyTix dari satu tempat secara real-time.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.manageEvents') }}" class="btn btn-dark btn-round px-4 py-2 fw-bold">
                            Kelola Event <i class="fas fa-calendar-check ms-2"></i>
                        </a>
                        <a href="{{ route('admin.manageUsers') }}" class="btn btn-white btn-round px-4 py-2 fw-bold border shadow-sm">
                            Manajemen User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row">
            <div class="col-md-3 fade-in-up" style="animation-delay: 0.2s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-primary me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Total Users</p>
                                <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 70%"></div>
                        </div>
                        <small class="text-muted">User terdaftar aktif</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 fade-in-up" style="animation-delay: 0.3s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-info me-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Event Aktif</p>
                                <h3 class="fw-bold mb-0">{{ $totalEvents }}</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 55%"></div>
                        </div>
                        <small class="text-muted">Event siap tayang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 fade-in-up" style="animation-delay: 0.4s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-success me-3">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Tiket Terjual</p>
                                <h3 class="fw-bold mb-0">8,421</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
                        </div>
                        <small class="text-muted">Total transaksi sukses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 fade-in-up" style="animation-delay: 0.5s;">
                <div class="card stat-card-premium">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper-premium bg-soft-secondary me-3">
                                <i class="far fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small fw-bold text-uppercase">Pending Request</p>
                                <h3 class="fw-bold mb-0">{{ $totalPendingRequests }}</h3>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 40%"></div>
                        </div>
                        <small class="text-muted">Menunggu verifikasi</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Requests Table -->
        <div class="row pt-4">
            <div class="col-md-12 fade-in-up" style="animation-delay: 0.6s;">
                <div class="card shadow-none">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4">
                        <h4 class="fw-bold mb-0">Permintaan Akses Event</h4>
                        <button class="btn btn-icon btn-link btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-premium">
                                <thead>
                                    <tr>
                                        <th>Nama Event</th>
                                        <th>Organizer</th>
                                        <th>Tanggal Request</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $request->event->name }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-light text-muted small">{{ substr($request->user->name, 0, 1) }}</span>
                                                </div>
                                                <span>{{ $request->user->name }}</span>
                                            </div>
                                        </td>
                                        <td><i class="far fa-calendar-alt text-muted me-1"></i> {{ $request->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge badge-pill bg-warning text-dark">PENDING</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge badge-pill bg-success text-white">APPROVED</span>
                                            @else
                                                <span class="badge badge-pill bg-danger text-white">REJECTED</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($request->status == 'pending')
                                            <div class="d-flex gap-2 justify-content-end">
                                                <form action="{{ route('admin.approveRequest', $request->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-round btn-sm px-3 shadow-sm border-0">Setujui</button>
                                                </form>
                                                <form action="{{ route('admin.rejectRequest', $request->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-light btn-round btn-sm px-3 shadow-sm border">Tolak</button>
                                                </form>
                                            </div>
                                            @else
                                                <span class="text-muted small">Processed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($requests->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <img src="https://img.icons8.com/bubbles/200/search.png" alt="Empty" style="width: 100px;">
                                            <p class="text-muted mt-2">Tidak ada permintaan akses saat ini.</p>
                                        </td>
                                    </tr>
                                    @endif
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
