@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .edit-profile-wrapper { font-family: 'Outfit', sans-serif; }
    .card-settings {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        background: #fff;
    }
    .card-settings .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 20px 30px;
    }
    .card-settings .card-title {
        font-weight: 700;
        color: #071120;
        margin-bottom: 0;
    }
    .card-settings .card-body {
        padding: 30px;
    }
    /* Simple fixes for Tailwind classes if they clash or are missing */
    .max-w-xl { max-width: 36rem; }
    .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem; }

    /* Button Styles */
    .btn-primary-ez {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%) !important;
        color: white !important;
        border: none !important;
        padding: 12px 30px !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        transition: all 0.3s !important;
        box-shadow: 0 8px 20px rgba(230, 126, 34, 0.3) !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    .btn-primary-ez:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 12px 25px rgba(230, 126, 34, 0.4) !important;
        filter: brightness(1.1) !important;
    }
    .btn-danger-ez {
        background: transparent !important;
        color: #dc3545 !important;
        border: 2px solid #dc3545 !important;
        padding: 10px 30px !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        transition: all 0.3s !important;
        cursor: pointer !important;
    }
    .btn-danger-ez:hover {
        background: #dc3545 !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3) !important;
        transform: translateY(-3px) !important;
    }
    
    /* Form Input Fixes */
    input[type="text"], input[type="email"], input[type="password"] {
        width: 100% !important;
        padding: 12px 20px !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 12px !important;
        margin-top: 8px !important;
        background: #fdfdfd !important;
        transition: all 0.3s !important;
    }
    input:focus {
        border-color: #E67E22 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1) !important;
    }
    label {
        font-weight: 600 !important;
        color: #071120 !important;
        margin-top: 15px !important;
        display: block !important;
    }
</style>
@endsection

@section('content')
<div class="container edit-profile-wrapper py-5">
    <div class="page-inner">
        <div class="d-flex align-items-center mb-4">
            <h2 class="fw-bold mb-0">Account <span class="text-warning">Settings</span></h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-settings">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <div class="card card-settings">
                    <div class="card-header">
                        <h4 class="card-title">Ubah Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="card card-settings">
                    <div class="card-header">
                        <h4 class="card-title text-danger">Hapus Akun</h4>
                    </div>
                    <div class="card-body">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
