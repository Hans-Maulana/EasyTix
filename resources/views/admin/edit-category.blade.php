@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Kategori</h3>
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
                    <a href="{{ route('admin.manageCategories') }}">Manajemen Kategori</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit: {{ $category->name }}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white mb-0">Form Edit Kategori</h4>
                    </div>
                    <form action="{{ route('admin.updateCategory', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            <div class="form-group p-0 mb-3">
                                <label for="name" class="fw-bold mb-2">Nama Kategori</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    value="{{ $category->name }}" placeholder="Masukkan nama kategori..." required>
                                <small class="text-muted mt-1 d-block">Ganti nama kategori jika diperlukan.</small>
                            </div>
                        </div>
                        <div class="card-action bg-light p-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.manageCategories') }}" class="btn btn-outline-danger px-4">
                                    <i class="fa fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
