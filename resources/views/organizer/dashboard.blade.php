@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Judul Header Halaman -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard Organizer</h3>
                <h6 class="op-7 mb-2">Kelola Event Anda dengan Efisien di EasyTix</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('organizer.events') }}" class="btn btn-primary btn-round">Cari Event Baru</a>
            </div>
        </div>

        <!-- Bagian Card Info Cepat -->
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Event yang Dipegang</p>
                                    <h4 class="card-title">{{ $totalMyEvents }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
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
                                    <p class="card-category">Tiket Terverifikasi</p>
                                    <h4 class="card-title">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Penjualan</p>
                                    <h4 class="card-title">Rp 0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="row pt-4">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <h4 class="card-title">Event Terbaru Anda</h4>
                        </div>
                        <p class="card-category">Daftar event yang Anda kelola saat ini.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-bg-primary mt-4 align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Event</th>
                                        <th scope="col">Lokasi</th>
                                        <th scope="col">Status Request</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedRequests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->event->name }}</td>
                                        <td>{{ $request->event->location }}</td>
                                        <td><span class="badge badge-success">Selesai</span></td>
                                        <td>
                                            <a href="{{ route('organizer.myEventsDetail', $request->event->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($approvedRequests->isEmpty())
                                    <tr class="text-center">
                                        <td colspan="5">
                                            <div class="py-5">
                                                <i class="fas fa-calendar-times mb-3" style="font-size: 3rem; color: #ccc;"></i>
                                                <p class="text-muted">Anda belum memegang event apapun. Silakan <a href="{{ route('organizer.events') }}">request akses</a> ke event yang tersedia.</p>
                                            </div>
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
