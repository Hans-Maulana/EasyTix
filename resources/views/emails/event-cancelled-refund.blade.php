<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Tiket - EasyTix</title>
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
            color: #ef4444;
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
        }
        p {
            margin-bottom: 20px;
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
        .warning-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin-top: 10px;
            font-size: 13px;
            color: #991b1b;
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
            <h1>⚠️ Pemberitahuan: Event Dibatalkan & Refund Dana</h1>
            <p>Halo <strong>{{ $order->user->name }}</strong>,</p>
            <p>Kami memohon maaf yang sebesar-besarnya. Dengan berat hati kami informasikan bahwa event <strong>{{ $event->name }}</strong> yang Anda pesan telah dibatalkan oleh pihak penyelenggara/admin.</p>
            <p>Sebagai bentuk tanggung jawab kami, seluruh dana yang telah Anda bayarkan untuk pesanan ini telah kami proses untuk <strong>Refund (Pengembalian Dana)</strong>.</p>
            
            <div class="order-details">
                <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; color: #071120;">Rincian Pengembalian Dana</h3>
                <table cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="color: #64748b; font-weight: 600; width: 40%;">No. Pesanan</td>
                        <td style="font-weight: 600; color: #0f172a;">{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Metode Refund</td>
                        <td style="color: #0f172a;">{{ $order->payment_method ?? 'Metode Pembayaran Awal' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Total Refund</td>
                        <td style="font-weight: bold; color: #ef4444; font-size: 18px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <p>Dana akan dikembalikan melalui metode pembayaran yang Anda gunakan saat bertransaksi. Estimasi waktu pencairan dana bergantung pada kebijakan masing-masing penyedia pembayaran (biasanya 1-7 hari kerja).</p>

            <div class="warning-box">
                <strong>Catatan Penting:</strong><br>
                1. Tiket yang Anda miliki untuk event ini sudah tidak berlaku lagi.<br>
                2. Anda tidak perlu melakukan tindakan apapun, sistem kami memproses refund secara otomatis.<br>
                3. Hubungi support@easytix.id jika Anda memiliki pertanyaan lebih lanjut.
            </div>

            <p style="margin-top: 40px; margin-bottom: 0;">Kami berharap dapat melayani Anda kembali di event menarik lainnya.<br><strong>Salam hangat, Tim EasyTix</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} EasyTix Indonesia. Hak Cipta Dilindungi.</p>
            <p>Email ini dikirim secara otomatis. Mohon simpan bukti No. Pesanan Anda jika sewaktu-waktu dibutuhkan.</p>
        </div>
    </div>
</body>
</html>
