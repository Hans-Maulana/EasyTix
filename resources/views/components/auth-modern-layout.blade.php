<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.85)), 
                        url('{{ asset("assets/img/easytix_login_bg.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #0f172a;
            overflow-x: hidden;
        }

        .auth-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            position: relative;
            box-sizing: border-box;
            width: 100%;
            overflow: hidden;
        }





        /* Decorative blobs */
        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: 0;
            opacity: 0.4;
            animation: pulse 10s infinite alternate;
        }

        .blob-1 { top: -100px; left: -100px; }
        .blob-2 { bottom: -100px; right: -100px; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); }

        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.2); }
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo img {
            height: 64px;
            margin: 0 auto;
            filter: drop-shadow(0 0 15px rgba(59, 130, 246, 0.4));
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .auth-subtitle {
            color: #94a3b8;
            font-size: 15px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            margin-left: 4px;
        }

        .form-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 14px !important;
            padding: 14px 18px !important;
            color: #ffffff !important;
            font-size: 15px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            outline: none !important;
        }

        .form-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
            background: rgba(15, 23, 42, 0.8) !important;
            transform: translateY(-1px);
        }

        .form-error {
            color: #fb7185;
            font-size: 13px;
            margin-top: 6px;
            margin-left: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .auth-footer-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 32px;
            font-size: 14px;
        }

        .auth-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
            cursor: pointer;
            user-select: none;
        }

        .auth-checkbox input {
            width: 18px;
            height: 18px;
            border-radius: 4px !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            accent-color: #3b82f6;
            cursor: pointer;
            filter: grayscale(1) brightness(1.5) contrast(1.2); /* Make it better blend with dark theme */
            transition: all 0.2s;
        }

        .auth-checkbox input:checked {
            filter: none;
        }


        .auth-link {
            color: #60a5fa;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .auth-link:hover {
            color: #93c5fd;
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
            filter: brightness(1.1);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .register-text {
            text-align: center;
            margin-top: 32px;
            color: #94a3b8;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
