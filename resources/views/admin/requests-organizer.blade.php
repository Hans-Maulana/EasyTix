@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Manajemen Request Organizer</h3>
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
                    <a href="#">Request Organizer</a>
                </li>
            </ul>
        </div>



        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-events-tab" data-bs-toggle="pill" href="#pills-events" role="tab" aria-controls="pills-events" aria-selected="true">Request Mengelola Event (<span class="badge badge-danger">{{ $eventRequests->where('status', 'pending')->count() }}</span>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-waitlist-tab" data-bs-toggle="pill" href="#pills-waitlist" role="tab" aria-controls="pills-waitlist" aria-selected="false">Request Waiting List (<span class="badge badge-danger">{{ $waitlistRequests->where('status', 'requested')->count() }}</span>)</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                            <!-- Tab: Request Event -->
                            <div class="tab-pane fade show active" id="pills-events" role="tabpanel" aria-labelledby="pills-events-tab">
                                <div class="table-responsive">
                                    <table class="display table table-striped table-hover datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Organizer</th>
                                                <th>Event Diminta</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($eventRequests as $req)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $req->user->name }}</td>
                                                <td><b>{{ $req->event->name }}</b></td>
                                                <td>
                                                    @if($req->status == 'pending')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($req->status == 'approved')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($req->status == 'pending')
                                                        <form action="{{ route('admin.approveRequest', $req->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Terima</button>
                                                        </form>
                                                        <form action="{{ route('admin.rejectRequest', $req->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Tolak</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Tab: Request Waiting List -->
                            <div class="tab-pane fade" id="pills-waitlist" role="tabpanel" aria-labelledby="pills-waitlist-tab">
                                <div class="table-responsive">
                                    <table class="display table table-striped table-hover datatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Event & Jadwal</th>
                                                <th>Tipe Tiket</th>
                                                <th>Diminta Oleh User</th>
                                                <th>Jumlah Kuota Diharapkan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($waitlistRequests as $wl)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <b>{{ $wl->ticket->event_schedule->event->name ?? '-' }}</b><br>
                                                    <small>{{ \Carbon\Carbon::parse($wl->ticket->event_schedule->event_date ?? now())->format('d M Y') }}</small>
                                                </td>
                                                <td><span class="badge badge-primary">{{ $wl->ticket->ticket_type->name ?? '-' }}</span></td>
                                                <td>{{ $wl->user->name ?? '-' }}</td>
                                                <td>{{ $wl->requested_quantity }}</td>
                                                <td>
                                                    @if($wl->status == 'requested')
                                                        <span class="badge badge-info">Diajukan Organizer</span>
                                                    @elseif($wl->status == 'approved')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($wl->status == 'requested')
                                                        <div class="d-flex gap-2">
                                                            <form action="{{ route('admin.approveWaitingList', $wl->id) }}" method="POST">
                                                                @csrf
                                                                <button class="btn btn-success btn-sm" onclick="confirmAction(event, 'Yakin menyetujui penambahan kuota tiket ke sistem?')"><i class="fas fa-check"></i> Terima</button>
                                                            </form>
                                                            <form action="{{ route('admin.rejectWaitingList', $wl->id) }}" method="POST">
                                                                @csrf
                                                                <button class="btn btn-danger btn-sm" onclick="confirmAction(event, 'Tolak permohonan penambahan tiket ini?')"><i class="fas fa-times"></i> Tolak</button>
                                                            </form>
                                                        </div>
                                                    @endif
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
    </div>
</div>
@endsection

@section('ExtraJS')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                "pageLength": 10,
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#142E5E'
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#142E5E'
                });
            @endif
            
            // Konfirmasi untuk Approve/Reject custom dengan SweetAlert2
            // karena button tidak memakai class khusus pada form, kita ubah logic onclicknya:
        });
        
        function confirmAction(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#142E5E',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection
