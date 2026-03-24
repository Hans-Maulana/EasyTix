@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Banner</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('admin.manageBanners') }}">Manajemen Banner</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Edit</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{ route('admin.updateBanner', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="card-header"><div class="card-title">Form Edit Banner</div></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="title" class="fw-bold">Judul Banner</label>
                                        <input type="text" class="form-control" name="title" value="{{ old('title', $banner->title) }}" required />
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="link" class="fw-bold">Link (Opsional)</label>
                                        <input type="url" class="form-control" name="link" value="{{ old('link', $banner->link) }}" />
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="type" class="fw-bold">Tipe Banner</label>
                                        <select name="type" class="form-select" required>
                                            <option value="main" {{ $banner->type == 'main' ? 'selected' : '' }}>Banner Utama (Carousel)</option>
                                            <option value="card" {{ $banner->type == 'card' ? 'selected' : '' }}>Banner Card (Promo/Kecil)</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="status" class="fw-bold">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ $banner->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ $banner->status == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="fw-bold">Upload Gambar Baru (Lewati jika tidak diganti)</label>
                                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                        <div id="preview-container" class="mt-3">
                                            <label class="d-block mb-1 text-muted">Preview Sekarang:</label>
                                            <img id="image-preview" src="{{ asset('storage/' . $banner->image) }}" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action text-end">
                            <button type="submit" class="btn btn-success">Perbarui Banner</button>
                            <a href="{{ route('admin.manageBanners') }}" class="btn btn-danger">Batal</a>
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
        @if(session('error')) swal("Gagal!", "{{ session('error') }}", "error"); @endif
        
        // Image Preview handler
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#image-preview').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
