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
    }
    .qr-container {
        background: #f8f9fa; border-top: 1px dashed #ddd; padding: 20px;
        text-align: center;
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
            <h2 class="fw-bold mb-0"><i class="fas fa-ticket-alt text-warning me-2"></i> Tiket <span class="text-warning">Saya</span></h2>
            @if(session('success'))
                <div class="alert alert-success ms-auto mb-0 py-2 px-4 rounded-pill">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        @if(count($orderHistory) > 0)
            @foreach(array_reverse($orderHistory) as $order)
                @foreach($order['items'] as $item)
                <div class="row ticket-item bg-white mx-0" data-aos="fade-up">
                    <div class="col-md-8 p-0">
                        <div class="ticket-info">
                            <div class="d-flex justify-content-between mb-4 align-items-center">
                                <div>
                                    <span class="badge bg-dark-blue text-white px-3 py-2 rounded-pill shadow-sm me-2">
                                        <i class="fas fa-star me-2 text-warning"></i> #{{ $order['id'] }}
                                    </span>
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-check-circle me-1"></i> PAID
                                    </span>
                                </div>
                                <span class="text-muted small fw-bold">
                                    <i class="far fa-calendar-alt me-1"></i> {{ $order['created_at'] }}
                                </span>
                            </div>

                            <h3 class="fw-bold text-dark mb-1">{{ $item['name'] }}</h3>

                            <div class="row mt-4">
                                <div class="col-4">
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Tipe Tiket</h6>
                                    <h5 class="fw-bold text-dark">{{ $item['type'] }} (Tiket {{ $item['ticket_index'] ?? 1 }}/{{ $item['total_qty'] ?? 1 }})</h5>
                                </div>
                                <div class="col-4 text-center">
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Bayar Via</h6>
                                    <h5 class="fw-bold text-dark">
                                        @if($order['payment_method'] === 'QRIS')
                                            <i class="fas fa-qrcode text-warning me-1"></i> QRIS
                                        @else
                                            <i class="fas fa-university text-warning me-1"></i> Virtual Account
                                        @endif
                                    </h5>
                                </div>
                                <div class="col-4 text-end">
                                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Subtotal</h6>
                                    <h5 class="fw-bold text-warning">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-0">
                        <div class="qr-container h-100 d-flex flex-column justify-content-center">
                            <div class="qr-code mt-3 p-2">
                                <img src="{{ asset('storage/' . $item['qr_code']) }}" width="130" height="130" alt="QR Code">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Kode Tiket</h6>
                            <p class="text-muted small mb-2">{{ $item['qr_string'] ?? $item['qr_code'] }}</p>
                            <button class="btn btn-sm btn-outline-primary rounded-pill mb-2 mx-auto px-4" data-bs-toggle="modal" data-bs-target="#detailModal{{ md5($item['qr_code']) }}">
                                Lihat Detail
                            </button>
                            <p class="small text-danger italic">*Tunjukkan kodenya saat masuk gate</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Tiket -->
                <div class="modal fade" id="detailModal{{ md5($item['qr_code']) }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0" style="border-radius: 20px;">
                            <div class="modal-header border-0 bg-light" style="border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-ticket-alt text-warning me-2"></i> Detail Tiket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-center">
                                <h4 class="fw-bold mb-1">{{ $item['name'] }}</h4>
                                <p class="text-muted mb-4">{{ $item['type'] }} - Tiket {{ $item['ticket_index'] ?? 1 }} / {{ $item['total_qty'] ?? 1 }}</p>
                                
                                <div class="bg-light p-4 rounded-3 mb-4 d-inline-block border">
                                    <img src="{{ asset('storage/' . $item['qr_code']) }}" width="200" height="200" alt="QR Code">
                                </div>
                                
                                <h3 class="fw-bold text-dark" style="letter-spacing: 2px;">{{ $item['qr_string'] ?? $item['qr_code'] }}</h3>
                                <p class="text-muted small mb-0">Tunjukkan QR Code ini kepada petugas di pintu masuk.</p>
                                
                                <hr class="my-4 dashed" style="border-top: 1px dashed #ccc;">
                                
                                <div class="row text-start text-dark">
                                    <div class="col-6 mb-3">
                                        <small class="text-muted d-block text-uppercase">Order ID</small>
                                        <span class="fw-bold">{{ $order['id'] }}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <small class="text-muted d-block text-uppercase">Tanggal Beli</small>
                                        <span class="fw-bold">{{ $order['created_at'] }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block text-uppercase">Metode Pembayaran</small>
                                        <span class="fw-bold">
                                            @if($order['payment_method'] === 'QRIS')
                                                QRIS
                                            @else
                                                Virtual Account
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block text-uppercase">Subtotal</small>
                                        <span class="fw-bold text-warning">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforeach
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fas fa-ticket-alt text-muted mb-4" style="font-size: 5rem; opacity: 0.3;"></i>
                <h3 class="fw-bold text-muted">Belum ada tiket nih!</h3>
                <p class="text-muted mb-5">Tiket yang kamu beli nanti akan muncul di sini.</p>
                <a href="{{ route('user.buyTickets') }}" class="btn btn-warning rounded-pill py-3 px-5 fw-bold">Beli Tiket Sekarang</a>
            </div>
        @endif
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

