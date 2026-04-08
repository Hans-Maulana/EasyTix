@extends('layouts.master')

@section('ExtraCSS')
<style>
    .card-report {
        border-radius: 1.5rem !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        background: rgba(20, 46, 94, 0.25) !important;
        backdrop-filter: blur(16px) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--premium-blue);
    }
    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #888;
    }
    .bg-light-primary { background: rgba(20, 46, 94, 0.05); }
    .bg-light-success { background: rgba(40, 167, 69, 0.05); }
    .bg-light-warning { background: rgba(255, 193, 7, 0.05); }
    .bg-light-info { background: rgba(23, 162, 184, 0.05); }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Laporan Analitik Admin</h3>
                <h6 class="op-7 mb-2">Pantau performa penjualan dan tingkat kehadiran secara menyeluruh.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.downloadMonthlyReport') }}" class="btn btn-danger btn-round">
                    <i class="fas fa-file-pdf me-2"></i> Unduh PDF Laporan Bulanan
                </a>
            </div>
        </div>

        <!-- Summary Row -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round card-report">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small bg-soft-primary" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category stat-label">Tiket Terjual</p>
                                    <h4 class="card-title stat-value">{{ number_format($totalTicketsSold) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round card-report">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small bg-soft-success" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category stat-label">Total Pendapatan</p>
                                    <h4 class="card-title stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round card-report">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small bg-soft-warning" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category stat-label">Total Kehadiran</p>
                                    <h4 class="card-title stat-value">{{ $attendanceStats->where('status', 'used')->first()->count ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round card-report">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small bg-soft-info" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category stat-label">Rate Kehadiran</p>
                                    <h4 class="card-title stat-value">{{ $attendanceRate }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Penjualan per Kategori Chart -->
            <div class="col-md-8">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Penjualan & Sisa Kuota per Kategori</div>
                        <p class="text-muted small">Perbandingan tiket terjual dengan kapasitas kategori.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kehadiran Chart -->
            <div class="col-md-4">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Status Kehadiran</div>
                        <p class="text-muted small">Validasi tiket vs Penggunaan.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="mt-4">
                            @foreach($attendanceStats as $stat)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small text-uppercase fw-bold">{{ $stat->status }}</span>
                                <span class="fw-bold">{{ $stat->count }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Revenue per Kategori -->
            <div class="col-md-6">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Penjualan per Tipe Tiket</div>
                        <p class="text-muted small">Distribusi nilai transaksi berdasarkan tipe tiket.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container">
                            <canvas id="ticketTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabel Data -->
            <div class="col-md-6">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Detail Kuota Tiket</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-premium">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-center">Kapasitas</th>
                                        <th class="text-center">Terjual</th>
                                        <th class="text-center">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotaStats as $quota)
                                    <tr>
                                        <td class="fw-bold">{{ $quota->category_name }}</td>
                                        <td class="text-center">{{ number_format($quota->total_capacity) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-success text-success px-3">{{ number_format($ticketTypeStats->firstWhere('category_name', $quota->category_name)->total_sold ?? 0) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $quota->remaining < 10 ? 'bg-soft-danger text-danger' : 'bg-soft-primary text-primary' }} px-3">{{ number_format($quota->remaining) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tren Penjualan Bulanan -->
            <div class="col-md-12">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Tren Penjualan Bulanan</div>
                        <p class="text-muted small">Grafik pendapatan dari bulan ke bulan.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Pendapatan per Kategori Event -->
            <div class="col-md-6">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Pendapatan per Kategori Event</div>
                        <p class="text-muted small">Berdasarkan kategori utama event.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="categoryRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Laporan Penjualan Perbulan -->
            <div class="col-md-6">
                <div class="card card-report">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="card-title fw-bold">Laporan Penjualan Perbulan</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-premium">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th class="text-center">Pesanan</th>
                                        <th class="text-end">Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlySales as $sale)
                                    <tr>
                                        <td class="fw-bold">{{ \Carbon\Carbon::parse($sale->month)->format('F Y') }}</td>
                                        <td class="text-center">{{ $sale->order_count }}</td>
                                        <td class="text-end fw-bold text-success">Rp {{ number_format($sale->revenue, 0, ',', '.') }}</td>
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
    // Data dari Controller
    const ticketTypeStats = @json($ticketTypeStats);
    const quotaStats = @json($quotaStats);
    const attendanceStats = @json($attendanceStats);
    const categoryRevenue = @json($categoryRevenue);
    const monthlySales = @json($monthlySales);

    // 1. Category Chart (Sold vs Remaining)
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'bar',
        data: {
            labels: quotaStats.map(item => item.category_name),
            datasets: [
                {
                    label: 'Terjual',
                    data: quotaStats.map(item => {
                        const stat = ticketTypeStats.find(s => s.category_name === item.category_name);
                        return stat ? stat.total_sold : 0;
                    }),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 8,
                },
                {
                    label: 'Sisa Kuota',
                    data: quotaStats.map(item => item.remaining),
                    backgroundColor: 'rgba(226, 232, 240, 0.9)',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Attendance Chart (Doughnut)
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctxAttendance, {
        type: 'doughnut',
        data: {
            labels: attendanceStats.map(item => item.status.toUpperCase()),
            datasets: [{
                data: attendanceStats.map(item => item.count),
                backgroundColor: [
                    '#10b981', '#f59e0b', '#ef4444', '#3b82f6'
                ],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 3. Ticket Type Chart
    const ctxTicketType = document.getElementById('ticketTypeChart').getContext('2d');
    new Chart(ctxTicketType, {
        type: 'bar',
        data: {
            labels: ticketTypeStats.map(item => item.category_name),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: ticketTypeStats.map(item => item.total_revenue),
                backgroundColor: 'rgba(20, 46, 94, 0.7)',
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { callback: (v) => 'Rp ' + v.toLocaleString() }
                }
            }
        }
    });

    // 4. Category Revenue Chart
    const ctxCatRev = document.getElementById('categoryRevenueChart').getContext('2d');
    new Chart(ctxCatRev, {
        type: 'pie',
        data: {
            labels: categoryRevenue.map(item => item.category_name),
            datasets: [{
                data: categoryRevenue.map(item => item.revenue),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    // 5. Monthly Sales Chart
    const ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
    // Sinkronkan data agar urutannya benar (Aura - Desc ke Asc untuk grafik)
    const chartMonthlyData = [...monthlySales].reverse();
    
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: chartMonthlyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
            }),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartMonthlyData.map(item => item.revenue),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { callback: (v) => 'Rp ' + v.toLocaleString() }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
