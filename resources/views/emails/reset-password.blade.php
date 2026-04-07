<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - EasyTix</title>
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
            background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
            color: #000000 !important;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(244, 208, 63, 0.3);
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
            <h1>Halo, {{ $user->name }}!</h1>
            <p>Kami menerima permintaan untuk mereset password akun EasyTix Anda. Jika Anda memang melakukan permintaan ini, silakan klik tombol di bawah ini untuk membuat password baru:</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Reset Password Sekarang</a>
            </div>

            <p style="color: #666; font-size: 14px;"><strong>Penting:</strong> Link reset password ini hanya valid selama {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60) }} menit demi keamanan akun Anda.</p>
            <p>Jika Anda tidak pernah meminta reset password, Anda dapat mengabaikan email ini dengan aman. Akun Anda tetap terlindungi.</p>
            
            <p style="margin-top: 40px;">Salam hangat,<br><strong>Tim EasyTix</strong></p>

            <hr style="border: 0; border-top: 1px solid #eaebed; margin: 30px 0;">
            
            <p style="font-size: 13px; color: #666;">
                Jika Anda kesulitan mengklik tombol "Reset Password Sekarang", silakan copy dan paste URL berikut ke browser Anda:<br>
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
