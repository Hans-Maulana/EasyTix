@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Kategori</h3>
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
                    <a href="#">Manajemen Kategori</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Kategori</h4>
                            <a href="{{ route('admin.createCategory') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Kategori
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="categories-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Kategori</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('admin.editCategory', $category->id) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Kategori">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.deleteCategory', $category->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-link btn-danger btn-delete-confirm" title="Hapus Kategori">
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
            $('#categories-table').DataTable({
                "pageLength": 10,
            });

            @if(session('success'))
                swal("Berhasil!", "{{ session('success') }}", "success");
            @endif

            $('.btn-delete-confirm').on('click', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                swal({
                    title: 'Hapus Kategori?',
                    text: 'Data yang dikaitkan dengan kategori ini mungkin akan terpengaruh!',
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
