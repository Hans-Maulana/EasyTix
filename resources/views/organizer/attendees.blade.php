@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Peserta - {{ $schedule->event->name }}</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('organizer.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.dashboard') }}">Organizer</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.myEvents') }}">Event Saya</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organizer.myEventsDetail', $schedule->event->id) }}">{{ $schedule->event->name }}</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Daftar Peserta: {{ $schedule->id }}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Peserta</p>
                                    <h4 class="card-title">{{ $totalCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Hadir</p>
                                    <h4 class="card-title">{{ $hadirCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Tidak Hadir</p>
                                    <h4 class="card-title">{{ $tidakHadirCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Rate Kehadiran</p>
                                    <h4 class="card-title">{{ $attendanceRate }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Diagram Statistik Kehadiran</div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="chart-container" style="min-height: 250px">
                                    <canvas id="attendanceDoughnutChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h2 class="fw-bold mb-0 text-success">{{ $hadirCount }}</h2>
                                        <p class="text-muted">Peserta Hadir</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h2 class="fw-bold mb-0 text-danger">{{ $tidakHadirCount }}</h2>
                                        <p class="text-muted">Peserta Tidak Hadir</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h2 class="fw-bold mb-0 text-primary">{{ $totalCount }}</h2>
                                        <p class="text-muted">Total Peserta</p>
                                    </div>
                                </div>
                                <div class="progress-info mt-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Presentase Kehadiran</span>
                                        <span class="fw-bold text-success">{{ $attendanceRate }}%</span>
                                    </div>
                                    <div class="progress" style="height: 12px; background: rgba(255,255,255,0.05);">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceRate }}%" aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">List Peserta yang Terdaftar - {{ $schedule->id }}</h4>
                        <div class="d-flex gap-2">
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 200px;">
                                <option value="">Semua Peserta (Keseluruhan)</option>
                                <option value="Used">Sudah Hadir</option>
                                <option value="Valid">Belum Hadir (Tidak Hadir)</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="attendees-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Peserta</th>
                                        <th>Tipe Tiket</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendees as $attendee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendee->owner_name }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $attendee->ticket->ticket_type->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($attendee->status) {
                                                    'valid' => 'badge-success',
                                                    'invalid' => 'badge-danger',
                                                    'used' => 'badge-info',
                                                    'expired' => 'badge-warning',
                                                    'cancelled' => 'badge-secondary',
                                                    default => 'badge-dark'
                                                };
                                                
                                                $statusText = match($attendee->status) {
                                                    'valid' => 'Valid',
                                                    'used' => 'Used',
                                                    default => ucfirst($attendee->status)
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
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
        $(document).ready(function() {
            // DataTable Initialization
            var table = $('#attendees-table').DataTable({
                "pageLength": 10,
                "ordering": true,
                "info": true,
            });

            // Status Filter Logic
            $('#statusFilter').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // Doughnut Chart for Attendance
            var ctx = document.getElementById('attendanceDoughnutChart').getContext('2d');
            var attendanceChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [{{ $hadirCount }}, {{ $tidakHadirCount }}],
                        backgroundColor: ['#2ecc71', '#e74c3c'],
                        borderWidth: 0
                    }],
                    labels: ['Hadir', 'Tidak Hadir']
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 75,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
@endsection
