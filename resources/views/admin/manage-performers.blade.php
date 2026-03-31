@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Performer</h3>
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
                    <a href="#">Manajemen Performer</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Performer</h4>
                            <a href="{{ route('admin.createPerformer') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Performer
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="performers-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Foto</th>
                                        <th>Nama Performer</th>
                                        <th>Genre Musik</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($performers as $performer)
                                        <tr>
                                            <td>{{ $performer->id }}</td>
                                            <td>
                                                @if($performer->image)
                                                    <img src="{{ asset('storage/' . $performer->image) }}" class="rounded-circle" width="50" height="50" alt="Performer Photo">
                                                @else
                                                    <img src="{{ asset('assets/img/kaiadmin/logo_EasyTix.png') }}" class="rounded-circle" width="50" height="50" alt="Default Performer Photo">
                                                @endif
                                            </td>
                                            <td>{{ $performer->name }}</td>
                                            <td>
                                                @foreach ($performer->genres as $genre)
                                                    <span class="badge badge-info">{{ $genre->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('admin.editPerformer', $performer->id) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Performer">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.deletePerformer', $performer->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-link btn-danger btn-delete-confirm" title="Hapus Performer">
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
            $('#performers-table').DataTable({
                "pageLength": 10,
            });

            @if(session('success'))
                swal("Berhasil!", "{{ session('success') }}", "success");
            @endif

            $('.btn-delete-confirm').on('click', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                swal({
                    title: 'Hapus Performer?',
                    text: 'Data yang dikaitkan dengan performer ini akan kehilangan referensinya!',
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
