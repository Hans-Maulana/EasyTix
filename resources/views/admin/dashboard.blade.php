@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Judul Header Halaman -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Admin</h3>
                <h6 class="op-7 mb-2">Pantau dan Kelola Sistem EasyTix dengan Mudah</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Lihat Laporan</a>
            </div>
        </div>

        <!-- Bagian Card Info Cepat -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Users</p>
                                    <h4 class="card-title">{{ $totalUsers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Event Aktif</p>
                                    <h4 class="card-title">{{ $totalEvents }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Tiket Terjual</p>
                                    <h4 class="card-title">8,421</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="far fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Menunggu Verifikasi</p>
                                    <h4 class="card-title">{{ $totalPendingRequests }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Event Terbaru yang Perlu Dipantau -->
        <div class="row pt-4">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Permintaan Akses Event</h4>
                            <div class="card-tools">
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                            </div>
                        </div>
                        <p class="card-category">Daftar organizer yang meminta akses untuk mengelola event.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-bg-primary mt-4 align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Event</th>
                                        <th scope="col">Organizer</th>
                                        <th scope="col">Tanggal Request</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->event->name }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($request->status == 'pending')
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.approveRequest', $request->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                                </form>
                                                <form action="{{ route('admin.rejectRequest', $request->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                                </form>
                                            </div>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>-</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($requests->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada permintaan akses.</td>
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
