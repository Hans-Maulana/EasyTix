@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Event Saya</h3>
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
                    <a href="#">Organizer</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Event Saya</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Event yang Dikelola</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="my-events-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Status Akses</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvedRequests as $request)
                                        <tr>
                                            <td>{{ $request->event->id }}</td>
                                            <td>{{ $request->event->name }}</td>
                                            <td>{{ $request->event->location }}</td>
                                            <td>
                                                <span class="badge badge-success">Approved</span>
                                            </td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('organizer.myEventsDetail', $request->event->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                                    </a>
                                                </div>
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
            $('#my-events-table').DataTable({
                "pageLength": 10,
            });
        });
    </script>
@endsection
