@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Kategori</h3>
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
                    <a href="#">Tambah Kategori</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white mb-0">Form Tambah Kategori</h4>
                    </div>
                    <form action="{{ route('admin.storeCategory') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group p-0 mb-3">
                                <label for="name" class="fw-bold">Nama Kategori</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Contoh: Konser, Festival, Workshop" required>
                                <small class="text-muted">Masukkan nama kategori event yang menarik.</small>
                            </div>
                        </div>
                        <div class="card-action bg-light">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.manageCategories') }}" class="btn btn-outline-danger">
                                    <i class="fa fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save"></i> Simpan Kategori
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
