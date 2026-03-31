@extends('layouts.master')

@section('ExtraCSS')
<style>
    .genre-select-container {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ebedf2;
        border-radius: 5px;
        padding: 10px;
        background: #fff;
    }
    .genre-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        padding: 5px;
        border-radius: 4px;
        transition: background 0.2s;
    }
    .genre-item:hover {
        background: #f8f9fa;
    }
    .genre-item input {
        margin-right: 10px;
    }
    .genre-item label {
        margin-bottom: 0;
        cursor: pointer;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Performer</h3>
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
                    <a href="#">Edit: {{ $performer->name }}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white mb-0">Form Edit Performer</h4>
                    </div>
                    <form action="{{ route('admin.updatePerformer', $performer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group p-0">
                                        <label for="name" class="fw-bold mb-2 text-primary">Nama Performer / Artis</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                            value="{{ $performer->name }}" placeholder="Masukkan nama artis/band..." required>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group p-0">
                                        <label for="image" class="fw-bold mb-2 text-primary">Foto Performer</label>
                                        @if($performer->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $performer->image) }}" class="rounded shadow-sm" width="100" height="100" alt="Current Photo">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <small class="text-muted mt-1 d-block">Biarkan kosong jika tidak ingin mengganti foto.</small>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group p-0">
                                        <label class="fw-bold mb-2 text-primary">Genre Musik</label>
                                        <div class="genre-select-container">
                                            @php
                                                $selectedGenres = $performer->genres->pluck('id')->toArray();
                                            @endphp
                                            <div class="row">
                                                @foreach ($genres as $genre)
                                                    <div class="col-md-4">
                                                        <div class="genre-item">
                                                            <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}"
                                                                {{ in_array($genre->id, $selectedGenres) ? 'checked' : '' }}>
                                                            <label for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action bg-light p-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.managePerformers') }}" class="btn btn-outline-danger px-4">
                                    <i class="fa fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
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
