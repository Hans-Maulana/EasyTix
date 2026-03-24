@extends('layouts.master')

@section('ExtraCSS')
    <style>
        .schedule-card {
            border-left: 5px solid #1a2035;
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .schedule-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .remove-schedule {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .add-schedule-btn {
            background: #1a2035;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .add-schedule-btn:hover {
            background: #2c3e50;
            transform: translateY(-2px);
        }

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
                <h3 class="fw-bold mb-3">Tambah Event</h3>
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
                        <a href="#">Tambah Event</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title text-white mb-0">Form Tambah Event</h4>
                        </div>
                        <form action="{{ route('admin.storeEvent') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-section-title">
                                    <span class="text-primary">Informasi Dasar Event</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="name" class="fw-bold">Nama Event</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Contoh: Konser Dewa 19" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="location" class="fw-bold">Lokasi</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                placeholder="Contoh: Jakarta International Stadium" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="status" class="fw-bold">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="" disabled selected>Pilih Status</option>
                                                <option value="active">Active</option>
                                                <option value="nonactive">Non Active</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">
                                    <span class="text-primary">Kategori Genre Musik</span>
                                </div>
                                <div class="genre-container p-2 mb-4 rounded border shadow-sm">
                                    <div class="row g-1">
                                        @foreach($genres as $genre)
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                <div class="genre-item">
                                                    <input class="form-check-input" type="checkbox" name="genre_ids[]"
                                                        value="{{ $genre->id }}" id="genre_{{ $genre->id }}">
                                                    <label for="genre_{{ $genre->id }}">
                                                        {{ $genre->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">
                                    <span class="text-primary">Jadwal Event</span>
                                    <button type="button" class="btn btn-sm btn-dark add-schedule-btn" id="add-schedule">
                                        <i class="fa fa-plus-circle me-1"></i> Tambah Jadwal
                                    </button>
                                </div>

                                <div id="schedule-wrapper">
                                    <div class="schedule-card" id="schedule-0">
                                        <button type="button" class="btn btn-link text-danger remove-schedule"
                                            onclick="removeSchedule(0)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group p-0 mb-3">
                                                    <label class="fw-bold text-muted">Tanggal Event</label>
                                                    <input type="date" name="schedules[0][event_date]" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group p-0 mb-3">
                                                    <label class="fw-bold text-muted">Waktu Mulai</label>
                                                    <input type="time" name="schedules[0][start_time]" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group p-0 mb-3">
                                                    <label class="fw-bold text-muted">Waktu Selesai</label>
                                                    <input type="time" name="schedules[0][end_time]" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group p-0">
                                                    <label class="fw-bold text-muted">Deskripsi Jadwal (Opsional)</label>
                                                    <textarea name="schedules[0][description]" class="form-control" rows="2"
                                                        placeholder="Detail singkat untuk jadwal ini..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group p-0 mb-3">
                                                    <label class="fw-bold text-muted">Status Sesi</label>
                                                    <select name="schedules[0][status]" class="form-select" required>
                                                        <option value="scheduled" selected>Scheduled</option>
                                                        <option value="ongoing">Ongoing</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="postponed">Postponed</option>
                                                        <option value="cancelled">Cancelled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nested Tickets -->
                                        <div class="mt-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="fw-bold mb-0 text-primary">Daftar Tiket untuk Jadwal Ini</h6>
                                                <button type="button" class="btn btn-xs btn-outline-primary" onclick="addTicket(0)">
                                                    <i class="fa fa-plus"></i> Tambah Tiket
                                                </button>
                                            </div>
                                            <div id="ticket-wrapper-0">
                                                <!-- Ticket Row 0 of Schedule 0 -->
                                                <div class="row g-2 mb-2 ticket-row" id="s0-t0">
                                                    <div class="col-md-4">
                                                        <select name="schedules[0][tickets][0][ticket_types_id]" class="form-select form-select-sm" required>
                                                            <option value="" disabled selected>Pilih Tipe</option>
                                                            @foreach($ticketTypes as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" name="schedules[0][tickets][0][capacity]" class="form-control form-control-sm" placeholder="Kapasitas" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="schedules[0][tickets][0][price]" class="form-control form-control-sm" placeholder="Harga" required>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <button type="button" class="btn btn-link text-danger p-0" onclick="removeTicket('s0-t0')">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-action bg-light">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.manageEvents') }}" class="btn btn-outline-danger">
                                        <i class="fa fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fa fa-save"></i> Simpan Event
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

@section('ExtraJS')
    <script>
        let scheduleIndex = 1;
        let ticketTypeOptions = `@foreach($ticketTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach`;

        // SweetAlert Flash Notifications
        $(document).ready(function () {
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

        document.getElementById('add-schedule').addEventListener('click', function () {
            const wrapper = document.getElementById('schedule-wrapper');
            const newItem = document.createElement('div');
            newItem.className = 'schedule-card';
            newItem.id = `schedule-${scheduleIndex}`;
            newItem.innerHTML = `
                <button type="button" class="btn btn-link text-danger remove-schedule" onclick="removeSchedule(${scheduleIndex})">
                    <i class="fa fa-trash"></i>
                </button>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group p-0 mb-3">
                            <label class="fw-bold text-muted">Tanggal Event</label>
                            <input type="date" name="schedules[${scheduleIndex}][event_date]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group p-0 mb-3">
                            <label class="fw-bold text-muted">Waktu Mulai</label>
                            <input type="time" name="schedules[${scheduleIndex}][start_time]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group p-0 mb-3">
                            <label class="fw-bold text-muted">Waktu Selesai</label>
                            <input type="time" name="schedules[${scheduleIndex}][end_time]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group p-0">
                            <label class="fw-bold text-muted">Deskripsi Jadwal (Opsional)</label>
                            <textarea name="schedules[${scheduleIndex}][description]" class="form-control" rows="2" placeholder="Detail singkat untuk jadwal ini..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group p-0 mb-3">
                            <label class="fw-bold text-muted">Status Sesi</label>
                            <select name="schedules[${scheduleIndex}][status]" class="form-select" required>
                                <option value="scheduled" selected>Scheduled</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="postponed">Postponed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Nested Tickets -->
                <div class="mt-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 text-primary">Daftar Tiket untuk Jadwal Ini</h6>
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="addTicket(${scheduleIndex})">
                            <i class="fa fa-plus"></i> Tambah Tiket
                        </button>
                    </div>
                    <div id="ticket-wrapper-${scheduleIndex}">
                        <div class="row g-2 mb-2 ticket-row" id="s${scheduleIndex}-t0">
                            <div class="col-md-4">
                                <select name="schedules[${scheduleIndex}][tickets][0][ticket_types_id]" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Pilih Tipe</option>
                                    ${ticketTypeOptions}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="schedules[${scheduleIndex}][tickets][0][capacity]" class="form-control form-control-sm" placeholder="Kapasitas" required>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="schedules[${scheduleIndex}][tickets][0][price]" class="form-control form-control-sm" placeholder="Harga" required>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-link text-danger p-0" onclick="removeTicket('s${scheduleIndex}-t0')">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            wrapper.appendChild(newItem);
            scheduleIndex++;
        });

        function removeSchedule(id) {
            const item = document.getElementById(`schedule-${id}`);
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    item.remove();
                }, 300);
            }
        }

        function addTicket(sIdx) {
            const wrapper = document.getElementById(`ticket-wrapper-${sIdx}`);
            const tIdx = wrapper.querySelectorAll('.ticket-row').length;
            const rowId = `s${sIdx}-t${tIdx}`;

            const newRow = document.createElement('div');
            newRow.className = 'row g-2 mb-2 ticket-row';
            newRow.id = rowId;
            newRow.innerHTML = `
                <div class="col-md-4">
                    <select name="schedules[${sIdx}][tickets][${tIdx}][ticket_types_id]" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Pilih Tipe</option>
                        ${ticketTypeOptions}
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="schedules[${sIdx}][tickets][${tIdx}][capacity]" class="form-control form-control-sm" placeholder="Kapasitas" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="schedules[${sIdx}][tickets][${tIdx}][price]" class="form-control form-control-sm" placeholder="Harga" required>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="removeTicket('${rowId}')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(newRow);
        }

        function removeTicket(id) {
            const item = document.getElementById(id);
            if (item) {
                item.remove();
            }
        }

    </script>
@endsection