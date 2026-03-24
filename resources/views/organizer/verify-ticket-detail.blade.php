@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Jadwal - {{ $event->name }}</h3>
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
                    <a href="#">{{ $event->name }}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Pilih Jadwal untuk Verifikasi</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="schedules-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Jadwal</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event->event_schedule as $schedule)
                                        <tr>
                                            <td>{{ $schedule->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->event_date)->format('d M Y') }}</td>
                                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                            <td>
                                                @if($schedule->status == 'scheduled')
                                                    <span class="badge badge-primary">Scheduled</span>
                                                @elseif($schedule->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $schedule->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('organizer.verifySchedule', $schedule->id) }}" class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-check-circle me-1"></i> Verifikasi Tiket
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($event->event_schedule->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada jadwal untuk event ini.</td>
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

@section('ExtraJS')
    <script>
        $(document).ready(function() {
            $('#schedules-table').DataTable({
                "pageLength": 10,
            });
        });
    </script>
@endsection
