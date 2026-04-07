<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Anda - EasyTix</title>
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
        .order-details {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        .order-details table {
            width: 100%;
        }
        .order-details td {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .order-details tr:last-child td {
            border-bottom: none;
        }
        .ticket-box {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            background-color: #ffffff;
        }
        .ticket-info {
            text-align: left;
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.8;
        }
        .qr-wrapper {
            background-color: #ffffff;
            padding: 10px;
            display: inline-block;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
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
        .warning-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-top: 10px;
            font-size: 13px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h2 style="color: #F4D03F; margin: 0; font-size: 28px; letter-spacing: 1px;">EasyTix</h2>
        </div>

        <!-- Body Content -->
        <div class="content">
            <h1>🎫 E-Ticket Anda Telah Diterbitkan!</h1>
            <p>Halo <strong>{{ $userName }}</strong>,</p>
            <p>Terima kasih telah melakukan pembelian tiket event melalui EasyTix. Pembayaran Anda telah berhasil diproses sepenuhnya.</p>
            
            <div class="order-details">
                <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; color: #071120;">Detail Pesanan</h3>
                <table cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="color: #64748b; font-weight: 600; width: 40%;">No. Pesanan</td>
                        <td style="font-weight: 600; color: #0f172a;">{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Metode Pembayaran</td>
                        <td style="color: #0f172a;">{{ $order->payment_method ?? 'QRIS' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Total</td>
                        <td style="font-weight: bold; color: #0f172a;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Waktu Transaksi</td>
                        <td style="color: #0f172a;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>

            <h3 style="color: #071120; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px;">Daftar E-Ticket Anda</h3>

            @foreach ($orderItems as $index => $item)
            <div class="ticket-box">
                <div style="background: #0f172a; color: #fff; padding: 5px 15px; border-radius: 20px; display: inline-block; font-size: 12px; font-weight: bold; margin-bottom: 15px;">
                    TIKET #{{ $index + 1 }}
                </div>
                
                <div class="ticket-info">
                    <strong>Event:</strong> &nbsp;{{ $item['event_name'] ?? '-' }}<br>
                    <strong>Tipe Tiket:</strong> &nbsp;{{ $item['ticket_type'] ?? '-' }}<br>
                    <strong>Pemilik:</strong> &nbsp;{{ $item['owner_name'] ?? '-' }}<br>
                    <strong>Kode Tiket:</strong> &nbsp;<span style="color: #3b82f6; font-family: monospace; font-size: 15px;">{{ $item['ticket_code'] ?? '-' }}</span>
                </div>
                
                <div class="qr-wrapper">
                    <!-- Backup QR code generated online via API if local CID fails in some email clients -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($item['ticket_code'] ?? '') }}" alt="QR Code" width="180" height="180" style="display: block;">
                </div>
                <div style="font-size: 11px; color: #94a3b8; margin-top: 8px;">Scan QR Code ini di pintu masuk venue.</div>
            </div>
            @endforeach

            <div class="warning-box">
                <strong>⚠️ Harap Diperhatikan:</strong><br>
                1. Tunjukkan QR Code dari email ini atau dari menu "Tiket Saya" di aplikasi saat memasuki venue.<br>
                2. Setiap QR Code bersifat unik dan hanya berlaku untuk SATU KALI scan.<br>
                3. Jangan mengambil screenshot dan membagikan QR Code Anda kepada siapapun di media sosial.
            </div>

            <div class="btn-container">
                <a href="{{ route('user.myTickets') }}" class="btn">Lihat Tiket di Website</a>
            </div>

            <p style="margin-top: 40px; margin-bottom: 0;">Terima kasih atas kepercayaannya,<br><strong>Tim EasyTix</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} EasyTix Indonesia. Hak Cipta Dilindungi.</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak menduplikasi, menjual, atau meminjamkan tiket Anda.</p>
        </div>
    </div>
</body>
</html>
