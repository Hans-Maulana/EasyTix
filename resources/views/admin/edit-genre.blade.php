@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Genre</h3>
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
                    <a href="{{ route('admin.manageGenres') }}">Manajemen Genre</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{ route('admin.updateGenre', $genre->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <div class="card-title">Form Edit Genre</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="name">Nama Genre</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $genre->name) }}" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Perbarui</button>
                            <a href="{{ route('admin.manageGenres') }}" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraJS')
<script>
    $(document).ready(function() {
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
