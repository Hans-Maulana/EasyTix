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
                                    <h4 class="card-title">5</h4>
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
                            <h4 class="card-title">Event Terbaru Menunggu Verifikasi</h4>
                            <div class="card-tools">
                                <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-angle-down"></span></button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-times"></span></button>
                            </div>
                        </div>
                        <p class="card-category">Daftar event yang diajukan oleh organizer yang membutuhkan persetujuan.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-bg-primary mt-4 align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Event</th>
                                        <th scope="col">Organizer</th>
                                        <th scope="col">Tanggal Event</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Konser Coldplay Jakarta</td>
                                        <td>PK Entertainment</td>
                                        <td>15 Nov 2026</td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Lihat Detail</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Festival Musik Indie Raya</td>
                                        <td>Ruang Gembira</td>
                                        <td>20 Okt 2026</td>
                                        <td><span class="badge badge-success">Approved</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Lihat Detail</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Pentas Teater Bawang Merah</td>
                                        <td>Teater Koma</td>
                                        <td>02 Sep 2026</td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Lihat Detail</a>
                                        </td>
                                    </tr>
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
