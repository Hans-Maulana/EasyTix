@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit User</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">User</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit User</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit User</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.updateUser', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                             <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <input type="text" class="form-control" id="role" value='{{ $user->role }}' name="role" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="name" value='{{ $user->name }}' name="name" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" value='{{ $user->email }}' name="email" placeholder="Masukkan email" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="phone_number">Nomor Telepon</label>
                                        <input type="text" class="form-control" id="phone_number" value='{{ $user->phone_number }}' name="phone_number" placeholder="Masukkan nomor telepon" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('admin.manageUsers') }}" class="btn btn-danger">Kembali</a>
                        </form>
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
        // SweetAlert Flash Notifications
        @if(session('error'))
            swal("Gagal!", "{{ session('error') }}", "error");
        @endif

        @if($errors->any())
            swal({
                title: 'Validasi Gagal!',
                text: '@foreach($errors->all() as $error)• {{ $error }} @endforeach',
                type: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK',
            });
        @endif
    });
</script>
@endsection
