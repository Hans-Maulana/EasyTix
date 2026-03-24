@extends('layouts.master')

@section('content')
    <div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Tiket</h3>
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
                    <a href="#">Manajemen Tiket</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Tiket</h4>
                            <a href="{{ route('admin.createTicketType') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Tiket
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ticket-types-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Tiket</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticketTypes as $ticketType)
                                        <tr>
                                            <td>{{ $ticketType->id }}</td>
                                            <td>{{ $ticketType->name }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('admin.editTicketType', $ticketType->id) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Tiket">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.deleteTicketType', $ticketType->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-link btn-danger btn-delete-confirm" title="Hapus Tiket">
                                                            <i class="fa fa-times"></i>
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
            $('#ticket-types-table').DataTable({
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
                    title: 'Hapus Tipe Tiket?',
                    text: 'Data tipe tiket yang dihapus tidak bisa dikembalikan!',
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