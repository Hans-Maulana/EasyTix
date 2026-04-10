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
        Total Pendapatan Bulanan: Rp {{ number_format($totalAll, 0, ',', '.') }}
    </div>

    <hr style="margin-top: 40px; border: 1px solid #eee;">

    <h2 style="margin-top: 30px;">Detail Penjualan Per-Event</h2>
    @foreach($eventReports as $eventId => $schedules)
        <div style="margin-bottom: 30px;">
            <h3 style="color: #444; border-bottom: 2px solid #f2f2f2; padding-bottom: 5px;">{{ $schedules->first()->event_name }}</h3>
            <table style="margin-top: 10px;">
                <thead>
                    <tr style="background-color: #fafafa;">
                        <th>Jadwal</th>
                        <th style="text-align: center;">Tiket Terjual</th>
                        <th style="text-align: right;">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($schedule->event_date)->format('d M Y') }} 
                                ({{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} WIB)
                            </td>
                            <td style="text-align: center;">{{ number_format($schedule->tickets_sold) }}</td>
                            <td style="text-align: right;">Rp {{ number_format($schedule->revenue ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td>TOTAL</td>
                        <td style="text-align: center;">{{ number_format($schedules->sum('tickets_sold')) }}</td>
                        <td style="text-align: right;">Rp {{ number_format($schedules->sum('revenue'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach

    <div style="margin-top: 50px; font-size: 0.8rem; color: #888;">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
