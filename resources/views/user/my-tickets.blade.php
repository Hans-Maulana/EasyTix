@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .tickets-wrapper { font-family: 'Outfit', sans-serif; }
    .ticket-item {
        border-radius: 20px; overflow: hidden; background: #fff;
        border: 1px solid rgba(0,0,0,0.05); margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: flex; flex-direction: column;
    }
    .qr-container {
        background: #f8f9fa; border-top: 1px dashed #ddd; padding: 20px;
        text-align: center; position: relative;
    }
    .qr-code {
        width: 150px; height: 150px; background: #fff;
        margin: 0 auto 15px; border-radius: 10px; display: flex;
        align-items: center; justify-content: center; border: 1px solid #eee;
    }
    .ticket-info { padding: 25px; }
</style>
@endsection

@section('content')
<div class="container tickets-wrapper py-4">
    <div class="page-inner">
        <div class="mb-5 d-flex align-items-center" data-aos="fade-down">
            <h2 class="fw-bold mb-0"><i class="fas fa-ticket-alt text-warning me-2"></i> Tiket <span class="text-warning">Kamu</span></h2>
            @if(session('success'))
                <div class="alert alert-success ms-auto mb-0 py-2 px-4 rounded-pill">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        @forelse($orders as $order)
            @foreach($order->orderItems as $item)
            <div class="row ticket-item bg-white mx-0" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="col-md-8 p-0">
                    <div class="ticket-info">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="badge bg-dark-blue text-white px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-star me-2 text-warning"></i> #{{ $order->id }}-{{ $item->id }}</span>
                            <span class="text-muted small fw-bold">Dipesan pada: {{ $item->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <h3 class="fw-bold text-dark mb-1">{{ $item->ticket->event_schedule->event->name }}</h3>
                        <p class="text-muted"><i class="fas fa-map-marker-alt text-warning me-2"></i> {{ $item->ticket->event_schedule->event->location }}</p>
                        
                        <div class="row mt-4">
                            <div class="col-6">
                                <h6 class="text-muted small text-uppercase fw-bold mb-1">Tipe Tiket</h6>
                                <h5 class="fw-bold text-dark">{{ $item->ticket->ticket_type->name }}</h5>
                            </div>
                            <div class="col-6 text-end">
                                <h6 class="text-muted small text-uppercase fw-bold mb-1">Nominal</h6>
                                <h5 class="fw-bold text-warning">Rp {{ number_format($item->price, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-0">
                    <div class="qr-container h-100 d-flex flex-column justify-content-center">
                        <div class="qr-code mt-3">
                            <!-- Simulated QR code using random placeholder/image or just an icon -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $item->qr_code }}" alt="QR Code" style="width: 140px; height: 140px;">
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Kode Tiket</h6>
                        <p class="text-muted small mb-3 letter-spacing-1">{{ $item->qr_code }}</p>
                        <p class="small text-danger italic">*Tunjukkan kodenya saat masuk gate</p>
                    </div>
                </div>
            </div>
            @endforeach
        @empty
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fas fa-ticket-alt text-muted mb-4" style="font-size: 5rem; opacity: 0.3;"></i>
                <h3 class="fw-bold text-muted">Belum ada tiket nih!</h3>
                <p class="text-muted mb-5">Tiket yang kamu beli nanti akan muncul di sini.</p>
                <a href="{{ route('user.buyTickets') }}" class="btn btn-warning rounded-pill py-3 px-5 fw-bold">Beli Tiket Sekarang</a>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('ExtraJS')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
