<x-mail::message>
# 🎫 E-Ticket Anda Telah Diterbitkan!

Halo **{{ $userName }}**,

Terima kasih telah melakukan pembelian tiket melalui **EasyTix**. Pembayaran Anda telah berhasil diproses.

---

## Detail Pesanan

<x-mail::table>
| Info | Detail |
|:-----|:-------|
| **No. Pesanan** | {{ $order->id }} |
| **Metode Pembayaran** | {{ $order->payment_method ?? 'QRIS' }} |
| **Total** | Rp {{ number_format($order->total_amount, 0, ',', '.') }} |
| **Tanggal** | {{ $order->created_at->format('d M Y, H:i') }} |
</x-mail::table>

---

## Tiket Anda

@foreach ($orderItems as $index => $item)
**Tiket #{{ $index + 1 }}**
- **Event:** {{ $item['event_name'] ?? '-' }}
- **Tipe:** {{ $item['ticket_type'] ?? '-' }}
- **Pemilik:** {{ $item['owner_name'] ?? '-' }}
- **Kode Tiket:** `{{ $item['ticket_code'] ?? '-' }}`

<div style="text-align: center; margin-top: 10px;">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($item['ticket_code'] ?? '') }}" alt="QR Code" width="150" height="150" style="background-color: #ffffff; padding: 5px; border-radius: 5px;">
</div>

@endforeach

> **Catatan:** Jika QR code tidak muncul di atas, silakan cek file lampiran di email ini. Tunjukkan QR Code saat memasuki venue.

<x-mail::button :url="route('user.myTickets')" color="primary">
Lihat Tiket Saya
</x-mail::button>

---

⚠️ **Penting:**
- Simpan email ini sebagai bukti pembelian.
- Setiap QR Code hanya berlaku untuk satu kali masuk.
- Jangan bagikan QR Code Anda kepada orang lain.

Terima kasih,<br>
**{{ config('app.name', 'EasyTix') }}**
</x-mail::message>
