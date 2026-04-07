<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - EasyTix</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaebed;
        }
        .header {
            background-color: #071120;
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
            font-size: 16px;
        }
        h1 {
            color: #071120;
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
        }
        p {
            margin-bottom: 20px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
        .footer {
            background-color: #fafbfc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eaebed;
        }
        .footer p {
            color: #888888;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }
        .link-fallback {
            word-break: break-all;
            color: #142E5E;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <!-- Ensure EasyTix Logo is visible in emails -->
            <h2 style="color: #F4D03F; margin: 0; font-size: 28px; letter-spacing: 1px;">EasyTix</h2>
        </div>

        <!-- Body Content -->
        <div class="content">
            <h1>Selamat Datang, {{ $user->name }}!</h1>
            <p>Terima kasih telah mendaftar di EasyTix, platform tiket event dan konser #1 di Indonesia.</p>
            <p>Satu langkah lagi! Silakan verifikasi alamat email Anda agar dapat menikmati semua fitur kami, termasuk membeli tiket artis favorit Anda.</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Verifikasi Email Saya</a>
            </div>

            <p>Jika Anda tidak merasa mendaftar di EasyTix, Anda dapat mengabaikan email ini dengan aman.</p>
            
            <p style="margin-top: 40px;">Salam hangat,<br><strong>Tim EasyTix</strong></p>

            <hr style="border: 0; border-top: 1px solid #eaebed; margin: 30px 0;">
            
            <p style="font-size: 13px; color: #666;">
                Jika Anda kesulitan mengklik tombol "Verifikasi Email Saya", silakan copy dan paste URL berikut ke browser Anda:<br>
                <a href="{{ $url }}" class="link-fallback">{{ $url }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} EasyTix Indonesia. Hak Cipta Dilindungi.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
