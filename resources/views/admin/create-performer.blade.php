@extends('layouts.master')

@section('ExtraCSS')
<style>
    .genre-select-container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.05);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
    }
    .genre-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        padding: 8px 10px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .genre-item:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    .genre-item input {
        margin-right: 10px;
        margin-top: 0;
        cursor: pointer;
    }
    .genre-item label {
        margin-bottom: 0;
        cursor: pointer;
        font-size: 0.9rem;
        color: #E0E6ED;
        flex: 1;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Performer</h3>
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
                    <a href="{{ route('admin.managePerformers') }}">Manajemen Performer</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah Performer</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white mb-0">Form Tambah Performer</h4>
                    </div>
                    <form action="{{ route('admin.storePerformer') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group p-0">
                                        <label for="name" class="fw-bold mb-2 text-primary">Nama Performer / Artis</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                            placeholder="Masukkan nama lengkap artis/band..." required>
                                        <small class="text-muted mt-1 d-block">Nama akan ditampilkan di halaman detail event.</small>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group p-0">
                                        <label for="image" class="fw-bold mb-2 text-primary">Foto Performer</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <small class="text-muted mt-1 d-block">Format JPG/PNG, ukuran maksimal 2MB.</small>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group p-0">
                                        <label class="fw-bold mb-2 text-primary">Genre Musik</label>
                                        <div class="genre-select-container">
                                            <div class="row">
                                                @foreach ($genres as $genre)
                                                    <div class="col-md-4">
                                                        <div class="genre-item">
                                                            <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}">
                                                            <label for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Pilih genre musik yang sesuai untuk performer ini.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action bg-transparent p-4 border-top border-light border-opacity-10">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.managePerformers') }}" class="btn btn-outline-danger px-4">
                                    <i class="fa fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fa fa-save me-1"></i> Simpan Performer
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
