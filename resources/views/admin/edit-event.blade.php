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
                                    <span class="text-primary">Informasi Dasar Event</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="name" class="fw-bold">Nama Event</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $event->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="location" class="fw-bold">Lokasi</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                value="{{ $event->location }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="status" class="fw-bold">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="nonactive"
                                                    {{ $event->status == 'nonactive' ? 'selected' : '' }}>Non Active</option>
                                                <option value="pending" {{ $event->status == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">
                                    <span class="text-primary">Kategori Genre Musik</span>
                                </div>
                                <div class="genre-container p-2 mb-4 rounded border shadow-sm">
                                    <div class="row g-1">
                                        @php
                                            $selectedGenres = $event->genres->pluck('id')->toArray();
                                        @endphp
                                        @foreach($genres as $genre)
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                <div class="genre-item">
                                                    <input class="form-check-input" type="checkbox" name="genre_ids[]"
                                                        value="{{ $genre->id }}" id="genre_{{ $genre->id }}"
                                                        {{ in_array($genre->id, $selectedGenres) ? 'checked' : '' }}>
                                                    <label for="genre_{{ $genre->id }}">
                                                        {{ $genre->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">
                                    <span class="text-primary">Jadwal & Tiket Event</span>
                                    <button type="button" class="btn btn-sm btn-dark add-schedule-btn" id="add-schedule">
                                        <i class="fa fa-plus-circle me-1"></i> Tambah Jadwal
                                    </button>
                                </div>

                                <div id="schedule-wrapper">
                                    @foreach($event->event_schedule as $sIdx => $schedule)
                                        <div class="schedule-card" id="schedule-{{ $sIdx }}">
                                            <input type="hidden" name="schedules[{{ $sIdx }}][id]" value="{{ $schedule->id }}">
                                            <button type="button" class="btn btn-link text-danger remove-schedule"
                                                onclick="removeSchedule({{ $sIdx }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group p-0 mb-3">
                                                        <label class="fw-bold text-muted">Tanggal Event</label>
                                                        <input type="date" name="schedules[{{ $sIdx }}][event_date]"
                                                            class="form-control" value="{{ $schedule->event_date }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group p-0 mb-3">
                                                        <label class="fw-bold text-muted">Waktu Mulai</label>
                                                        <input type="time" name="schedules[{{ $sIdx }}][start_time]"
                                                            class="form-control" value="{{ $schedule->start_time }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group p-0 mb-3">
                                                        <label class="fw-bold text-muted">Waktu Selesai</label>
                                                        <input type="time" name="schedules[{{ $sIdx }}][end_time]"
                                                            class="form-control" value="{{ $schedule->end_time }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group p-0">
                                                        <label class="fw-bold text-muted">Deskripsi Jadwal (Opsional)</label>
                                                        <textarea name="schedules[{{ $sIdx }}][description]" class="form-control"
                                                            rows="2">{{ $schedule->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group p-0 mb-3">
                                                        <label class="fw-bold text-muted">Status Sesi</label>
                                                        <select name="schedules[{{ $sIdx }}][status]" class="form-select" required>
                                                            <option value="scheduled" {{ $schedule->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                            <option value="ongoing" {{ $schedule->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                            <option value="completed" {{ $schedule->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                            <option value="postponed" {{ $schedule->status == 'postponed' ? 'selected' : '' }}>Postponed</option>
                                                            <option value="cancelled" {{ $schedule->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h6 class="fw-bold mb-0 text-primary">Daftar Tiket</h6>
                                                    <button type="button" class="btn btn-xs btn-outline-primary"
                                                        onclick="addTicket({{ $sIdx }})">
                                                        <i class="fa fa-plus"></i> Tambah Tiket
                                                    </button>
                                                </div>
                                                <div id="ticket-wrapper-{{ $sIdx }}">
                                                    @foreach($schedule->tickets as $tIdx => $ticket)
                                                        <div class="row g-2 mb-2 ticket-row" id="s{{ $sIdx }}-t{{ $tIdx }}">
                                                            <input type="hidden" name="schedules[{{ $sIdx }}][tickets][{{ $tIdx }}][id]" value="{{ $ticket->id }}">
                                                            <div class="col-md-4">
                                                                <select name="schedules[{{ $sIdx }}][tickets][{{ $tIdx }}][ticket_types_id]"
                                                                    class="form-select form-select-sm" required>
                                                                    @foreach($ticketTypes as $type)
                                                                        <option value="{{ $type->id }}"
                                                                            {{ $ticket->ticket_types_id == $type->id ? 'selected' : '' }}>
                                                                            {{ $type->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="number"
                                                                    name="schedules[{{ $sIdx }}][tickets][{{ $tIdx }}][capacity]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $ticket->capacity }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number"
                                                                    name="schedules[{{ $sIdx }}][tickets][{{ $tIdx }}][price]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $ticket->price }}" required>
                                                            </div>
                                                            <div class="col-md-1 text-end">
                                                                <button type="button" class="btn btn-link text-danger p-0"
                                                                    onclick="removeTicket('s{{ $sIdx }}-t{{ $tIdx }}')">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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

@section('ExtraJS')
    <script>
        let scheduleIndex = {{ $event->event_schedule->count() }};
        let ticketTypeOptions = `@foreach($ticketTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach`;

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

