@extends('layouts.master')

@section('ExtraCSS')
<style>
    .form-section-title {
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 10px;
        margin-bottom: 20px;
        color: #1a2035;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .genre-container {
        max-height: 250px;
        overflow-y: auto;
        background: #fdfdfd;
        scrollbar-width: thin;
    }
    .genre-item {
        display: flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 6px;
        transition: background 0.2s;
        cursor: pointer;
    }
    .genre-item:hover {
        background: #f0f0f0;
    }
    .genre-item input {
        margin-top: 0;
        cursor: pointer;
    }
    .genre-item label {
        margin-bottom: 0;
        margin-left: 10px;
        cursor: pointer;
        font-size: 0.85rem;
        color: #333;
        flex: 1;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Event</h3>
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
                    <a href="{{ route('admin.manageEvents') }}">Manajemen Event</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit: {{ $event->name }}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title text-white mb-0">Form Edit Event</h4>
                    </div>
                    <form action="{{ route('admin.updateEvent', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-section-title">
                                <span>Informasi Dasar Event</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group p-0">
                                        <label for="name" class="fw-bold">Nama Event</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $event->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group p-0">
                                        <label for="location" class="fw-bold">Lokasi</label>
                                        <input type="text" class="form-control" id="location" name="location" value="{{ $event->location }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group p-0">
                                        <label for="status" class="fw-bold">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="nonactive" {{ $event->status == 'nonactive' ? 'selected' : '' }}>Non Active</option>
                                            <option value="pending" {{ $event->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section-title mt-4">
                                <span>Kategori Genre Musik</span>
                            </div>
                            <div class="genre-container p-2 mb-4 rounded border shadow-sm">
                                <div class="row g-1">
                                    @php 
                                        $selectedGenres = $event->genres->pluck('id')->toArray();
                                    @endphp
                                    @foreach($genres as $genre)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="genre-item">
                                            <input class="form-check-input" type="checkbox" name="genre_ids[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}" 
                                                {{ in_array($genre->id, $selectedGenres) ? 'checked' : '' }}>
                                            <label for="genre_{{ $genre->id }}">
                                                {{ $genre->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-action bg-light">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.manageEvents') }}" class="btn btn-outline-danger">
                                    <i class="fa fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save"></i> Simpan Perubahan
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

