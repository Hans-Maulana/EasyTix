<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .total { font-weight: bold; font-size: 1.2rem; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan Bulanan</h1>
        <p>EasyTix - Managed Ticketing System</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Pesanan</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAll = 0; @endphp
            @foreach($monthlySales as $sale)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sale->month)->format('F Y') }}</td>
                    <td>{{ $sale->order_count }}</td>
                    <td>Rp {{ number_format($sale->revenue, 0, ',', '.') }}</td>
                </tr>
                @php $totalAll += $sale->revenue; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pendapatan Keseluruhan: Rp {{ number_format($totalAll, 0, ',', '.') }}
    </div>

    <div style="margin-top: 50px; font-size: 0.8rem; color: #888;">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
