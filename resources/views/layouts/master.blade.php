<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kaiadmin - Bootstrap 5 Admin Dashboard</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{ asset('assets/img/kaiadmin/favicon.ico')}}"
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
        .main-panel {
            background: #f4f7fa !important;
        }

        .card {
            border: none !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s ease !important;
            overflow: hidden !important;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05) !important;
        }

        .btn-primary {
            background: var(--premium-gold-grad) !important;
            border: none !important;
            color: #000 !important;
            font-weight: 700 !important;
            border-radius: 1rem !important;
            padding: 0.6rem 1.5rem !important;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.2) !important;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(244, 208, 63, 0.3) !important;
        }

        .sidebar {
            background: var(--premium-blue) !important;
            border-right: 1px solid rgba(255,255,255,0.05) !important;
        }

        .sidebar .sidebar-logo {
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            padding: 1.5rem 1rem !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item > a {
            margin: 8px 20px !important;
            border-radius: 12px !important;
            padding: 12px 15px !important;
            transition: all 0.3s ease !important;
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item > a:hover {
            background: rgba(255, 255, 255, 0.03) !important;
            transform: translateX(5px);
        }

        .sidebar[data-background-color="dark"] .nav > .nav-item > a p,
        .sidebar[data-background-color="dark"] .nav > .nav-item > a i {
            color: rgba(255, 255, 255, 0.5) !important;
            font-size: 1rem !important;
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

        .main-header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
            box-shadow: 0 4px 30px rgba(0,0,0,0.03) !important;
        }

        .navbar-header {
            background: transparent !important;
        }

        .topbar-user .profile-pic {
            padding: 5px 15px !important;
            background: #fff;
            border-radius: 50px;
            border: 1px solid #eee;
            transition: all 0.3s;
        }
        .topbar-user .profile-pic:hover {
            border-color: var(--premium-gold);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .avatar-sm {
            width: 35px !important;
            height: 35px !important;
        }

        .avatar-img {
            border: 2px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .nav-section .text-section {
            color: rgba(255, 255, 255, 0.3) !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
            font-weight: 800 !important;
            font-size: 0.7rem !important;
        }

        /* NEW PREMIUM UI COMPONENTS */
        .welcome-card {
            background: var(--premium-gold-grad);
            border-radius: 2rem !important;
            padding: 2.5rem;
            color: #000;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .stat-card-premium {
            border-radius: 1.5rem !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
        }
        
        .icon-wrapper-premium {
            width: 60px;
            height: 60px;
            border-radius: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .bg-soft-primary { background: rgba(20, 46, 94, 0.1); color: var(--premium-accent); }
        .bg-soft-info { background: rgba(0, 188, 212, 0.1); color: #00bcd4; }
        .bg-soft-success { background: rgba(67, 233, 123, 0.1); color: #2ecc71; }
        .bg-soft-warning { background: rgba(244, 208, 63, 0.1); color: #f39c12; }
        .bg-soft-secondary { background: rgba(104, 110, 118, 0.1); color: #686e76; }
        .bg-soft-danger { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }

        .table-premium thead th {
            background: transparent !important;
            color: #888 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border-bottom: 1px solid #eee !important;
        }
        
        .table-premium tbody td {
            padding: 1.2rem 0.75rem !important;
            vertical-align: middle !important;
        }
        
        .badge-pill {
            padding: 0.4rem 1rem !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
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

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    @yield('ExtraJS')   
  </body>
</html>
