@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .buy-tickets-wrapper { font-family: 'Outfit', sans-serif; }
    .gold-gradient { background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%); }
    .btn-premium {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000; border: none; font-weight: 600; border-radius: 50px;
        padding: 10px 25px; transition: all 0.3s ease;
    }
    .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(244, 208, 63, 0.3); }
    .event-card {
        border-radius: 20px; overflow: hidden; background: #fff;
        border: 1px solid rgba(0,0,0,0.05); transition: all 0.4s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .ticket-option {
        border: 1px solid #eee; border-radius: 15px; padding: 15px;
        margin-bottom: 15px; transition: all 0.3s;
    }
    .ticket-option:hover { border-color: #F4D03F; background: rgba(244, 208, 63, 0.05); }
    .cart-badge {
        position: fixed; bottom: 30px; right: 30px; z-index: 1000;
        width: 70px; height: 70px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #071120; color: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        transition: all 0.3s;
    }
    .cart-badge:hover { transform: scale(1.1); color: #F4D03F; }
    .cart-count {
        position: absolute; top: 0; right: 0; background: #E67E22;
        color: #fff; width: 25px; height: 25px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="container buy-tickets-wrapper py-4">
    <div class="page-inner">
        <div class="d-flex align-items-center mb-4" data-aos="fade-down">
            <h2 class="fw-bold mb-0">Pilih Tiket <span class="text-warning">Impianmu</span></h2>
            @if(session('success'))
                <div class="alert alert-success ms-auto mb-0 py-2 px-4 rounded-pill">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="row">
            @foreach($events as $event)
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="event-card">
                    <div style="height: 200px; position: relative;">
                        <img src="{{ asset('assets/img/easytix_login_bg.png') }}" class="w-100 h-100" style="object-fit: cover;">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 20px;">
                            <h4 class="text-white fw-bold mb-0">{{ $event->name }}</h4>
                            <p class="text-light opacity-75 mb-0 small"><i class="fas fa-map-marker-alt me-1"></i> {{ $event->location }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold text-muted mb-3 text-uppercase" style="letter-spacing: 1px;">Pilih Tipe Tiket:</h6>
                        
                        @php $hasTickets = false; @endphp
                        @foreach($event->event_schedule as $schedule)
                            @foreach($schedule->tickets as $ticket)
                                @php $hasTickets = true; @endphp
                                <div class="ticket-option">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $ticket->ticket_type->name }}</h5>
                                            <span class="text-warning fw-bold">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                            @if($ticket->capacity <= 50)
                                                <small class="text-danger d-block mt-1"><i class="fas fa-fire me-1"></i> Sisa {{ $ticket->capacity }} slot!</small>
                                            @endif
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $ticket->capacity }}" class="form-control me-2" style="width: 70px; border-radius: 10px;">
                                            <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach

                        @if(!$hasTickets)
                            <div class="text-center py-3">
                                <p class="text-muted mb-0 italic">Tiket belum tersedia untuk event ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<a href="{{ route('cart.view') }}" class="cart-badge">
    <i class="fas fa-shopping-basket fs-3"></i>
    @if(session('cart') && count(session('cart')) > 0)
        <span class="cart-count">{{ count(session('cart')) }}</span>
    @endif
</a>
@endsection

@section('ExtraJS')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
