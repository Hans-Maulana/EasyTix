@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Peserta - {{ $schedule->event->name }}</h3>
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
                    <a href="{{ route('organizer.myEvents') }}">Event Saya</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.myEventsDetail', $schedule->event->id) }}">{{ $schedule->event->name }}</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Daftar Peserta: {{ $schedule->id }}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Peserta yang Terdaftar - {{ $schedule->id }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="attendees-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Peserta</th>
                                        <th>Tipe Tiket</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendees as $attendee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendee->owner_name }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $attendee->ticket->ticket_type->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($attendee->status) {
                                                    'valid' => 'badge-success',
                                                    'invalid' => 'badge-danger',
                                                    'used' => 'badge-info',
                                                    'expired' => 'badge-warning',
                                                    'cancelled' => 'badge-secondary',
                                                    default => 'badge-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($attendee->status) }}</span>
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
            $('#attendees-table').DataTable({
                "pageLength": 10,
            });
        });
    </script>
@endsection
