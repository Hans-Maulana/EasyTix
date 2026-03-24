@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Banner</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Admin</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Manajemen Banner</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Banner</h4>
                            <a href="{{ route('admin.createBanner') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Banner
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="banners-table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banners as $banner)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="" style="width: 150px; border-radius: 8px;">
                                        </td>
                                        <td>{{ $banner->title }}</td>
                                        <td>
                                            @if($banner->type == 'main')
                                                <span class="badge badge-info">Utama</span>
                                            @else
                                                <span class="badge badge-warning">Card/Promo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($banner->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.editBanner', $banner->id) }}" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('admin.deleteBanner', $banner->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-link btn-danger btn-delete-confirm"><i class="fa fa-times"></i></button>
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
    $(document).ready(function() {
        $('#banners-table').DataTable({ "pageLength": 10 });

        @if(session('success')) swal("Berhasil!", "{{ session('success') }}", "success"); @endif
        @if(session('error')) swal("Gagal!", "{{ session('error') }}", "error"); @endif

        $('.btn-delete-confirm').on('click', function (e) {
            e.preventDefault();
            const form = $(this).closest('form');
            swal({
                title: 'Hapus Banner?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                type: 'warning',
                buttons: {
                    confirm: { text: "Ya, Hapus!", className: "btn btn-danger" },
                    cancel: { visible: true, text: "Batal", className: "btn btn-secondary" }
                }
            }).then((Delete) => { if (Delete) form.submit(); });
        });
    });
</script>
@endsection
