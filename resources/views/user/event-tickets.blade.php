@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .event-tickets-body { font-family: 'Outfit', sans-serif; background: #fdfdfd; }
    
    /* Header Section */
    .event-header-top { 
        background: #fff; padding: 40px 0; border-bottom: 1px solid #f0f0f0; 
        margin-bottom: 30px;
    }
    .event-header-name { font-weight: 900; font-size: 2.2rem; color: #111; margin-bottom: 10px; }
    .event-header-meta { font-size: 0.95rem; color: #666; display: flex; align-items: center; gap: 20px; }
    .event-header-meta i { color: #888; margin-right: 5px; }

    /* Schedule & Tickets */
    .schedule-block { margin-bottom: 50px; }
    .day-badge { 
        background: #000; color: #fff; padding: 4px 15px; border-radius: 5px; 
        font-weight: 700; font-size: 0.8rem; text-transform: uppercase;
        margin-right: 15px;
    }
    .schedule-title { font-weight: 800; font-size: 1.25rem; color: #000; display: flex; align-items: center; }

    .ticket-row {
        background: #fff; border: 1px solid #eee; border-radius: 15px;
        padding: 25px; margin-bottom: 20px; transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .ticket-row:hover { border-color: #000; box-shadow: 0 8px 20px rgba(0,0,0,0.08); }

    .ticket-type-name { font-weight: 800; font-size: 1.15rem; color: #111; margin-bottom: 5px; }
    .ticket-price { font-weight: 800; font-size: 1.3rem; color: #000; margin-right: 15px; }
    .ticket-stock { font-size: 0.85rem; color: #888; }

    .btn-action-add {
        background: #000; color: #fff; border: none; font-weight: 800;
        border-radius: 50px; padding: 12px 35px; transition: 0.3s;
    }
    .btn-action-add:hover { background: #333; transform: translateY(-2px); }

    .qty-control { width: 140px; display: flex; align-items: center; background: #f5f5f5; border-radius: 50px; padding: 5px; }
    .qty-control button { border: none; background: #fff; color: #000; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; }
    .qty-control input { border: none; background: transparent; text-align: center; font-weight: 800; width: 50px; color: #000; }
</style>
@endsection

@section('content')
<div class="event-tickets-body pb-5">
    
    <!-- Simple Header -->
    <div class="event-header-top shadow-sm">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <a href="{{ route('user.buyTickets') }}" class="text-muted small text-decoration-none mb-3 d-block"><i class="fas fa-arrow-left"></i> Kembali ke daftar event</a>
                <h1 class="event-header-name">{{ $event->name }}</h1>
                <div class="event-header-meta">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</span>
                </div>
            </div>
            <div class="mt-3 mt-lg-0">
                <img src="{{ $event->image ? asset('storage/'.$event->image) : asset('assets/img/easytix_login_bg.png') }}" alt="{{ $event->name }}" style="height: 120px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
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
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-3">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <div class="qty-control">
                                    <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $ticket->capacity }}" readonly>
                                    <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                </div>
                                <button type="submit" class="btn-action-add shadow-sm">
                                    Pesan <i class="fas fa-shopping-cart ms-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 p-3 text-center bg-white rounded-3 border">
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
<script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('info'))
            swal("Info", "{{ session('info') }}", "info");
        @endif
        @if(session('success'))
            swal("Berhasil!", "{{ session('success') }}", "success");
        @endif
    });
</script>
@endsection
