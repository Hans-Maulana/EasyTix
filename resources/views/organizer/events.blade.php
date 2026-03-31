@extends('layouts.master')

@section('ExtraCSS')
<style>
    .request-card-premium {
        border-radius: 2rem !important;
        background: #fff;
        border: 1px solid #eee !important;
        margin-bottom: 30px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .request-card-premium:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05) !important;
        border-color: var(--premium-gold) !important;
    }
    .request-icon-box {
        width: 60px;
        height: 60px;
        background: var(--premium-gold-grad);
        color: #000;
        border-radius: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .btn-request-p {
        border-radius: 50px !important;
        padding: 0.6rem 2rem !important;
        font-weight: 800 !important;
        box-shadow: 0 4px 15px rgba(244, 208, 63, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container pb-5">
    <div class="page-inner">
        <div class="page-header mb-5">
            <div>
                <h3 class="fw-bold display-6 mb-2">Jelajahi Event</h3>
                <p class="text-muted">Cari dan ajukan permintaan akses untuk mengelola event yang tersedia di platform.</p>
            </div>
        </div>

        <div class="row">
            @forelse($events as $event)
                <div class="col-md-6 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="card request-card-premium p-4">
                        <div class="d-flex align-items-center">
                            <div class="request-icon-box me-4 shadow-sm">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-warning text-dark border px-2 py-1 rounded-pill small fw-bold" style="font-size: 0.65rem;">{{ $event->category->name ?? 'Uncategorized' }}</span>
                                    @php
                                        $allGenres = collect();
                                        foreach($event->performers as $p) {
                                            $allGenres = $allGenres->merge($p->genres);
                                        }
                                        $uniqueGenres = $allGenres->unique('id')->take(3); // Limit to 3 badge displays
                                    @endphp
                                    @foreach($uniqueGenres as $genre)
                                        <span class="badge bg-light text-muted border px-2 py-1 rounded-pill small" style="font-size: 0.65rem;">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                <h4 class="fw-bold mb-1 text-dark">{{ $event->name }}</h4>
                                <div class="small text-muted mb-0">
                                    <i class="fas fa-map-marker-alt me-1 text-warning"></i> {{ $event->location }}
                                </div>
                            </div>
                            <div class="ms-auto">
                                @php
                                    $requestStatus = $myRequests[$event->id] ?? null;
                                @endphp
                                
                                @if($requestStatus === 'approved')
                                    <span class="badge bg-success px-3 py-2 rounded-pill" style="font-size: 0.85rem;">
                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif($requestStatus === 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill" style="font-size: 0.85rem;">
                                        <i class="fas fa-clock me-1"></i> Menunggu
                                    </span>
                                @elseif($requestStatus === 'rejected')
                                    <form action="{{ route('organizer.requestAccess', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-request-p">
                                            <i class="fas fa-redo me-1"></i> Request Ulang
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('organizer.requestAccess', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-request-p">
                                            Request Access
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://img.icons8.com/bubbles/200/broken-link.png" alt="No data" style="width: 150px;">
                    <h3 class="fw-bold mt-4">Tidak Ada Event Baru</h3>
                    <p class="text-muted fs-5">Semua event saat ini sudah Anda kelola atau belum ada event baru yang aktif.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
    <script>
        $(document).ready(function() {
            @if(session('success'))
                swal({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    }
                });
            @endif
            @if(session('error'))
                swal({
                    title: "Gagal!",
                    text: "{{ session('error') }}",
                    icon: "error",
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }
                    }
                });
            @endif
        });
    </script>
@endsection
