@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .event-tickets-body { font-family: 'Outfit', sans-serif; padding-bottom: 150px; }
    
    .event-header-top { 
        background: rgba(255, 255, 255, 0.02); padding: 40px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
        margin-bottom: 30px;
    }
    .event-header-name { font-weight: 900; font-size: 2.2rem; color: #fff; margin-bottom: 10px; }
    .event-header-meta { font-size: 0.95rem; color: #cbd5e1; display: flex; align-items: center; gap: 20px; }
    .event-header-meta i { color: #a0aec0; margin-right: 5px; }

    .schedule-block { margin-bottom: 50px; }
    .day-badge { 
        background: var(--premium-gold-grad); color: #000; padding: 4px 15px; border-radius: 5px; 
        font-weight: 700; font-size: 0.8rem; text-transform: uppercase;
        margin-right: 15px;
    }
    .schedule-title { font-weight: 800; font-size: 1.25rem; color: #fff; display: flex; align-items: center; }

    .ticket-row {
        background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;
        padding: 25px; margin-bottom: 20px; transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
    }
    .ticket-row:hover { border-color: #F4D03F; background: rgba(255, 255, 255, 0.08); }

    .ticket-type-name { font-weight: 800; font-size: 1.15rem; color: #fff; margin-bottom: 5px; }
    .ticket-price { font-weight: 800; font-size: 1.3rem; color: var(--premium-gold); margin-right: 15px; }
    .ticket-stock { font-size: 0.85rem; color: #a0aec0; }

    .qty-control { width: 140px; display: flex; align-items: center; background: rgba(0,0,0,0.3); border-radius: 50px; padding: 5px; border: 1px solid rgba(255,255,255,0.1); }
    .qty-control button { border: none; background: rgba(255,255,255,0.1); color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; transition: 0.2s; }
    .qty-control button:hover { background: #F4D03F; color: #000; }
    .qty-control input { border: none; background: transparent; text-align: center; font-weight: 800; width: 50px; color: #fff; }

    /* Sticky Bottom Bar */
    .sticky-bottom-bar {
        position: fixed; bottom: 0; left: 0; right: 0;
        background: rgba(7, 17, 32, 0.95); backdrop-filter: blur(20px);
        border-top: 1px solid rgba(244, 208, 63, 0.3);
        padding: 25px 30px; z-index: 1000;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.6);
        display: none; /* Show only when tickets selected */
    }
    @media (min-width: 992px) {
        .sticky-bottom-bar { left: 265px; } /* Align with sidebar width */
    }
    .sticky-content { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; }
    .total-info { color: #fff; }
    .total-label { font-size: 0.85rem; color: #a0aec0; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
    .total-amount { font-size: 1.8rem; font-weight: 900; color: #F4D03F; line-height: 1; margin-top: 5px; }

    .btn-buy-now {
        background: var(--premium-gold-grad); color: #000; border: none; font-weight: 800;
        border-radius: 50px; padding: 15px 45px; transition: 0.3s;
        box-shadow: 0 5px 15px rgba(244, 208, 63, 0.3);
    }
    .btn-buy-now:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(244, 208, 63, 0.5); color: #000; }
</style>
@endsection

@section('content')
<div class="event-tickets-body pt-5">
    
    <div class="event-header-top shadow-sm">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.buyTickets') }}" class="text-muted text-decoration-none">Event</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #cbd5e1; font-weight: 700;">{{ $event->name }}</li>
                    </ol>
                </nav>
                <h1 class="event-header-name">{{ $event->name }}</h1>
                <div class="event-header-meta">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</span>
                </div>
            </div>
            <div class="mt-3 mt-lg-0">
                <img src="{{ $event->banner ? asset('storage/'.$event->banner) : asset('assets/img/easytix_login_bg.png') }}" alt="{{ $event->name }}" style="height: 120px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            </div>
        </div>
    </div>

    <form action="{{ route('cart.add') }}" method="POST" id="bulkTicketForm">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <div class="container mt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-lg position-relative overflow-hidden" style="background: rgba(255, 255, 255, 0.03); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-pill p-3 me-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: var(--premium-gold-grad) !important;">
                                    <i class="fas fa-info-circle text-dark fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-white mb-1">Batas Pembelian Tiket</h5>
                                    <p class="text-muted small mb-0">Setiap akun dibatasi maksimal 10 tiket per event (Termasuk yang sudah dibeli).</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-muted small text-uppercase fw-bold mb-1">Kuota Event Ini</span>
                                @php 
                                    $totalUsed = $purchasedCount ?? 0;
                                    $isFull = $totalUsed >= 10;
                                @endphp
                                <h3 class="fw-bold {{ $totalUsedCount >= 10 ? 'text-danger' : 'text-warning' }} mb-0">
                                    {{ $totalUsedCount }} / 10 <small class="fs-6 opacity-75">Tiket</small>
                                </h3>
                            </div>
                        </div>
                        <div style="position: absolute; bottom: 0; left: 0; height: 4px; width: {{ min(($totalUsedCount / 10) * 100, 100) }}%; background: var(--premium-gold-grad); transition: width 0.5s ease;"></div>
                    </div>
                </div>
            </div>

            @php $dayIndex = 1; @endphp
            @foreach($event->event_schedule->sortBy('event_date') as $schedule)
            <div class="schedule-block">
                <div class="schedule-title mb-4">
                    <span class="day-badge">HARI {{ $dayIndex++ }}</span>
                    {{ \Carbon\Carbon::parse($schedule->event_date)->translatedFormat('l, d F Y') }}
                </div>

                <div class="row">
                    @forelse($schedule->tickets as $ticket)
                    <div class="col-12">
                        <div class="ticket-row" data-price="{{ $ticket->price }}">
                            <div class="flex-grow-1">
                                <h4 class="ticket-type-name">{{ $ticket->ticket_type->name }}</h4>
                                <div class="d-flex align-items-center">
                                    @php 
                                        // Hitung stok yang benar-benar bisa dilihat user ini
                                        // Rumus: Total Kapasitas - Jatah yang di-reserve oleh ORANG LAIN
                                        $reservedByOthers = \App\Models\WaitingList::where('ticket_id', $ticket->id)
                                            ->where('status', 'approved')
                                            ->where('user_id', '!=', auth()->id())
                                            ->sum('quantity');
                                        
                                        $visibleStock = max(0, $ticket->capacity - $reservedByOthers);
                                    @endphp
                                    
                                    <span class="ticket-price">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                    @if($visibleStock > 0)
                                        <span class="ticket-stock">Tersedia {{ $visibleStock }} slot</span>
                                    @else
                                        <span class="fw-bold" style="color: #ff4d4d; font-size: 0.85rem;">Habis Terjual</span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                                @php $userWL = $activeWaitingLists[$ticket->id] ?? null; @endphp
                                
                                <div class="d-flex align-items-center gap-3">
                                    @if($ticket->capacity > 0)
                                        @if($userWL && $userWL->status == 'approved')
                                            <div class="d-flex align-items-center gap-3 me-3">
                                                <div class="px-3 py-2 rounded-pill border border-success border-opacity-50" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71; font-size: 0.8rem; font-weight: 700;">
                                                    <i class="fas fa-check-circle me-1"></i> Slot Tersedia
                                                </div>
                                                <button type="button" class="btn btn-outline-danger border-0 p-0 rounded-circle d-flex align-items-center justify-content-center" 
                                                        style="width: 28px; height: 28px; color: #ff4d4d; background: rgba(255, 77, 77, 0.1); transition: 0.2s;" 
                                                        onclick="confirmCancelWL('{{ $userWL->id }}', 'Batalkan slot khusus ini? Tiket akan diberikan ke antrean berikutnya.')"
                                                        onmouseover="this.style.transform='scale(1.1)'; this.style.background='rgba(255, 77, 77, 0.2)';" 
                                                        onmouseout="this.style.transform='scale(1)'; this.style.background='rgba(255, 77, 77, 0.1)';"
                                                        title="Batalkan Slot">
                                                    <i class="fas fa-times fs-6"></i>
                                                </button>
                                            </div>
                                        @endif
                                        <div class="qty-control">
                                            <button type="button" class="btn-qty" data-action="minus" onclick="updateQty(this, -1)">-</button>
                                            @php 
                                                // Jangan hitung waiting list milik user sendiri untuk ticket ini ke dalam limit belanja saat ini
                                                $allWLCount = $pendingWLCount + $approvedWLCount;
                                                $myOtherWaitingLists = $allWLCount - ($userWL ? $userWL->quantity : 0);
                                                $currentLimit = 10 - $purchasedCount - $myOtherWaitingLists;
                                                // Limit pembelian adalah yang terkecil antara kuota akun dan stok yang tersedia untuk user ini
                                                $maxPurchase = min($currentLimit, $visibleStock);
                                            @endphp
                                            <input type="number" name="tickets[{{ $ticket->id }}]" 
                                                   value="{{ ($userWL && $userWL->status == 'approved') ? $userWL->quantity : 0 }}" 
                                                   min="0" max="{{ $maxPurchase }}" readonly class="qty-input">
                                            <button type="button" class="btn-qty" data-action="plus" onclick="updateQty(this, 1)">+</button>
                                        </div>
                                    @else
                                        @if($userWL)
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="px-3 py-2 rounded-pill border border-warning border-opacity-50" style="background: rgba(244, 208, 63, 0.1); color: #F4D03F; font-size: 0.85rem; font-weight: 700;">
                                                    <i class="fas fa-hourglass-half me-2"></i> Menunggu Persetujuan
                                                </div>
                                                <button type="button" class="btn btn-outline-danger border-0 p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                                        style="width: 28px; height: 28px; color: #ff4d4d; background: rgba(255, 77, 77, 0.1); transition: 0.2s;" 
                                                        onclick="confirmCancelWL('{{ $userWL->id }}', 'Apakah Anda yakin ingin membatalkan pengajuan waiting list ini?')"
                                                        onmouseover="this.style.transform='scale(1.1)'; this.style.background='rgba(255, 77, 77, 0.2)';" 
                                                        onmouseout="this.style.transform='scale(1)'; this.style.background='rgba(255, 77, 77, 0.1)';"
                                                        title="Batalkan Antrean">
                                                    <i class="fas fa-times fs-6"></i>
                                                </button>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" onclick="openWaitingList('{{ $ticket->id }}', '{{ $ticket->ticket_type->name }}')">
                                                <i class="fas fa-clock me-2"></i> Join Waiting List
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 p-3 text-center rounded-3 bg-transparent border border-light border-opacity-25">
                        <p class="text-muted mb-0">Tiket belum tersedia untuk hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        <div class="sticky-bottom-bar" id="stickyBar" style="display: none;">
            <div class="sticky-content">
                <div class="total-info">
                    <div class="total-label">Total Pembelian (<span id="totalTicketsCount">0</span> Tiket)</div>
                    <div class="total-amount">Rp <span id="totalAmountText">0</span></div>
                </div>
                <div>
                    <button type="submit" class="btn-buy-now">
                        LANJUTKAN PEMBAYARAN <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Global Hidden Form for Cancellation (To avoid nested forms) --}}
<form id="globalCancelForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<!-- Waiting List Modal -->
<div class="modal fade" id="waitingListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background: rgba(15, 25, 45, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-clock text-warning me-2"></i> Join Waiting List</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.waitingList.join') }}" method="POST">
                @csrf
                <input type="hidden" name="ticket_id" id="wlTicketId">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Anda akan didaftarkan ke antrian untuk tiket <span class="text-warning fw-bold" id="wlTicketTypeName"></span>. Organizer akan memberikan slot prioritas jika kuota ditambah.</p>
                    
                    <div class="mb-4 text-center">
                        <label class="form-label small fw-bold text-light d-block mb-3">Jumlah Tiket yang Dibutuhkan</label>
                        @php $remaining = 10 - $totalUsedCount; @endphp
                        @if($remaining > 0)
                            <div class="qty-control mx-auto shadow-lg" style="width: 180px; transform: scale(1.1); background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2);">
                                <button type="button" class="btn-qty" data-action="minus" onclick="updateQty(this, -1)">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $remaining }}" readonly class="qty-input fs-5" style="width: 70px;">
                                <button type="button" class="btn-qty" data-action="plus" onclick="updateQty(this, 1)">+</button>
                            </div>
                        @else
                            <div class="alert alert-danger border-0 rounded-4 py-2 small mb-0">
                                <i class="fas fa-exclamation-circle me-1"></i> Batas pembelian (10 tiket) telah tercapai.
                            </div>
                        @endif
                        <small class="text-muted mt-4 d-block">Terhitung dari {{ $purchasedCount }} tiket terbayar & {{ $pendingWLCount + $approvedWLCount }} tiket di antrian.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    @if($remaining > 0)
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Kirim Permintaan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateQty(btn, change) {
        const input = btn.parentElement.querySelector('input');
        const min = parseInt(input.getAttribute('min')) || 0;
        const maxStock = parseInt(input.getAttribute('max')) || 0;
        let currentInputVal = parseInt(input.value) || 0;
        
        // Hitung total tiket yang sedang dipilih di seluruh kategori
        // Kita kunci hanya pada .ticket-row agar tidak menghitung input di modal WL
        let totalSelectedNow = 0;
        document.querySelectorAll('.ticket-row .qty-input').forEach(inp => {
            // Kita hitung total, tapi kurangi nilai input yang sedang kita ubah (agar tidak double count)
            if (inp !== input) {
                totalSelectedNow += (parseInt(inp.value) || 0);
            }
        });

        // baseLimitCount hanya menghitung Tiket Terbeli + Pending Waiting List.
        // Tiket Approved dihitung dinamis dari input UI oleh totalSelectedNow.
        const totalBaseUsed = {{ $baseLimitCount }}; 
        const limitMaksimal = 10;
        
        let newVal = currentInputVal + change;
        
        // Validasi bawah
        if (newVal < min) return;
        
        // Validasi Stok/Reserved per tiket
        if (newVal > maxStock) {
            Swal.fire({
                icon: 'info',
                title: 'Stok Terbatas',
                text: 'Hanya dapat membeli 10 tiket untuk setiap event.',
                confirmButtonColor: '#F4D03F',
                background: '#071120',
                color: '#fff'
            });
            return;
        }

        // Validasi Limit Global 10 Tiket
        if ((totalBaseUsed + totalSelectedNow + newVal) > limitMaksimal) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Kuota Tercapai',
                text: 'Maksimal pembelian untuk satu event adalah 10 tiket (termasuk tiket yang sudah Anda miliki).',
                confirmButtonColor: '#F4D03F',
                background: '#071120',
                color: '#fff'
            });
            return;
        }
        
        input.value = newVal;
        updateStickyBar();
    }

    function updateStickyBar() {
        const allRows = document.querySelectorAll('.ticket-row');
        const bar = document.getElementById('stickyBar');
        const countDisplay = document.getElementById('totalTicketsCount');
        const amountDisplay = document.getElementById('totalAmountText');
        
        let totalCount = 0;
        let totalAmount = 0;
        
        allRows.forEach(row => {
            const qtyInput = row.querySelector('.qty-input');
            if (qtyInput) {
                const qty = parseInt(qtyInput.value);
                const price = parseInt(row.getAttribute('data-price'));
                totalCount += qty;
                totalAmount += (qty * price);
            }
        });
        
        if (totalCount > 0) {
            bar.style.display = 'block';
            countDisplay.innerText = totalCount;
            amountDisplay.innerText = new Intl.NumberFormat('id-ID').format(totalAmount);
        } else {
            bar.style.display = 'none';
        }
    }

    function confirmCancelWL(id, message) {
        const form = document.getElementById('globalCancelForm');
        form.action = `/user/waiting-list/${id}/cancel`;
        
        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak',
            confirmButtonColor: '#e74c3c',
            background: '#071120',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Clear old timer if any
        localStorage.removeItem('payment_expiry');

        // Initial check in case of browser back
        updateStickyBar();

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Opps...',
                text: {!! json_encode(session('error')) !!},
                confirmButtonColor: '#ef4444',
                background: '#071120',
                color: '#fff'
            });
        @endif

        @if(session('payment_failed'))
             Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: "{{ session('payment_failed') }}",
                confirmButtonColor: '#ef4444',
                background: '#071120',
                color: '#fff'
            });
        @endif
    });

    function openWaitingList(ticketId, typeName) {
        document.getElementById('wlTicketId').value = ticketId;
        document.getElementById('wlTicketTypeName').innerText = typeName;
        
        // Trigger modal menggunakan Bootstrap 5 API atau jQuery fallback
        const modalEl = document.getElementById('waitingListModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        } else {
            $(modalEl).modal('show');
        }
    }
</script>
@endsection
