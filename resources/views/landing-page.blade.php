<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyTix</title>
    <link rel="icon" href="{{ asset('assets/img/logo_easytix_new.png') }}" type="image/x-icon" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        :root {
            --dark-blue: #071120;
            --accent-blue: #142E5E;
            --gold: #F4D03F;
            --gold-gradient: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
            --text-light: #E0E6ED;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-blue);
            color: var(--text-light);
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(244, 208, 63, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(20, 46, 94, 0.5) 0%, transparent 50%);
        }


        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--dark-blue); }
        ::-webkit-scrollbar-thumb { background: var(--accent-blue); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        .text-gold { color: var(--gold) !important; }
        .bg-gold { background-color: var(--gold) !important; }
        .gradient-text {
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .navbar-custom {
            background: rgba(7, 17, 32, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px 0;
            transition: all 0.4s ease;
        }
        .navbar-custom.scrolled {
            padding: 10px 0;
            background: rgba(7, 17, 32, 0.95);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -1px;
            color: white !important;
        }
        .nav-link {
            font-weight: 500;
            color: var(--text-light) !important;
            margin: 0 15px;
            position: relative;
            transition: all 0.3s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--gold);
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover, .nav-link.active { color: var(--gold) !important; }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-primary-custom {
            background: var(--gold-gradient);
            color: #000;
            border: none;
            box-shadow: 0 8px 20px rgba(244, 208, 63, 0.3);
        }
        .btn-primary-custom:hover {
            transform: translateY(-3px) scale(1.02);
            color: #000;
            box-shadow: 0 15px 30px rgba(244, 208, 63, 0.4);
        }
        .btn-outline-custom {
            border: 2px solid var(--gold);
            color: var(--gold);
            background: transparent;
        }
        .btn-outline-custom:hover {
            background: var(--gold);
            color: #000;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(244, 208, 63, 0.2);
        }

        .hero {
            height: 100vh;
            min-height: 700px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1533174000276-26798e1f0e8f?q=80&w=2000&auto=format&fit=crop') center/cover;
            z-index: -2;
            animation: zoomInOut 20s infinite alternate;
        }
        @keyframes zoomInOut {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, var(--dark-blue) 0%, rgba(7, 17, 32, 0.7) 50%, transparent 100%);
            z-index: -1;
        }
        .hero-content {
            z-index: 1;
        }
        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 20px;
        }
        
        .floating-badge {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 25px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: float 6s ease-in-out infinite;
        }
        .badge-1 { top: 20%; right: 10%; animation-delay: 0s; }
        .badge-2 { bottom: 25%; right: 25%; animation-delay: 2s; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .feature-item {
            padding: 40px;
            background: rgba(20, 46, 94, 0.3);
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.4s;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gold-gradient);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 0;
        }
        .feature-item:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            border-color: rgba(244, 208, 63, 0.3);
        }
        .feature-item:hover::before { opacity: 0.05; }
        .feature-item * { z-index: 1; position: relative; }
        .feature-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: rgba(244, 208, 63, 0.1);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 25px;
            transition: all 0.4s;
        }
        .feature-item:hover .feature-icon-wrapper {
            background: var(--gold-gradient);
            color: #000;
            transform: scale(1.1) rotate(5deg);
        }

        .concert-card {
            border-radius: 20px;
            overflow: hidden;
            background: rgba(20, 46, 94, 0.4);
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.4s;
        }
        .concert-card:hover {
            border-color: var(--gold);
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(244, 208, 63, 0.15);
        }
        .concert-img-wrapper {
            position: relative;
            height: 300px;
            overflow: hidden;
        }
        .concert-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .concert-card:hover .concert-img-wrapper img {
            transform: scale(1.1);
        }
        .concert-date {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            padding: 10px 15px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            font-weight: 700;
            line-height: 1.2;
        }
        .concert-date span { display: block; font-size: 0.8rem; color: var(--gold); }
        .concert-hot-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gold-gradient);
            color: #000;
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.8rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(244, 208, 63, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(244, 208, 63, 0); }
            100% { box-shadow: 0 0 0 0 rgba(244, 208, 63, 0); }
        }
        .concert-info { padding: 30px; }

        .stats-area {
            background: linear-gradient(rgba(7, 17, 32, 0.9), rgba(7, 17, 32, 0.9)), url('https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?q=80&w=2000&auto=format&fit=crop') center/cover;
            background-attachment: fixed;
            padding: 100px 0;
            border-top: 1px solid rgba(244, 208, 63, 0.2);
            border-bottom: 1px solid rgba(244, 208, 63, 0.2);
        }
        .stat-number {
            font-size: 4rem;
            font-weight: 900;
            color: var(--gold);
            margin-bottom: 10px;
        }

        .footer {
            background: #040A14;
            padding: 80px 0 30px;
        }
        .social-link {
            display: inline-flex;
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
            margin-right: 10px;
        }
        .social-link:hover {
            background: var(--gold);
            color: #000;
            transform: translateY(-5px);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 3rem; }
            .hero-overlay { background: linear-gradient(0deg, var(--dark-blue) 0%, rgba(7, 17, 32, 0.8) 100%); }
            .navbar-collapse {
                background: rgba(7, 17, 32, 0.95);
                backdrop-filter: blur(15px);
                padding: 20px;
                border-radius: 15px;
                margin-top: 15px;
            }
            .floating-badge { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/logo_easytix_new.png') }}" alt="EasyTix" height="50">
            </a>
            <button class="navbar-toggler shadow-none border-0 text-gold fs-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#konser">Jadwal Konser</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keunggulan">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>
                </ul>
                <div class="d-flex gap-3 align-items-center mt-3 mt-lg-0">
                    @guest
                        <a href="{{ route('login') }}" class="text-white text-decoration-none fw-semibold">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary-custom px-4 py-2">Daftar</a>
                    @else
                        <div class="dropdown">
                            <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle glass-card p-2 rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=F4D03F&color=000&size=100" class="rounded-circle me-2" height="35" alt="Avatar">
                                <span class="small fw-semibold">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark glass-card border-0 mt-2 p-2 shadow-lg" style="min-width: 200px;">
                                <li>
                                    <div class="dropdown-item-text">
                                        <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                                        <p class="mb-0 small opacity-75">{{ auth()->user()->email }}</p>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('dashboard') }}"><i class="fas fa-th-large me-2 small"></i> Dashboard</a></li>
                                <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle me-2 small"></i> Profil Saya</a></li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li>
                                    <form id="landing-logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-3 text-danger" data-confirm="Apakah Anda yakin ingin keluar dari akun ini?"><i class="fas fa-sign-out-alt me-2 small"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>

        <!-- Decorative Floating Badges (Desktop Only) -->
        <div class="floating-badge badge-1 d-none d-lg-flex">
            <div class="bg-gold text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="fa-solid fa-fire fs-4"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white">Coldplay Jakarta</h6>
                <small class="text-gold">Tersisa 120 Tiket</small>
            </div>
        </div>
        <div class="floating-badge badge-2 d-none d-lg-flex">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="fa-solid fa-check fs-4"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white">Transaksi Berhasil</h6>
                <small class="text-light opacity-75">a/n Budi Santoso</small>
            </div>
        </div>

        <div class="container hero-content">
            <div class="row">
                <div class="col-lg-7">
                    <span class="d-inline-block px-3 py-1 rounded-pill mb-4" style="background: rgba(244, 208, 63, 0.1); border: 1px solid var(--gold); color: var(--gold); font-weight: 600;" data-aos="fade-down">
                        <i class="fa-solid fa-star me-2"></i> #1 Platform Tiket Konser di Indonesia
                    </span>
                    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                        Amankan Tiket Konser <br>
                        <span class="gradient-text">Idolamu Sekarang!</span>
                    </h1>
                    <p class="fs-5 text-light opacity-75 mb-5 pe-lg-5" data-aos="fade-up" data-aos-delay="200">
                        Jangan biarkan momen berharga berlalu. Sistem antrean pintar kami memastikan kamu mendapatkan pengalaman war tiket yang adil, cepat, dan 100% aman.
                    </p>
                    <div class="d-flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="300">
                        <a href="#konser" class="btn btn-primary-custom btn-lg">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Cari Konser
                        </a>
                        <a href="#" class="btn btn-outline-custom btn-lg d-flex align-items-center gap-2">
                            <i class="fa-regular fa-circle-play fs-4"></i> Cara Kerja
                        </a>
                    </div>
                    
                    <!-- Trusted By -->
                    <div class="mt-5 pt-4 border-top border-secondary border-opacity-25" data-aos="fade-up" data-aos-delay="400">
                        <p class="text-uppercase tracking-wider small text-light opacity-50 fw-bold mb-3">Partner Resmi Event</p>
                        <div class="d-flex align-items-center gap-4 opacity-75">
                            <i class="fa-brands fa-spotify fs-2"></i>
                            <i class="fa-brands fa-apple fs-2"></i>
                            <i class="fa-brands fa-soundcloud fs-2"></i>
                            <i class="fa-brands fa-youtube fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hot Concerts Section -->
    <section id="konser" class="py-5 my-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div data-aos="fade-right">
                    <h6 class="text-gold fw-bold tracking-wider text-uppercase mb-2"><i class="fa-solid fa-fire me-2"></i> Sedang Hangat</h6>
                    <h2 class="display-5 fw-bold text-white mb-0">Konser <span class="gradient-text">Mendatang</span></h2>
                </div>
                <div class="d-none d-md-block" data-aos="fade-left">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-light rounded-circle swiper-prev" style="width:50px; height:50px;"><i class="fa-solid fa-arrow-left"></i></button>
                        <button class="btn btn-outline-light rounded-circle swiper-next" style="width:50px; height:50px;"><i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Swiper Slider -->
            <div class="swiper concertSwiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper py-3">
                    @forelse($events as $event)
                        @php
                            $schedule = $event->event_schedule->first();
                            $minPrice = $schedule ? $schedule->tickets->min('price') : 0;
                            $eventDate = $schedule ? \Carbon\Carbon::parse($schedule->event_date) : null;
                        @endphp
                        <div class="swiper-slide">
                            <div class="concert-card h-100">
                                <div class="concert-img-wrapper">
                                    @if($event->status == 'active')
                                        <span class="concert-hot-badge"><i class="fa-solid fa-bolt"></i> Active</span>
                                    @endif
                                    <div class="concert-date">
                                        <span class="text-uppercase">{{ $eventDate ? $eventDate->format('M') : 'TBD' }}</span>
                                        {{ $eventDate ? $eventDate->format('d') : '??' }}
                                    </div>
                                    @if($event->banner)
                                        <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->name }}">
                                    @else
                                        <img src="{{ asset('assets/img/easytix_login_bg.png') }}" alt="{{ $event->name }}">
                                    @endif
                                </div>
                                <div class="concert-info">
                                    <h4 class="text-white fw-bold mb-1">{{ $event->name }}</h4>
                                    <p class="text-gold small fw-bold mb-2">
                                        {{ $event->category->name ?? 'Uncategorized' }} • 
                                        @foreach($event->performers as $p)
                                            {{ $p->name }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </p>
                                    <p class="text-light opacity-75 small mb-4"><i class="fa-solid fa-location-dot text-gold me-2"></i> {{ $event->location }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-end pt-3 border-top border-secondary border-opacity-25">
                                        <div>
                                            <small class="text-light opacity-50 d-block">Harga mulai dari</small>
                                            <span class="text-gold fw-bold fs-5">Rp {{ number_format($minPrice, 0, ',', '.') }}</span>
                                        </div>
                                        <a href="/login" class="btn btn-primary-custom btn-sm px-4 py-2 rounded-pill">Beli</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h4 class="text-light opacity-50">Belum ada konser aktif saat ini.</h4>
                        </div>
                    @endforelse
                </div>
                <!-- Pagination for mobile -->
                <div class="swiper-pagination mt-4 d-md-none"></div>
            </div>
            
            <div class="text-center mt-5 pt-4" data-aos="fade-up">
                <a href="#" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold">Lihat Semua Jadwal <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-area my-5">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="0">
                    <div class="stat-number">1.2M+</div>
                    <p class="text-light fw-semibold text-uppercase tracking-wider fs-6">Tiket Terjual</p>
                </div>
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-number">500+</div>
                    <p class="text-light fw-semibold text-uppercase tracking-wider fs-6">Konser Sukses</p>
                </div>
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-number">50+</div>
                    <p class="text-light fw-semibold text-uppercase tracking-wider fs-6">Mitra Promotor</p>
                </div>
                <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-number">4.9/5</div>
                    <p class="text-light fw-semibold text-uppercase tracking-wider fs-6">Rating Pengguna</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="keunggulan" class="py-5 my-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-gold fw-bold tracking-wider text-uppercase mb-2">Mengapa Pilih Kami</h6>
                <h2 class="display-5 fw-bold text-white">Bukan Sekadar <span class="gradient-text">Beli Tiket</span></h2>
            </div>
            
            <div class="row g-4 pt-3">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-item">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-3">Anti Crash System</h4>
                        <p class="text-light opacity-75 mb-0">Server kami didukung cloud computing skala besar yang mampu menampung jutaan antrean war tiket tanpa down.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-item">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-3">Smart E-Ticket</h4>
                        <p class="text-light opacity-75 mb-0">Tiket digital dengan dynamic QR Code yang anti-palsu. Cukup scan di gate, masuk tanpa perlu print kertas.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-item">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-3">100% Refundable</h4>
                        <p class="text-light opacity-75 mb-0">Konser batal atau ditunda? Sistem auto-refund kami menjamin uang Anda kembali secara utuh dalam 2x24 jam.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="p-5 text-center mt-4 rounded-4 position-relative overflow-hidden shadow-lg" style="background: var(--gold-gradient);" data-aos="zoom-in">
                <!-- Background Pattern -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1; mix-blend-mode: overlay; pointer-events: none;"></div>
                
                <div class="position-relative z-index-1">
                    <h2 class="display-6 fw-bold text-dark mb-3">Siap Untuk Bernyanyi Bersama?</h2>
                    <p class="fs-5 text-dark opacity-75 mb-5 mx-auto fw-medium" style="max-width: 600px;">Buat akun sekarang dan dapatkan akses prioritas (*Early Bird*) untuk pembelian tiket konser artis favoritmu!</p>
                    <a href="/register" class="btn btn-dark btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg" style="letter-spacing: 1px;">
                        <i class="fa-solid fa-rocket me-2"></i> DAFTAR SEKARANG - GRATIS
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5 border-top border-secondary border-opacity-25 text-center text-lg-start">
        <div class="container py-4">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 pe-lg-5">
                    <a class="navbar-brand d-flex align-items-center gap-2 mb-4 justify-content-center justify-content-lg-start" href="#">
                        <img src="{{ asset('assets/img/kaiadmin/logo_EasyTix.png') }}" alt="EasyTix" height="40">
                    </a>
                    <p class="text-light opacity-50 mb-4">Platform penyedia layanan tiket event dan konser terbaik. Keamanan, kecepatan, dan kenyamanan pengguna adalah prioritas utama kami.</p>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6">
                    <h5 class="text-white fw-bold mb-4">Eksplor</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Jadwal Konser</a></li>
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Festival</a></li>
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Early Bird</a></li>
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Promo Tiket</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6">
                    <h5 class="text-white fw-bold mb-4">Dukungan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Pusat Bantuan</a></li>
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Cara Pembelian</a></li>
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Kebijakan Refund</a></li>
                        <li class="mb-3"><a href="#" class="text-light opacity-75 text-decoration-none hover-gold">Hubungi Kami</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <h5 class="text-white fw-bold mb-4">Metode Pembayaran</h5>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                        <div class="bg-white px-2 py-1 rounded"><i class="fa-brands fa-cc-visa text-dark fs-3"></i></div>
                        <div class="bg-white px-2 py-1 rounded"><i class="fa-brands fa-cc-mastercard text-dark fs-3"></i></div>
                        <div class="bg-white px-2 py-1 rounded"><i class="fa-brands fa-cc-paypal text-dark fs-3"></i></div>
                        <div class="bg-white px-2 py-1 rounded text-dark fw-bold fs-6 d-flex align-items-center">QRIS</div>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="text-light opacity-50 mb-0 small">&copy; 2026 EasyTix. All rights reserved.</p>
                <div class="d-flex gap-3 mt-3 mt-md-0 small">
                    <a href="#" class="text-light opacity-50 text-decoration-none">Privacy Policy</a>
                    <a href="#" class="text-light opacity-50 text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            background: #071120 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 1.5rem !important;
        }
        .swal2-title, .swal2-html-container {
            color: #fff !important;
        }
        .swal2-confirm {
            background: var(--gold-gradient) !important;
            color: #000 !important;
            border: none !important;
            font-weight: 700 !important;
            border-radius: 50px !important;
            padding: 12px 30px !important;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.2) !important;
        }
        .swal2-cancel {
            background: transparent !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            font-weight: 600 !important;
            border-radius: 50px !important;
            padding: 12px 30px !important;
        }
        /* Prevent SweetAlert DOM leakage */
        .swal2-input, .swal2-file, .swal2-textarea, .swal2-select, .swal2-radio, .swal2-checkbox {
            display: none;
        }
        .swal2-popup [style*="display: none"] {
            display: none !important;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // SweetAlert2 Global Styling & Defaults
            const swalPremium = Swal.mixin({
                customClass: {
                    popup: 'border border-light shadow-lg',
                },
                buttonsStyling: false
            });

            const confirmElements = document.querySelectorAll('[data-confirm]');
            confirmElements.forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const message = this.getAttribute('data-confirm');
                    
                    swalPremium.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (this.dataset.submitForm) {
                                document.getElementById(this.dataset.submitForm).submit();
                            } else if (this.tagName === 'A') {
                                window.location.href = this.href;
                            } else if (this.closest('form')) {
                                this.closest('form').submit();
                            }
                        }
                    });
                });
            });

            // Initialize AOS
            AOS.init({
                once: true,
                offset: 50,
                duration: 900,
                easing: 'ease-out-cubic',
            });

            // Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Swiper Slider
            var swiper = new Swiper(".concertSwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-next",
                    prevEl: ".swiper-prev",
                },
                breakpoints: {
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }
            });
        });
    </script>
</body>
</html>