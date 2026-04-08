@extends('layouts.master')

@section('ExtraCSS')
<style>
    .verification-card-premium {
        border-radius: 2.5rem !important;
        background: rgba(20, 46, 94, 0.2) !important;
        backdrop-filter: blur(10px);
        padding: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }
    .scanner-btn-premium {
        width: 100%;
        padding: 1.5rem !important;
        border-radius: 1.5rem !important;
        font-size: 1.2rem !important;
        font-weight: 800 !important;
        background: var(--premium-gold-grad) !important;
        color: #000 !important;
        border: none !important;
        box-shadow: 0 10px 30px rgba(244, 208, 63, 0.4) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        margin-top: 2rem;
    }
    .scanner-btn-premium:hover {
        transform: scale(1.03) translateY(-5px);
        box-shadow: 0 20px 40px rgba(244, 208, 63, 0.5) !important;
    }
    .ticket-input-premium {
        border-radius: 1rem !important;
        padding: 1rem 1.5rem !important;
        border: 2px solid #f0f0f0 !important;
        font-weight: 600 !important;
        font-size: 1.1rem !important;
        transition: all 0.3s !important;
        text-align: center;
        letter-spacing: 1px;
    }
    .ticket-input-premium:focus {
        border-color: var(--premium-gold) !important;
        box-shadow: 0 0 0 4px rgba(244, 208, 63, 0.1) !important;
    }
    .history-card-premium {
        border-radius: 2rem !important;
        background: rgba(7, 17, 32, 0.4) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        overflow: hidden;
    }
    .badge-status-premium {
        padding: 0.5rem 1.2rem !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="page-inner">
        <div class="page-header mb-5 text-center d-block">
            <h3 class="fw-bold display-6 mb-2">Verification Hub</h3>
            <p class="text-muted fs-5">Pinda tiket atau masukkan kode secara manual untuk verifikasi instan.</p>
        </div>

        <div class="row pt-2">
            <div class="col-md-7 mx-auto fade-in-up">
                <div class="card verification-card-premium border-0">
                    <div class="card-body p-0">
                        <div class="mb-5 d-flex align-items-center justify-content-center">
                            <div class="icon-big bg-soft-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                <i class="fas fa-qrcode"></i>
                            </div>
                        </div>
                        
                        <h4 class="fw-bold mb-4">Input Kode Tiket</h4>
                        <form id="verifyTicketForm" action="{{ route('organizer.processVerification', $schedule->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-0 p-0">
                                <input type="text" class="form-control ticket-input-premium mb-4" id="ticket_code" name="ticket_code" placeholder="TIX-2026-XXXXX" required autofocus>
                                <button class="btn btn-dark btn-round w-100 py-3 fw-bold fs-6 shadow-sm" type="submit">Verify Now <i class="fas fa-check-circle ms-2"></i></button>
                            </div>
                        </form>
                        
                        <div class="mt-5 border-top pt-5">
                            <h5 class="text-muted fw-bold small text-uppercase mb-4">Atau Gunakan Kamera</h5>
                            <button type="button" class="btn btn-primary scanner-btn-premium border-0" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                                <i class="fas fa-camera me-3"></i> OPEN SCANNER
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="row pt-5 mt-4">
            <div class="col-md-11 mx-auto fade-in-up" style="animation-delay: 0.2s;">
                <div class="card history-card-premium shadow-none border-0 overflow-hidden">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4 pt-5">
                        <h4 class="card-title fw-bold">Recent Verifications <span class="badge bg-light text-muted fw-bold ms-2 border">Today</span></h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead style="background: rgba(0,0,0,0.3) !important;">
                                    <tr class="small fw-bold text-uppercase">
                                        <th class="ps-4 py-4" style="color: var(--premium-gold) !important; border-bottom: 1px solid rgba(255,255,255,0.1);">Time</th>
                                        <th style="color: var(--premium-gold) !important; border-bottom: 1px solid rgba(255,255,255,0.1);">Ticket Code</th>
                                        <th style="color: var(--premium-gold) !important; border-bottom: 1px solid rgba(255,255,255,0.1);">Owner Name</th>
                                        <th style="color: var(--premium-gold) !important; border-bottom: 1px solid rgba(255,255,255,0.1);">Ticket Type</th>
                                        <th class="text-end pe-4" style="color: var(--premium-gold) !important; border-bottom: 1px solid rgba(255,255,255,0.1);">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($todayVerifications as $verify)
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">{{ $verify->updated_at->format('H:i:s') }}</td>
                                            <td class="fw-bold text-dark">{{ $verify->ticket_code }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2">
                                                        <span class="avatar-title rounded-circle bg-light text-dark fw-bold">{{ substr($verify->owner_name, 0, 1) }}</span>
                                                    </div>
                                                    {{ $verify->owner_name }}
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark border fw-bold px-3 py-2 rounded-pill">{{ $verify->ticket->ticket_type->name }}</span></td>
                                            <td class="text-end pe-4">
                                                <span class="badge badge-status-premium bg-gradient-success text-white shadow-sm">
                                                    {{ strtoupper($verify->status) }} <i class="fas fa-check-circle ms-1"></i>
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="5" class="py-5">
                                                <img src="https://img.icons8.com/bubbles/200/search.png" alt="No data" style="width: 100px; opacity: 0.5;">
                                                <p class="text-muted mt-3 fw-medium">Belum ada aktivitas verifikasi hari ini.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 2.5rem; overflow: hidden; background: #071120;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-camera text-warning me-2"></i> Scanning...</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="closeScannerModal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="reader" style="width: 100%; border-radius: 1.5rem; overflow: hidden; border: 2px solid rgba(255,255,255,0.1);"></div>
                <div class="mt-4 py-3">
                    <div class="spinner-grow text-warning spinner-grow-sm me-2" role="status"></div>
                    <p class="text-light opacity-50 small d-inline-block mb-0">Deteksi QR Code otomatis aktif.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: '{!! session('success') !!}',
        icon: 'success',
        confirmButtonText: 'Great!',
        customClass: {
            popup: 'rounded-5',
            confirmButton: 'btn btn-primary btn-round px-5'
        }
    });
</script>
@endif

@if(session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: 'Gagal!',
        text: '{!! session('error') !!}',
        icon: 'error',
        confirmButtonText: 'Tutup',
        customClass: {
            popup: 'rounded-5',
            confirmButton: 'btn btn-dark btn-round px-5'
        }
    });
</script>
@endif

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrCode;

    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('ticket_code').value = decodedText;
        html5QrCode.stop().then((ignore) => {
            document.getElementById('verifyTicketForm').submit();
        }).catch((err) => {
            console.error("Stop failed: " + err);
            document.getElementById('verifyTicketForm').submit();
        });
    }

    const scannerModal = document.getElementById('qrScannerModal');
    scannerModal.addEventListener('shown.bs.modal', function () {
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 15, qrbox: { width: 280, height: 280 } };
        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess).catch(err => console.error(err));
    });

    scannerModal.addEventListener('hidden.bs.modal', function () {
        if(html5QrCode) html5QrCode.stop();
    });
</script>
@endsection
