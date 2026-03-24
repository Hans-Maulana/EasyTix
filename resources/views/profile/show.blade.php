@extends('layouts.master')

@section('ExtraCSS')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .profile-wrapper { font-family: 'Outfit', sans-serif; }
    .profile-card {
        border-radius: 25px;
        background: #fff;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .profile-header {
        background: linear-gradient(135deg, #071120 0%, #1a2a44 100%);
        padding: 60px 20px;
        text-align: center;
        position: relative;
    }
    .profile-avatar-container {
        position: relative;
        display: inline-block;
        margin-top: -80px;
        z-index: 10;
    }
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 7px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        object-fit: cover;
    }
    .info-label {
        color: #888;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }
    .info-value {
        color: #071120;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .btn-edit-profile {
        background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
        color: #000;
        border: none;
        font-weight: 700;
        border-radius: 50px;
        padding: 12px 30px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 10px 20px rgba(244, 208, 63, 0.3);
    }
    .btn-edit-profile:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(244, 208, 63, 0.4);
        color: #000;
    }
    .account-badge {
        background: rgba(244, 208, 63, 0.2);
        color: #E67E22;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container profile-wrapper py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-header">
                </div>
                <div class="text-center">
                    <div class="profile-avatar-container">
                        <img src="{{ asset('assets/img/profile.jpg') }}" alt="User Avatar" class="profile-avatar">
                    </div>
                </div>
                
                <div class="p-5">
                    <div class="text-center mb-5">
                        <div class="account-badge text-uppercase">{{ $user->role ?? 'Member' }}</div>
                        <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <hr class="opacity-10 mb-5">
                    
                    <div class="row px-md-4">
                        <div class="col-md-6">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Alamat Email</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value">{{ $user->phone_number ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Tanggal Dibuat</div>
                            <div class="info-value">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">Terakhir Diperbarui</div>
                            <div class="info-value">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-5">
                        <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
