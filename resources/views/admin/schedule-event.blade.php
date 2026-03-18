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
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Sesi/Jadwal untuk <strong>{{ $event->name }}</strong></h4>
                            <a href="{{ route('admin.createSchedule', $event->id) }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Sesi Jadwal
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
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event->event_schedule as $schedule)
                                        <tr>
                                            <td>{{ $schedule->id }}</td>
                                            <td>{{ $schedule->event_date }}</td>
                                            <td>{{ $schedule->start_time }}</td>
                                            <td>{{ $schedule->end_time }}</td>
                                            <td>{{ $schedule->description ?? '-' }}</td>
                                            <td>
                                                @if($schedule->status == 'active')
                                                    <span class="badge badge-primary">Active</span>
                                                @elseif($schedule->status == 'nonactive')
                                                    <span class="badge badge-info">Non Active</span>
                                                @elseif($schedule->status == 'pending')
                                                    <span class="badge badge-secondary">Pending</span>
                                                @else
                                                    <span class="badge badge-dark">{{ $schedule->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('admin.editSchedule', ['event' => $event->id, 'schedule' => $schedule->id]) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Sesi">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.deleteSchedule', ['event' => $event->id, 'schedule' => $schedule->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi jadwal ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-danger" data-bs-toggle="tooltip" title="Hapus Sesi">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
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
    </div>
</div>
@endsection 

@section('ExtraJS')
    <script>
        $(document).ready(function() {
            $('#events-table').DataTable({
                "pageLength": 10,
            });
        });
    </script>
@endsection     