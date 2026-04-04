@extends('layouts.master')

@section('ExtraCSS')
<style>
    .card-report {
        border-radius: 1.5rem !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        background: #fff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
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
                <button onclick="window.print()" class="btn btn-primary btn-round me-2">
                    <i class="fas fa-print me-2"></i> Cetak Laporan
                </button>
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
                                    <h4 class="card-title stat-value">{{ $ticketStats->sum('total_sold') }}</h4>
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
                                    <h4 class="card-title stat-value">Rp {{ number_format($ticketStats->sum('total_revenue'), 0, ',', '.') }}</h4>
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
                                    <h4 class="card-title stat-value">{{ $attendanceStats->where('status', 'use')->first()->count ?? 0 }}</h4>
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
                                    @php
                                        $total = $attendanceStats->sum('count');
                                        $used = $attendanceStats->where('status', 'use')->first()->count ?? 0;
                                        $rate = $total > 0 ? round(($used / $total) * 100) : 0;
                                    @endphp
                                    <h4 class="card-title stat-value">{{ $rate }}%</h4>
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
                        <div class="card-title fw-bold">Pendapatan per Kategori</div>
                        <p class="text-muted small">Distribusi nilai transaksi berdasarkan tipe tiket.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
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
                                            <span class="badge bg-soft-success text-success px-3">{{ number_format($ticketStats->firstWhere('category_name', $quota->category_name)->total_sold ?? 0) }}</span>
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
    </div>
</div>
@endsection

@section('ExtraJS')
<script>
    // Data dari Controller
    const ticketStats = @json($ticketStats);
    const quotaStats = @json($quotaStats);
    const attendanceStats = @json($attendanceStats);

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
                        const stat = ticketStats.find(s => s.category_name === item.category_name);
                        return stat ? stat.total_sold : 0;
                    }),
                    backgroundColor: 'rgba(0, 210, 255, 0.8)', // Glowing Cyan
                    borderRadius: 8,
                },
                {
                    label: 'Sisa Kuota',
                    data: quotaStats.map(item => item.remaining),
                    backgroundColor: 'rgba(244, 208, 63, 0.9)', // Glowing Gold
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { color: '#E0E6ED' } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#E0E6ED' } },
                x: { grid: { display: false }, ticks: { color: '#E0E6ED' } }
            }
        }
    });

    // 2. Attendance Chart (Pie/Doughnut)
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctxAttendance, {
        type: 'doughnut',
        data: {
            labels: attendanceStats.map(item => item.status.toUpperCase()),
            datasets: [{
                data: attendanceStats.map(item => item.count),
                backgroundColor: [
                    'rgba(46, 204, 113, 0.85)',   // Green
                    'rgba(241, 196, 15, 0.85)',   // Gold
                    'rgba(231, 76, 60, 0.85)',    // Red
                    'rgba(52, 152, 219, 0.85)'    // Blue
                ],
                borderWidth: 2,
                borderColor: 'rgba(255, 255, 255, 0.2)',
                hoverOffset: 15
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

    // 3. Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: ticketStats.map(item => item.category_name),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: ticketStats.map(item => item.total_revenue),
                backgroundColor: 'rgba(0, 210, 255, 0.7)',
                borderColor: '#00d2ff',
                borderWidth: 1,
                borderRadius: 20,
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
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: {
                        color: '#E0E6ED',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#E0E6ED' } 
                }
            }
        }
    });
</script>
@endsection
