@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Banner</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('admin.manageBanners') }}">Manajemen Banner</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Tambah</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{ route('admin.storeBanner') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header"><div class="card-title">Form Tambah Banner</div></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="title" class="fw-bold">Judul Banner</label>
                                        <input type="text" class="form-control" name="title" placeholder="Masukan Judul Banner" value="{{ old('title') }}" required />
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="event_id" class="fw-bold text-primary">Pilih Event Terkait</label>
                                        <select name="event_id" id="event_select" class="form-select" required>
                                            <option value="" disabled selected>-- Pilih Event --</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Setiap banner wajib memilih salah satu event untuk tujuan link "Beli Tiket".</small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="type" class="fw-bold">Tipe Banner</label>
                                        <select name="type" class="form-select" required>
                                            <option value="main">Banner Utama (Carousel)</option>
                                            <option value="card">Banner Card (Promo/Kecil)</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="status" class="fw-bold">Status</label>
                                        <select name="status" id="" class="form-select" required>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="fw-bold">Upload Gambar</label>
                                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                                        <small class="text-muted">Rekomendasi ukuran: 1200x400 (Maks 5MB)</small>
                                        <div id="preview-container" class="mt-3 d-none">
                                            <img id="image-preview" src="#" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action text-end">
                            <button type="submit" class="btn btn-success">Simpan Banner</button>
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
        
        @if($errors->any())
            swal({
                title: 'Gagal!',
                text: '{!! implode('\n', $errors->all()) !!}',
                type: 'error',
            });
        @endif

        // Image Preview
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#image-preview').attr('src', event.target.result);
                    $('#preview-container').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
        });
    });
    });
</script>
@endsection
