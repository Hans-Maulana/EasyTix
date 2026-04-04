@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .checkout-wrapper { font-family: 'Outfit', sans-serif; }
    .payment-option {
        border: 2px solid #eee; border-radius: 15px; padding: 20px;
        margin-bottom: 20px; transition: all 0.3s; cursor: pointer;
    }
    .payment-option:hover { border-color: #F4D03F; }
    .payment-option.active { border-color: #F4D03F; background: rgba(244, 208, 63, 0.05); }
    .btn-premium {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000; border: none; font-weight: 700; border-radius: 50px;
        padding: 15px 40px; transition: all 0.3s ease;
    }
    .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(244, 208, 63, 0.3); }
    .btn-premium:disabled {
        background: #ccc !important; color: #666 !important; cursor: not-allowed;
        transform: none !important; box-shadow: none !important;
    }
</style>
@endsection

@section('content')
<div class="container checkout-wrapper pt-5 pb-4">
    <div class="page-inner">
        <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down" data-aos-delay="100">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.view') }}" class="text-muted text-decoration-none">Keranjang</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #000; font-weight: 700;">Checkout</li>
            </ol>
        </nav>
        <div class="mb-5" data-aos="fade-down">
            <h2 class="fw-bold mb-0"><i class="fas fa-ticket-alt text-warning me-2"></i> Detail <span class="text-warning">Pesanan</span></h2>
        </div>

        <form action="{{ route('user.saveDetails') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="card border-0 rounded-4 p-4 shadow-sm mb-4">
                        <h5 class="fw-bold mb-4">Detail Pemilik Tiket</h5>
                        @php $ticketIdx = 0; @endphp
                        @foreach($cart as $id => $details)
                            @for($i = 0; $i < $details['quantity']; $i++)
                                <div class="ticket-detail-group mb-4 p-3 border border-light border-opacity-25 rounded-3 bg-transparent">
                                    <h6 class="fw-bold text-warning mb-3">Identitas Tiket #{{ ++$ticketIdx }} - {{ $details['name'] }} ({{ $details['type'] }})</h6>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label small fw-bold text-light">Nama Lengkap Sesuai KTP</label>
                                            <input type="text" name="tickets[{{ $id }}][{{ $i }}][name]" class="form-control rounded-pill req-field" placeholder="Nama Lengkap" value="{{ $ticketDetails[$id][$i]['name'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-light">Nomor Telepon (WhatsApp)</label>
                                            <input type="text" name="tickets[{{ $id }}][{{ $i }}][phone]" class="form-control rounded-pill req-field" placeholder="Nomor Telepon" value="{{ $ticketDetails[$id][$i]['phone'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-light">Email Aktif</label>
                                            <input type="email" name="tickets[{{ $id }}][{{ $i }}][email]" class="form-control rounded-pill req-field" placeholder="Email" value="{{ $ticketDetails[$id][$i]['email'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-light">Jenis Kelamin</label>
                                            <select name="tickets[{{ $id }}][{{ $i }}][gender]" class="form-select rounded-pill req-field" required>
                                                <option value="" disabled {{ !isset($ticketDetails[$id][$i]['gender']) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki" {{ (isset($ticketDetails[$id][$i]['gender']) && $ticketDetails[$id][$i]['gender'] == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ (isset($ticketDetails[$id][$i]['gender']) && $ticketDetails[$id][$i]['gender'] == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-light">Umur</label>
                                            <input type="number" name="tickets[{{ $id }}][{{ $i }}][age]" class="form-control rounded-pill req-field" placeholder="Umur" min="1" value="{{ $ticketDetails[$id][$i]['age'] ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: rgba(255,255,255,0.05);">
                        <h5 class="fw-bold mb-4">Total Bayar</h5>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $details)
                            @php $total += $details['price'] * $details['quantity']; @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">{{ $details['name'] }} ({{ $details['quantity'] }}x)</span>
                                <span class="small fw-bold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-4 mt-3">
                            <span class="fs-4">Total Amount</span>
                            <span class="fs-4 fw-bold text-warning">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" id="btnContinue" class="btn btn-premium w-100 py-3 mb-2" disabled>
                            LANJUT KE PEMBAYARAN <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('ExtraJS')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnContinue = document.getElementById('btnContinue');
        const reqFields = document.querySelectorAll('.req-field');

        function checkForm() {
            let allFilled = true;
            reqFields.forEach(field => {
                if (!field.value || field.value.trim() === "") {
                    allFilled = false;
                }
            });
            btnContinue.disabled = !allFilled;
        }

        reqFields.forEach(field => {
            field.addEventListener('input', checkForm);
            field.addEventListener('change', checkForm);
        });

        // Initial check for pre-filled data
        checkForm();
    });
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({ once: true, duration: 800 });
    });
</script>
@endsection
