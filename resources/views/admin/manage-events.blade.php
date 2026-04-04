@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Event</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Admin</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Manajemen Event</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Event</h4>
                            <a href="{{ route('admin.createEvent') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Event
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="events-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Performer</th>
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
                                            <td>{{ $event->category->name ?? '-' }}</td>
                                            <td>
                                                @foreach($event->performers as $performer)
                                                    <span class="badge badge-secondary">{{ $performer->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $event->location }}</td>
                                            <td>
                                                @if($event->status == 'active')
                                                    <span class="badge badge-primary">Active</span>
                                                @elseif($event->status == 'nonactive')
                                                    <span class="badge badge-info">Non Active</span>
                                                @elseif($event->status == 'pending')
                                                    <span class="badge badge-secondary">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('admin.editEvent', $event->id) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Event">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.scheduleEvent', $event->id) }}" class="btn btn-link btn-success btn-lg" data-bs-toggle="tooltip" title="Detail Event">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.deleteEvent', $event->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-link btn-danger btn-delete-confirm" title="Hapus Event">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
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
            $('#events-table').DataTable({
                "pageLength": 10,
            });

            // SweetAlert Flash Notifications
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#142E5E'
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#142E5E'
                });
            @endif

            // SweetAlert Delete Confirmation
            $('.btn-delete-confirm').on('click', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Hapus Event?',
                    text: 'Data event yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
