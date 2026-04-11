@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .wl-body { font-family: 'Outfit', sans-serif; min-height: 80vh; padding-bottom: 50px; }
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .gl-card:hover { transform: translateY(-5px); border-color: rgba(255, 255, 255, 0.2); }
    
    .status-badge {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending { background: rgba(243, 156, 18, 0.1); color: #f39c12; border: 1px solid rgba(243, 156, 18, 0.3); }
    .status-approved { background: rgba(46, 204, 113, 0.1); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3); }
    .status-purchased { background: rgba(52, 152, 219, 0.1); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.3); }
    .status-cancelled { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }

    .priority-circle {
        width: 45px; height: 45px;
        background: var(--premium-gold-grad);
        color: #000;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem;
        box-shadow: 0 5px 15px rgba(244, 208, 63, 0.3);
    }

    .btn-action {
        border-radius: 50px; padding: 10px 25px; font-weight: 700; font-size: 0.85rem; transition: 0.3s;
    }
    .btn-checkout { background: var(--premium-gold-grad); color: #000; border: none; }
    .btn-checkout:hover { transform: scale(1.05); color: #000; box-shadow: 0 5px 15px rgba(244, 208, 63, 0.5); }
</style>
@endsection

@section('content')
<div class="wl-body pt-5">
    <div class="container">
        <div class="d-flex align-items-center mb-5">
            <div class="bg-warning rounded-pill p-3 me-3 d-flex align-items-center justify-content-center shadow-lg" style="width: 60px; height: 60px; background: var(--premium-gold-grad) !important;">
                <i class="fas fa-clock text-dark fs-3"></i>
            </div>
            <div>
                <h2 class="fw-bold text-white mb-0">Antrean Waiting List</h2>
                <p class="text-muted mb-0">Pantau status prioritas dan selesaikan pembelian tiket Anda.</p>
            </div>
        </div>

        @if($waitingLists->isEmpty())
            <div class="glass-card p-5 text-center mt-4">
                <div class="mb-4">
                    <i class="fas fa-folder-open fs-1 text-muted opacity-25"></i>
                </div>
                <h4 class="text-white fw-bold">Belum Ada Antrean</h4>
                <p class="text-muted">Anda belum memiliki permintaan waiting list aktif saat ini.</p>
                <a href="{{ route('user.buyTickets') }}" class="btn btn-warning rounded-pill px-4 fw-bold mt-3">Cari Event Seru</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($waitingLists as $wl)
                    <div class="col-12">
                        <div class="glass-card p-4 d-flex align-items-center justify-content-between flex-wrap gap-4">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="priority-circle me-4" title="Prioritas Antrean #{{ $wl->priority }}">
                                    @if($wl->status == 'pending')
                                        @php
                                            $position = \App\Models\WaitingList::where('ticket_id', $wl->ticket_id)
                                                ->where('status', 'pending')
                                                ->where('priority', '<', $wl->priority)
                                                ->count() + 1;
                                        @endphp
                                        {{ $position }}
                                    @else
                                        <i class="fas fa-check"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-white fw-bold mb-1">{{ $wl->ticket->event_schedule->event->name }}</h5>
                                    <div class="d-flex align-items-center flex-wrap gap-3">
                                        <span class="text-muted small"><i class="fas fa-ticket-alt me-1"></i> {{ $wl->ticket->ticket_type->name }}</span>
                                        <span class="text-muted small"><i class="fas fa-shopping-basket me-1"></i> {{ $wl->quantity }} Tiket</span>
                                        <span class="text-muted small"><i class="fas fa-calendar-day me-1"></i> {{ \Carbon\Carbon::parse($wl->ticket->event_schedule->event_date)->format('d M Y') }}</span>
                                    </div>
                                    @if($wl->status == 'pending')
                                        <div class="mt-2 text-warning small">
                                            <i class="fas fa-info-circle me-1"></i> Anda berada di urutan ke-{{ $position }} untuk tiket ini.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <span class="status-badge status-{{ $wl->status }}">
                                    {{ $wl->status == 'pending' ? 'Menunggu' : ($wl->status == 'approved' ? 'Siap Bayar' : ($wl->status == 'purchased' ? 'Selesai' : 'Batal')) }}
                                </span>

                                @if($wl->status == 'approved')
                                    <a href="{{ route('user.eventTickets', $wl->ticket->event_schedule->event->id) }}" class="btn btn-action btn-checkout">
                                        BELI SEKARANG <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                @endif

                                @if(in_array($wl->status, ['pending', 'approved']))
                                    <button onclick="confirmCancelWL('{{ $wl->id }}')" class="btn btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Hidden Form for Cancellation --}}
<form id="cancelWLForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('ExtraJS')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmCancelWL(id) {
        const form = document.getElementById('cancelWLForm');
        form.action = `/user/waiting-list/${id}/cancel`;
        
        Swal.fire({
            title: 'Batalkan Antrean?',
            text: 'Tindakan ini tidak dapat dibatalkan dan posisi Anda akan diberikan ke orang lain.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#e74c3c',
            background: '#071120',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection
