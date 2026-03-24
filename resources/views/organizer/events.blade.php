@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Request Akses Event</h3>
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
                    <a href="#">Request Akses</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Event yang Tersedia</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="events-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>{{ $event->id }}</td>
                                            <td>{{ $event->name }}</td>
                                            <td>{{ $event->location }}</td>
                                            <td>
                                                <span class="badge badge-primary">Active</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('organizer.requestAccess', $event->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-key me-1"></i> Request Access
                                                    </button>
                                                </form>
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
            $('#events-table').DataTable({
                "pageLength": 10,
            });

            // SweetAlert Flash Notifications
            @if(session('success'))
                swal("Berhasil!", "{{ session('success') }}", "success");
            @endif
            @if(session('error'))
                swal("Gagal!", "{{ session('error') }}", "error");
            @endif

            // SweetAlert Delete Confirmation
            $('.btn-delete-confirm').on('click', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                swal({
                    title: 'Hapus Event?',
                    text: 'Data event yang dihapus tidak bisa dikembalikan!',
                    type: 'warning',
                    buttons: {
                        confirm: {
                            text: "Ya, Hapus!",
                            className: "btn btn-danger",
                        },
                        cancel: {
                            visible: true,
                            text: "Batal",
                            className: "btn btn-secondary",
                        },
                    },
                }).then((Delete) => {
                    if (Delete) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
