@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Jadwal: {{ $event->name }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.manageEvents') }}">Manajemen Event</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Jadwal Event</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary-gradient text-white">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title text-white">List Sesi/Jadwal untuk <strong>{{ $event->name }}</strong></h4>
                            <a href="{{ route('admin.editEvent', $event->id) }}" class="btn btn-warning btn-round ms-auto">
                                <i class="fa fa-edit"></i>
                                Kelola Jadwal & Tiket
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="schedules-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Jadwal</th>
                                        <th>Tanggal</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event->event_schedule as $schedule)
                                        <tr>
                                            <td><span class="badge badge-info fs-6">{{ $schedule->id }}</span></td>
                                            <td>{{ $schedule->event_date }}</td>
                                            <td>{{ $schedule->start_time }}</td>
                                            <td>{{ $schedule->end_time }}</td>
                                            <td style="max-width: 300px;">
                                                <p class="mb-0 text-dark small" style="line-height: 1.4;">{{ $schedule->description ?? '-' }}</p>
                                            </td>
                                            <td>
                                                @if($schedule->status == 'completed')
                                                    <span class="badge badge-success fs-6">Completed</span>
                                                @elseif($schedule->status == 'cancelled')
                                                    <span class="badge badge-danger fs-6">Cancelled</span>
                                                @elseif($schedule->status == 'ongoing')
                                                    <span class="badge badge-warning fs-6">Ongoing</span>
                                                @else
                                                    <span class="badge badge-info fs-6">{{ $schedule->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dedicated Ticket Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary-gradient text-white">
                        <h4 class="card-title mb-0 text-white"><i class="fa fa-ticket-alt me-2"></i>Rincian Tiket Berdasarkan Sesi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($event->event_schedule as $schedule)
                                <div class="row">
                                    <div class="p-3 border rounded shadow-sm h-100" style="border-top: 4px solid #1572e8 !important; background: rgba(255,255,255,0.02);">
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                            <h6 class="fw-bold mb-0 text-primary">
                                                <i class="fa fa-calendar-day me-2"></i>Sesi {{ $schedule->id }}
                                            </h6>
                                            <span class="badge badge-dark" style="font-size: 10px;">{{ $schedule->event_date }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @forelse($schedule->tickets as $ticket)
                                                <div class="d-flex align-items-center p-2 border rounded" style="min-width: 140px; flex: 1; background: rgba(255,255,255,0.05);">
                                                    <div class="p-2 bg-info rounded me-2 text-white" style="font-size: 14px;">
                                                        <i class="fa fa-tag"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-white" style="font-size: 0.8rem; line-height: 1.2;">{{ $ticket->ticket_type->name }}</div>
                                                        <div class="text-info fw-bold" style="font-size: 0.85rem;">Rp {{ number_format($ticket->price, 0, ',', '.') }}</div>
                                                        <div class="text-light opacity-75" style="font-size: 11px;">Stok: {{ $ticket->capacity }}</div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="w-100 text-center py-3 text-muted italic small">
                                                    Tidak ada tiket untuk sesi ini.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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