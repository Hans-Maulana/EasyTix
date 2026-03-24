@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Verifikasi Tiket - {{ $schedule->event->name }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('organizer.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.dashboard') }}">Organizer</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.selectEventVerification') }}">Tabel Pilih Event</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.verifyTicketDetail', $schedule->event->id) }}">{{ $schedule->event->name }}</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Jadwal: {{ $schedule->id }}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="card-title">Scan / Input Kode Tiket ({{ $schedule->id }})</h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="ticket_code">Kode Tiket</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="ticket_code" name="ticket_code" placeholder="Masukkan ID Tiket (Contoh: TIX-2026-001)" required autofocus>
                                    <button class="btn btn-primary" type="submit">Cek Tiket</button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <button class="btn btn-secondary btn-border">
                                <i class="fas fa-camera me-2"></i> Buka Scanner Kamera
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Riwayat Verifikasi Hari Ini</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Kode Tiket</th>
                                        <th>Nama Pembeli</th>
                                        <th>Tipe Tiket</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="5">Belum ada aktivitas verifikasi hari ini.</td>
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
