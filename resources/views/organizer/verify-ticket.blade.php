@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Pilih Event untuk Verifikasi</h3>
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
                    <a href="#">Tabel Pilih Event</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Event yang Tersedia untuk Verifikasi</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="verify-select-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Event</th>
                                        <th>Lokasi</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvedRequests as $request)
                                        <tr>
                                            <td>{{ $request->event->id }}</td>
                                            <td>{{ $request->event->name }}</td>
                                            <td>{{ $request->event->location }}</td>
                                            <td>
                                                <a href="{{ route('organizer.verifyTicketDetail', $request->event->id) }}" class="btn btn-info btn-sm w-100">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($approvedRequests->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Belum ada event yang dipegang.</td>
                                    </tr>
                                    @endif
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
            $('#verify-select-table').DataTable({
                "pageLength": 10,
            });
        });
    </script>
@endsection
