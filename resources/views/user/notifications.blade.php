@extends('layouts.master')

@php
    $dashboardRoute = 'user.dashboard';
    if(auth()->user()->role === 'admin') {
        $dashboardRoute = 'admin.dashboard';
    } elseif(auth()->user()->role === 'organizer') {
        $dashboardRoute = 'organizer.dashboard';
    }
@endphp

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pusat Notifikasi</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route($dashboardRoute) }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Inbox</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Semua Notifikasi</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="notif-list">
                            @forelse($notifications as $notif)
                            <div class="notif-item p-4 border-bottom {{ $notif->is_read ? '' : 'bg-light' }}">
                                <div class="d-flex align-items-center">
                                    <div class="notif-icon-circle me-3 bg-{{ $notif->type === 'success' ? 'success' : ($notif->type === 'offer' ? 'warning' : 'primary') }} text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; border-radius: 50%; flex-shrink: 0;">
                                        <i class="fa fa-{{ $notif->type === 'success' ? 'check' : ($notif->type === 'offer' ? 'tag' : 'info-circle') }} fa-lg"></i>
                                    </div>
                                    <div class="notif-body flex-grow-1">
                                        <div class="dh-between d-flex justify-content-between align-items-start">
                                            <h5 class="fw-bold mb-1 {{ $notif->is_read ? 'text-dark' : 'text-primary' }}">{{ $notif->title }}</h5>
                                            <span class="text-muted small">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-muted mb-2">{{ $notif->message }}</p>
                                        @if($notif->link)
                                        <a href="{{ $notif->link }}" class="btn btn-sm btn-outline-primary btn-round">Lihat Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-5 text-center">
                                <div class="mb-3">
                                    <i class="fa fa-bell-slash fa-4x text-muted opacity-25"></i>
                                </div>
                                <h4 class="text-muted">Belum ada notifikasi untuk Anda</h4>
                                <p class="text-muted small">Semua kabar terbaru tentang tiket dan promo akan muncul di sini.</p>
                                <a href="{{ route($dashboardRoute) }}" class="btn btn-primary btn-round mt-3">Kembali ke Beranda</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notif-item {
        transition: background 0.3s ease;
        border-bottom-color: rgba(255,255,255,0.05) !important;
    }
    .notif-item.bg-light {
        background: rgba(255, 255, 255, 0.05) !important;
    }
    .notif-item:hover {
        background-color: rgba(255,255,255,0.08) !important;
    }
    .notif-icon-circle {
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
</style>
@endsection
