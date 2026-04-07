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

@endforeach

> **Catatan:** File QR Code tiket terlampir di email ini. Tunjukkan QR Code saat memasuki venue.

<x-mail::button :url="config('app.url') . '/my-tickets'" color="primary">
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
