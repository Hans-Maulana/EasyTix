@extends('layouts.master')

@section('ExtraCSS')
<style>
    .card-stats-premium {
        border-radius: 2rem !important;
        border: none !important;
        background: #fff;
        box-shadow: 0 15px 35px rgba(0,0,0,0.03) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .card-stats-premium:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.08) !important;
    }
    .icon-box-premium {
        width: 70px;
        height: 70px;
        border-radius: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }
    .bg-gradient-p { background: linear-gradient(135deg, #142E5E 0%, #071120 100%); color: white; }
    .bg-gradient-g { background: var(--premium-gold-grad); color: #000; }
    .bg-gradient-b { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    
    .chart-container-premium {
        background: #fff;
        border-radius: 2rem;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    
    .recent-table-premium {
        border-radius: 2rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .table thead th {
        background: #fcfcfc !important;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        color: #999;
        padding: 1.5rem 1rem !important;
        border: none !important;
    }
    .table tbody td {
        padding: 1.5rem 1rem !important;
        border-bottom: 1px solid #f8f8f8 !important;
        vertical-align: middle !important;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header mb-4">
            <h3 class="fw-bold mb-0">Analytics & Sales Hub</h3>
            <ul class="breadcrumbs mb-0">
                <li class="nav-home"><a href="{{ route('organizer.dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Organizer</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Laporan Penjualan</a></li>
            </ul>
        </div>

        <!-- Sales Chart Section -->
        <div class="row mb-4">
            <div class="col-md-8 fade-in-up" style="animation-delay: 0.1s;">
                <div class="card chart-container-premium h-100 border-0">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-line text-primary me-2"></i> Tren Penjualan</h4>
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-bold border">Last 7 Days</span>
                    </div>
                    <div class="card-body">
                        <div style="height: 350px">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.2s;">
                <div class="card chart-container-premium h-100 border-0">
                    <div class="card-header bg-transparent border-0 text-center">
                        <h4 class="fw-bold mb-0 text-dark">Revenue Mix</h4>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div style="height: 280px; width: 100%;">
                            <canvas id="revenuePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Row -->
        <div class="row mb-5">
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.3s;">
                <div class="card card-stats-premium p-4">
                    <div class="icon-box-premium bg-gradient-g">
                        <i class="fas fa-coins"></i>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Revenue</p>
                    <h2 class="fw-bold text-dark mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    <small class="text-success fw-bold mt-2 d-block"><i class="fas fa-arrow-up me-1"></i> 15% Increase</small>
                </div>
            </div>
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.4s;">
                <div class="card card-stats-premium p-4">
                    <div class="icon-box-premium bg-gradient-b">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Tickets Sold</p>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalTickets }} <span class="fs-6 opacity-50 fw-normal">Tickets</span></h2>
                    <small class="text-info fw-bold mt-2 d-block"><i class="fas fa-check-circle me-1"></i> Global reach</small>
                </div>
            </div>
            <div class="col-md-4 fade-in-up" style="animation-delay: 0.5s;">
                <div class="card card-stats-premium p-4 shadow">
                    <div class="icon-box-premium bg-gradient-p shadow">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Net Orders</p>
                    <h2 class="fw-bold text-dark mb-0">{{ $orders->count() }} <span class="fs-6 opacity-50 fw-normal">Transactions</span></h2>
                    <small class="text-muted fw-bold mt-2 d-block">Lifetime activity</small>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-md-12 fade-in-up" style="animation-delay: 0.6s;">
                <div class="recent-table-premium">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center p-4 border-0">
                        <h4 class="fw-bold mb-0">Daftar Transaksi</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-round border btn-sm shadow-sm">Export PDF</button>
                            <button class="btn btn-light btn-round border btn-sm shadow-sm">CSV</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0" id="recent-sales-table">
                                <thead>
                                    <tr>
                                        <th>ID Order</th>
                                        <th>Event Name</th>
                                        <th>Attendee Name</th>
                                        <th>Amount</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                        <td>{{ $order->event->name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2"><span class="avatar-title rounded-circle bg-premium-blue text-white fw-bold">{{ substr($order->user->name, 0, 1) }}</span></div>
                                                {{ $order->user->name }}
                                            </div>
                                        </td>
                                        <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="text-muted small">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
<script>
$(document).ready(function() {
    $('#recent-sales-table').DataTable({
        "pageLength": 10,
        "order": [[4, "desc"]],
        "dom": '<"p-4 d-flex justify-content-between align-items-center"f>rt<"p-4 d-flex justify-content-between align-items-center"ip>',
        "language": {
            "search": "",
            "searchPlaceholder": "Cari transaksi..."
        }
    });

    // Sales Line Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(20, 46, 94, 0.4)');
    gradient.addColorStop(1, 'rgba(20, 46, 94, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($chartData) !!},
                borderColor: '#142E5E',
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#142E5E',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#071120',
                    titleFont: { size: 14, family: 'Outfit' },
                    bodyFont: { size: 16, family: 'Outfit', weight: 'bold' },
                    padding: 15,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { display: true, drawBorder: false, color: '#f0f0f0' },
                    ticks: {
                        font: { family: 'Outfit' },
                        callback: value => 'Rp ' + (value/1000000) + 'M'
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Revenue Pie Chart
    const ctxPie = document.getElementById('revenuePieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($eventStats, 'name')) !!},
            datasets: [{
                data: {!! json_encode(array_column($eventStats, 'revenue')) !!},
                backgroundColor: ['#F4D03F', '#142E5E', '#E67E22', '#3498db', '#2ecc71'],
                borderWidth: 8,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: 'Outfit', size: 12 }
                    }
                }
            }
        }
    });
});
</script>
@endsection
