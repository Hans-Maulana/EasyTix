@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Waiting List - {{ $schedule->event->name }}</h3>
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
                    <a href="#">Waiting List: {{ $schedule->id }}</a>
                </li>
            </ul>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Permintaan Waiting List</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover datatable-waiting-list">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pemesan</th>
                                        <th>Tipe Tiket</th>
                                        <th>Jumlah Diminta</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($waitingLists as $wl)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $wl->user->name }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $wl->ticket->ticket_type->name ?? '-' }}</span>
                                        </td>
                                        <td>{{ $wl->quantity }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($wl->status) {
                                                    'pending' => 'badge-warning',
                                                    'requested' => 'badge-info',
                                                    'approved' => 'badge-success',
                                                    'rejected' => 'badge-danger',
                                                    default => 'badge-dark'
                                                };
                                                $statusText = match($wl->status) {
                                                    'pending' => 'Menunggu Aksi',
                                                    'requested' => 'Menunggu Admin',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    default => ucfirst($wl->status)
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                             @if($wl->status === 'pending')
                                             <form action="{{ route('organizer.requestWaitingListAdmin', $wl->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                 @csrf
                                                 <input type="number" name="quantity" value="{{ $wl->quantity }}" min="1" class="form-control form-control-sm" style="width: 80px;">
                                                 <button type="submit" class="btn btn-sm btn-success" data-confirm="Minta admin untuk menyetujui penambahan kuota waiting list ini?">
                                                     <i class="fas fa-paper-plane"></i> Request ke Admin
                                                 </button>
                                             </form>
                                             @elseif($wl->status === 'requested')
                                             <span class="text-muted small">Diajukan: <strong>{{ $wl->quantity }}</strong></span>
                                             @elseif($wl->status === 'approved')
                                             <span class="text-success small">Telah Dibuka: <strong>{{ $wl->quantity }}</strong></span>
                                             @else
                                             <span class="text-danger small">Ditolak</span>
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
    </div>
</div>
@endsection

@section('ExtraJS')
    <script>
        $(document).ready(function() {
            $('.datatable-waiting-list').DataTable({
                "pageLength": 10,
            });
        });
    </script>
@endsection
