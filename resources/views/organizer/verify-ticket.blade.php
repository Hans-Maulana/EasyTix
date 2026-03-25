@extends('layouts.master')

@section('ExtraCSS')
<style>
    .verify-card-p {
        border-radius: 1.5rem !important;
        background: #fff;
        border: none !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
        margin-bottom: 25px;
        transition: all 0.3s;
    }
    .verify-card-p:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.06) !important;
    }
    .btn-action-p {
        border-radius: 50px !important;
        font-weight: 700 !important;
        padding-left: 2rem !important;
        padding-right: 2rem !important;
    }
</style>
@endsection

@section('content')
<div class="container pb-5">
    <div class="page-inner">
        <div class="page-header mb-5">
            <div>
                <h3 class="fw-bold display-6 mb-2">Pilih Event Verifikasi</h3>
                <p class="text-muted">Pilih acara yang ingin Anda lakukan pengecekan dan verifikasi tiket hari ini.</p>
            </div>
        </div>

        <div class="row">
            @forelse ($approvedRequests as $request)
                <div class="col-md-6 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="card verify-card-p p-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg me-4">
                                <span class="avatar-title rounded-circle bg-premium-blue text-white fw-bold shadow-sm">{{ substr($request->event->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="fw-bold text-dark mb-1">{{ $request->event->name }}</h4>
                                <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt me-1 text-warning"></i> {{ $request->event->location }}</p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('organizer.verifyTicketDetail', $request->event->id) }}" class="btn btn-dark btn-action-p shadow-sm">
                                    Buka Panel <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://img.icons8.com/bubbles/200/delete-shield.png" alt="No data" style="width: 150px;">
                    <h3 class="fw-bold mt-4">Belum Ada Event Terdaftar</h3>
                    <p class="text-muted fs-5">Lakukan request akses ke event terlebih dahulu.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
