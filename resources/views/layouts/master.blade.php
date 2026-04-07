<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>EasyTix</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{ asset('assets/img/logo_easytix_new.png')}}"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700", "Outfit:100,200,300,400,500,600,700,800,900"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css')}}" />
    @yield('ExtraCSS')
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css')}}" />
    
    <style>
        :root {
            --premium-blue: #071120;
            --premium-accent: #142E5E;
            --premium-gold: #F4D03F;
            --premium-gold-grad: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
            --premium-glass: rgba(255, 255, 255, 0.05);
            --premium-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Outfit', 'Public Sans', sans-serif !important;
            scroll-behavior: smooth;
        }

        @if(Auth::check())
        body, .wrapper {
            background-color: var(--premium-blue) !important;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(244, 208, 63, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(20, 46, 94, 0.6) 0%, transparent 50%) !important;
            color: #E0E6ED;
        }

        .main-panel {
            background: transparent !important;
        }

        /* Glass Cards */
        .card, .stat-card-premium {
            background: rgba(20, 46, 94, 0.35) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
            transition: all 0.3s ease !important;
            color: #E0E6ED !important;
            overflow: hidden !important;
        }

        .card:hover, .stat-card-premium:hover {
            transform: translateY(-5px);
            border-color: rgba(244, 208, 63, 0.2) !important;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4) !important;
            background: rgba(20, 46, 94, 0.45) !important;
        }

        .card-header, .card-title, .card-category { 
            color: #fff !important; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            background: transparent !important;
        }

        .card-body, .card-action {
            background: transparent !important;
        }

        /* Forms & Inputs to match Dark Glass */
        .form-control, .form-select, input[type="search"], input[type="text"], input[type="number"], input[type="date"], input[type="time"], input[type="file"], textarea {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            border-radius: 0.8rem;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(0, 0, 0, 0.4) !important;
            border-color: var(--premium-gold) !important;
            color: #fff !important;
            box-shadow: 0 0 0 0.2rem rgba(244, 208, 63, 0.25) !important;
        }

        .form-select option {
            background: var(--premium-blue);
            color: #fff;
        }

        select option {
            background: var(--premium-blue) !important;
            color: #fff !important;
        }

        /* Text Overrides */
        label, p, span, th, td, .text-muted, div.dataTables_info, .nav-item a, .breadcrumbs li a, .fw-medium {
            color: #cbd5e1 !important;
        }
        
        h1, h2, h3, h4, h5, h6, .fw-bold {
            color: #fff !important;
        }

        .text-primary, .text-info, .text-success, .text-warning, .text-danger, .text-dark {
            text-shadow: 0 0 10px rgba(0,0,0,0.5); /* Helps texts over dark bg */
        }
        
        /* Convert text-dark to bright text in Dark theme */
        .text-dark { color: #fff !important; }

        .text-primary {
            color: var(--premium-gold) !important;
        }

        /* Universal Links */
        a { color: var(--premium-gold); text-decoration: none; }
        a:hover { color: #fff; }

        /* Buttons */
        .btn {
            border-radius: 0.8rem !important;
            font-weight: 600 !important;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--premium-gold) !important;
            color: #000 !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.2) !important;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #F4D03F, #E67E22) !important;
            box-shadow: 0 8px 25px rgba(244, 208, 63, 0.4) !important;
            color: #000 !important;
        }

        .btn-danger {
            background: rgba(220, 53, 69, 0.2) !important;
            border: 1px solid rgba(220, 53, 69, 0.5) !important;
            color: #ff6b6b !important;
        }
        .btn-danger:hover {
            transform: scale(1.05);
            background: #dc3545 !important;
            color: #fff !important;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4) !important;
        }

        .btn-success {
            background: rgba(40, 167, 69, 0.2) !important;
            border: 1px solid rgba(40, 167, 69, 0.5) !important;
            color: #69f0ae !important;
        }
        .btn-success:hover {
            transform: scale(1.05);
            background: #28a745 !important;
            color: #fff !important;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4) !important;
        }

        .btn-info {
            background: rgba(23, 162, 184, 0.2) !important;
            border: 1px solid rgba(23, 162, 184, 0.5) !important;
            color: #4dd0e1 !important;
        }
        .btn-info:hover {
            transform: scale(1.05);
            background: #17a2b8 !important;
            color: #fff !important;
            box-shadow: 0 8px 25px rgba(23, 162, 184, 0.4) !important;
        }

        .btn-warning {
            background: rgba(255, 193, 7, 0.2) !important;
            border: 1px solid rgba(255, 193, 7, 0.5) !important;
            color: #ffb74d !important;
        }
        .btn-warning:hover {
            transform: scale(1.05);
            background: #ffc107 !important;
            color: #000 !important;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4) !important;
        }
        
        .btn-outline-danger {
            border: 1px solid rgba(220, 53, 69, 0.5) !important;
            color: #ff6b6b !important;
            background: transparent !important;
        }
        .btn-outline-danger:hover {
            background: #dc3545 !important;
            color: #fff !important;
        }

        .btn-outline-primary {
            border: 1px solid var(--premium-gold) !important;
            color: var(--premium-gold) !important;
            background: transparent !important;
        }
        .btn-outline-primary:hover {
            background: var(--premium-gold) !important;
            color: #000 !important;
        }

        /* Dropdown Glassmorphism */
        .dropdown-menu {
            background: rgba(7, 17, 32, 0.95) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8) !important;
            border-radius: 12px;
            padding: 0.5rem 0;
        }
        
        .dropdown-menu::before {
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .dropdown-menu::after {
            border-bottom-color: rgba(7, 17, 32, 0.95) !important;
        }

        .dropdown-item {
            color: #E0E6ED !important;
            transition: all 0.2s ease;
            padding: 10px 20px !important;
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }
        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            margin: 0.5rem 0;
        }
        
        /* User Profile Dropdown Specifics */
        .dropdown-user .user-box { padding: 15px !important; }
        .dropdown-user .user-box .u-text h4 { color: #fff !important; margin-bottom: 5px; }
        .dropdown-user .user-box .u-text .text-muted { color: #a0aec0 !important; font-size: 13px !important; }
        
        /* Notifications Dropdown Specifics */
        .notif-box .dropdown-title {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.05) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            font-weight: 600;
        }
        .notif-box .notif-center a { border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
        .notif-box .notif-center a:hover { background: rgba(255,255,255,0.05) !important; }
        .notif-box .notif-content .block { color: #fff !important; }
        .notif-box .see-all {
            background: rgba(0, 210, 255, 0.1) !important;
            color: #00d2ff !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 15px !important;
        }
        .notif-box .see-all:hover { background: rgba(0, 210, 255, 0.2) !important; }

        /* Glass Badges Configuration */
        .badge {
            padding: 0.5rem 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 6px;
            backdrop-filter: blur(4px);
        }
        .badge.bg-success, .badge-success {
            background: rgba(46, 204, 113, 0.15) !important;
            color: #2ecc71 !important;
            border: 1px solid rgba(46, 204, 113, 0.4) !important;
        }
        .badge.bg-warning, .badge-warning {
            background: rgba(244, 208, 63, 0.15) !important;
            color: #F4D03F !important;
            border: 1px solid rgba(244, 208, 63, 0.4) !important;
        }
        .badge.bg-danger, .badge-danger {
            background: rgba(231, 76, 60, 0.15) !important;
            color: #ff6b6b !important;
            border: 1px solid rgba(231, 76, 60, 0.4) !important;
        }
        .badge.bg-info, .badge-info {
            background: rgba(0, 210, 255, 0.15) !important;
            color: #00d2ff !important;
            border: 1px solid rgba(0, 210, 255, 0.4) !important;
        }
        .badge.bg-primary, .badge-primary {
            background: rgba(161, 140, 209, 0.15) !important;
            color: #a18cd1 !important;
            border: 1px solid rgba(161, 140, 209, 0.4) !important;
        }
        .badge.bg-light, .badge-light {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        /* Universal Forms Glassmorphism */
        .form-control, .form-select, .form-control:disabled, .form-control[readonly] {
            background: rgba(7, 17, 32, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(7, 17, 32, 0.7) !important;
            border-color: var(--premium-gold) !important;
            box-shadow: 0 0 15px rgba(244, 208, 63, 0.2) !important;
            color: #fff !important;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }
        
        .sidebar {
            background: rgba(7, 17, 32, 0.6) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-right: 1px solid rgba(255,255,255,0.05) !important;
            box-shadow: 4px 0 20px rgba(0,0,0,0.3) !important;
        }

        .sidebar .sidebar-logo {
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            background: transparent !important;
            padding: 1.5rem 1rem !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item > a {
            margin: 8px 20px !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
            color: #a0aec0 !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item > a:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            transform: translateX(5px);
            color: #fff !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item > a p,
        .sidebar[data-background-color="dark"] .nav > .nav-item > a i {
            color: inherit !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item.active > a {
            background: var(--premium-gold-grad) !important;
            box-shadow: 0 8px 20px rgba(244, 208, 63, 0.2) !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item.active > a i,
        .sidebar[data-background-color="dark"] .nav > .nav-item.active > a p {
            color: #000 !important;
            font-weight: 800 !important;
        }

        /* Header Glass */
        .main-header, .main-header[data-background-color] {
            background: rgba(7, 17, 32, 0.5) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1) !important;
        }

        .navbar-header { background: transparent !important; }

        .topbar-user .profile-pic {
            border: 2px solid rgba(244, 208, 63, 0.5);
            background: rgba(255,255,255,0.1) !important;
        }
        
        .topbar-user .profile-pic:hover {
            border-color: var(--premium-gold);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 208, 63, 0.2);
        }

        /* Premium UI Components */
        .welcome-card {
            background: var(--premium-gold-grad) !important;
            border: none !important;
            border-radius: 2rem !important;
            padding: 2.5rem !important;
            margin-bottom: 2rem !important;
            position: relative;
            overflow: hidden;
            color: #000 !important;
        }
        
        .btn-dark {
            background: #071120 !important;
            color: #fff !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }
        .btn-dark:hover {
            background: #142E5E !important;
            color: #E0E6ED !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.5) !important;
        }

        .btn-white {
            background: rgba(255,255,255,0.9) !important;
            color: #000 !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
        }
        .btn-white:hover {
            background: #fff !important;
            color: #000 !important;
            box-shadow: 0 8px 25px rgba(255,255,255,0.3) !important;
        }
        .welcome-card h1, .welcome-card h2, .welcome-card h3, .welcome-card p, .welcome-card span { color: #000 !important; }

        .footer {
            background: transparent !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            color: #cbd5e1 !important;
            padding: 1rem 0;
        }

        .icon-wrapper-premium {
            width: 60px;
            height: 60px;
            border-radius: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Soft Badges for Dark Mode */
        .bg-soft-primary { background: rgba(244, 208, 63, 0.15) !important; color: var(--premium-gold) !important; }
        .bg-soft-info { background: rgba(0, 188, 212, 0.15) !important; color: #4dd0e1 !important; }
        .bg-soft-success { background: rgba(67, 233, 123, 0.15) !important; color: #69f0ae !important; }
        .bg-soft-warning { background: rgba(240, 173, 78, 0.15) !important; color: #ffb74d !important; }
        .bg-soft-secondary { background: rgba(255, 255, 255, 0.1) !important; color: #e2e8f0 !important; }
        .bg-soft-danger { background: rgba(231, 76, 60, 0.15) !important; color: #ff8a80 !important; }

        /* Tables in Dark Glass */
        .table {
            color: #e2e8f0 !important;
            --bs-table-bg: transparent !important;
            --bs-table-striped-bg: rgba(255, 255, 255, 0.02) !important;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .table-premium thead th, .table thead th {
            background: rgba(0,0,0,0.2) !important;
            color: var(--premium-gold) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-top: none !important;
        }
        
        .table-premium tbody td, .table tbody td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            color: #e2e8f0 !important;
            background: transparent !important;
        }

        div.dataTables_wrapper div.dataTables_length select,
        div.dataTables_wrapper div.dataTables_filter input {
            background-color: rgba(0,0,0,0.2) !important;
            color: #fff !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 5px;
        }

        /* Pagination in Dark Glass */
        .page-item .page-link {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }
        .page-item.active .page-link {
            background: var(--premium-gold) !important;
            border-color: var(--premium-gold) !important;
            color: #000 !important;
        }

        .badge-pill {
            padding: 0.4rem 1rem !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            backdrop-filter: blur(5px);
        }

        /* Animations */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @endif
    </style>
  </head>
  <body>
    <div class="wrapper">
      @include('layouts.sidebar')

      <div class="main-panel">
        @include('layouts.header')

        @yield('content')
       
        @include('layouts.footer')
        
      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Google Maps Plugin -->
    <script src="{{ asset('assets/js/plugin/gmaps/gmaps.js') }}"></script>

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

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
            background: var(--premium-gold-grad) !important;
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
        /* When Swal actually needs them, it overrides inline or via classes. But if global css broke it, we force hide if they have display:none inline */
        .swal2-popup [style*="display: none"] {
            display: none !important;
        }
    </style>
    <script>
        // SweetAlert2 Global Styling & Defaults
        window.swalPremium = Swal.mixin({
            customClass: {
                popup: 'border border-light shadow-lg',
            },
            buttonsStyling: false // Let our CSS handle it
        });

        // Handle Global Flash Messages from Session
        @if(session('success'))
            swalPremium.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('status'))
            @php
                $statusMsg = session('status');
                if ($statusMsg === 'profile-updated') $statusMsg = 'Profil berhasil diperbarui.';
                elseif ($statusMsg === 'password-updated') $statusMsg = 'Password berhasil diperbarui.';
                elseif ($statusMsg === 'verification-link-sent') $statusMsg = 'Link verifikasi baru telah dikirim ke email Anda.';
            @endphp
            swalPremium.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ $statusMsg }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            swalPremium.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
            });
        @endif

        @if(session('warning'))
            swalPremium.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: "{{ session('warning') }}",
            });
        @endif

        @if(session('info'))
            swalPremium.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('info') }}",
            });
        @endif

        // Global Confirmation Handler for Links and Forms
        document.addEventListener('DOMContentLoaded', function () {
            // Find all elements with data-confirm attribute
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
        });
    </script>
    
    @yield('ExtraJS')   
  </body>
</html>
