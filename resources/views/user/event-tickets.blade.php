@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .event-tickets-body { font-family: 'Outfit', sans-serif; }
    
    /* Header Section */
    .event-header-top { 
        background: rgba(255, 255, 255, 0.02); padding: 40px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
        margin-bottom: 30px;
    }
    .event-header-name { font-weight: 900; font-size: 2.2rem; color: #fff; margin-bottom: 10px; }
    .event-header-meta { font-size: 0.95rem; color: #cbd5e1; display: flex; align-items: center; gap: 20px; }
    .event-header-meta i { color: #a0aec0; margin-right: 5px; }

    /* Schedule & Tickets */
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
    .ticket-row:hover { border-color: rgba(255,255,255,0.3); box-shadow: 0 8px 25px rgba(0,0,0,0.4); }

    .ticket-type-name { font-weight: 800; font-size: 1.15rem; color: #fff; margin-bottom: 5px; }
    .ticket-price { font-weight: 800; font-size: 1.3rem; color: var(--premium-gold); margin-right: 15px; }
    .ticket-stock { font-size: 0.85rem; color: #a0aec0; }

    .btn-action-add {
        background: var(--premium-gold-grad); color: #000; border: none; font-weight: 800;
        border-radius: 50px; padding: 12px 35px; transition: 0.3s;
    }
    .btn-action-add:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(244, 208, 63, 0.4); color: #000; }

    .qty-control { width: 140px; display: flex; align-items: center; background: rgba(0,0,0,0.3); border-radius: 50px; padding: 5px; border: 1px solid rgba(255,255,255,0.1); }
    .qty-control button { border: none; background: rgba(255,255,255,0.1); color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; transition: 0.2s; }
    .qty-control button:hover { background: rgba(255,255,255,0.2); }
    .qty-control input { border: none; background: transparent; text-align: center; font-weight: 800; width: 50px; color: #fff; }
</style>
@endsection

@section('content')
<div class="event-tickets-body pt-5 pb-5">
    
    <!-- Simple Header -->
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

    <div class="container mt-4">
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
                    <div class="ticket-row">
                        <div class="flex-grow-1">
                            <h4 class="ticket-type-name">{{ $ticket->ticket_type->name }}</h4>
                            <div class="d-flex align-items-center">
                                <span class="ticket-price">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                <span class="ticket-stock">Tersedia {{ $ticket->capacity }} slot</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                            @if($ticket->capacity > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-3">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <div class="qty-control">
                                    <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                    <input type="number" name="quantity" value="0" min="0" max="{{ $ticket->capacity }}" readonly>
                                    <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                </div>
                                <button type="submit" class="btn-action-add shadow-sm">
                                    Pesan <i class="fas fa-shopping-cart ms-1"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('cart.waitlist') }}" method="POST" class="d-flex align-items-center gap-3">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <div class="qty-control">
                                    <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                    <input type="number" name="quantity" value="1" min="1" readonly>
                                    <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                </div>
                                <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm" style="border-radius: 50px;">
                                    <i class="fas fa-clock me-2"></i> Join Waiting List
                                </button>
                            </form>
                            @endif
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

</div>
@endsection

@section('ExtraJS')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add quantity validation for ticket forms
        const ticketForms = document.querySelectorAll('form[action$="/cart/add"]');
        ticketForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const qtyInput = this.querySelector('input[name="quantity"]');
                const qty = parseInt(qtyInput ? qtyInput.value : 0);
                
                if (qty <= 0) {
                    e.preventDefault();
                    swalPremium.fire({
                        icon: 'warning',
                        text: 'Silakan masukkan jumlah tiket terlebih dahulu.',
                        confirmButtonText: 'OKE',
                        confirmButtonColor: '#3b82f6',
                        background: '#071120',
                        color: '#fff',
                        customClass: {
                            popup: 'border border-light shadow-lg'
                        }
                    });
                }
            });
        });

        @if(session('info'))
            swalPremium.fire("Info", "{{ session('info') }}", "info");
        @endif
        
        @if(session('waiting_list_prompt'))
            swalPremium.fire({
                title: "Opps, Stok Gagal!",
                text: "{{ session('waiting_list_prompt')['message'] }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Masuk Waiting List",
                cancelButtonText: "Batal",
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#dc3545"
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "{{ route('cart.waitlist') }}";
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ session('waiting_list_prompt')['ticket_id'] }}">
                        <input type="hidden" name="quantity" value="{{ session('waiting_list_prompt')['quantity'] }}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        @endif
    });
</script>
@endsection
