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
                    <div class="alert alert-success border-0 shadow-sm mb-4" style="background: rgba(40, 167, 69, 0.2); color: #fff; border: 1px solid rgba(40, 167, 69, 0.4) !important; backdrop-filter: blur(10px); border-radius: 12px;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="background: rgba(220, 53, 69, 0.2); color: #fff; border: 1px solid rgba(220, 53, 69, 0.4) !important; backdrop-filter: blur(10px); border-radius: 12px;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
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
                                        <th>Ranking</th>
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
                                        <td>
                                            <span class="badge" style="background: linear-gradient(135deg, #F4D03F, #D4AC0D); color: #000; font-weight: 800;">
                                                #{{ $wl->priority }}
                                            </span>
                                        </td>
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
                                                    'purchased' => 'badge-primary',
                                                    'cancelled' => 'badge-danger',
                                                    default => 'badge-dark'
                                                };
                                                $statusText = match($wl->status) {
                                                    'pending' => 'Menunggu Aksi',
                                                    'requested' => 'Menunggu Admin',
                                                    'approved' => 'Slot Tersedia',
                                                    'purchased' => 'Sudah Beli',
                                                    'cancelled' => 'Dibatalkan',
                                                    default => ucfirst($wl->status)
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                              @if($wl->status === 'pending')
                                             <form action="{{ route('organizer.requestWaitingListAdmin', $wl->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                 @csrf
                                                 <input type="number" name="quantity" value="{{ $wl->quantity }}" min="1" class="form-control form-control-sm text-center" style="width: 70px; background: rgba(255,255,255,0.1); border: 1px solid var(--premium-gold);">
                                                 <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                     <i class="fas fa-paper-plane me-1"></i> Ajukan
                                                 </button>
                                             </form>
                                             @elseif($wl->status === 'requested')
                                             <span class="badge bg-soft-info px-3 py-2 rounded-pill shadow-sm">
                                                 <i class="fas fa-hourglass-half me-1"></i> Diajukan ke Admin: {{ $wl->requested_quantity }}
                                             </span>
                                             @elseif($wl->status === 'approved')
                                             <span class="badge bg-soft-success px-3 py-2 rounded-pill shadow-sm">
                                                 <i class="fas fa-check-circle me-1"></i> Dibuka Admin: {{ $wl->requested_quantity ?? $wl->quantity }}
                                             </span>
                                             @else
                                             <span class="badge bg-soft-danger px-3 py-2 rounded-pill">Ditolak</span>
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
