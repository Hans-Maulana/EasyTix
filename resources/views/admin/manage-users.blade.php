@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Manajemen User</h3>
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
                        <a href="#">Manajemen User</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar User</h4>
                                <a href="{{ route('admin.createUser') }}" class="btn btn-primary btn-round ms-auto">
                                    <i class="fa fa-plus"></i>
                                    Tambah User
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users-table" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->role == 'admin')
                                                        <span class="badge badge-primary">Admin</span>
                                                    @elseif($user->role == 'organizer')
                                                        <span class="badge badge-info">Organizer</span>
                                                    @else
                                                        <span class="badge badge-secondary">User</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <a href="{{ route('admin.editUser', $user->id) }}"
                                                            class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip"
                                                            title="Edit User">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST"
                                                            class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-link btn-danger btn-delete-confirm"
                                                                title="Hapus User">
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
        $(document).ready(function () {
            $('#users-table').DataTable({
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
                    title: 'Hapus User?',
                    text: 'Data user yang dihapus tidak bisa dikembalikan!',
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