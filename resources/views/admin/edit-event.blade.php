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
    .performer-item {
        display: flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 6px;
        transition: background 0.2s;
        cursor: pointer;
    }
    .performer-item:hover {
        background: #f0f0f0;
    }
    .performer-item input {
        margin-top: 0;
        cursor: pointer;
    }
    .performer-item label {
        margin-bottom: 0;
        margin-left: 10px;
        cursor: pointer;
        font-size: 0.85rem;
        color: #333;
        flex: 1;
    }
    .genre-badge {
        background: #e9ecef;
        color: #495057;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-right: 4px;
        margin-bottom: 4px;
        display: inline-block;
    }
    .genre-badge.selected {
        background: #1a2035;
        color: white;
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
                    <form action="{{ route('admin.updateEvent', $event->id) }}" method="POST" enctype="multipart/form-data">
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
                                                <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="nonactive" {{ $event->status == 'nonactive' ? 'selected' : '' }}>Non Active</option>
                                                <option value="pending" {{ $event->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group p-0">
                                            <label for="category_id" class="fw-bold">Kategori Event</label>
                                            <select class="form-select" id="category_id" name="category_id" required>
                                                <option value="" disabled>Pilih Kategori</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group p-0">
                                            <label for="banner" class="fw-bold">Banner / Gambar Event</label>
                                            @if($event->banner)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $event->banner) }}" alt="Banner" class="img-thumbnail" style="max-height: 150px;">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah banner.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group p-0">
                                            <label for="description" class="fw-bold">Deskripsi Event</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan deskripsi lengkap event...">{{ $event->description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">
                                    <span class="text-primary">Pilih Performer</span>
                                </div>
                                <div class="performer-container p-2 mb-4 rounded border shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                    <div class="row g-1">
                                        @php
                                            $selectedPerformers = $event->performers->pluck('id')->toArray();
                                        @endphp
                                        @foreach($performers as $performer)
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                <div class="performer-item" data-genres='@json($performer->genres->pluck("id"))'>
                                                    <input class="form-check-input performer-checkbox" type="checkbox" name="performer_ids[]"
                                                        value="{{ $performer->id }}" id="performer_{{ $performer->id }}"
                                                        {{ in_array($performer->id, $selectedPerformers) ? 'checked' : '' }}>
                                                    <label for="performer_{{ $performer->id }}">
                                                        {{ $performer->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">
                                    <span class="text-primary">Genre Musik (Otomatis Terpilih sesuai Performer)</span>
                                </div>
                                <div id="genre-display-container" class="p-3 mb-4 rounded border bg-light">
                                    <div id="selected-genres-list" class="d-flex flex-wrap">
                                        <span class="text-muted italic">Genre akan muncul secara otomatis berdasarkan performer yang dipilih...</span>
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
                    <button type="button" class="btn btn-link text-danger btn-delete-confirm p-0" onclick="removeTicket('${rowId}')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(newRow);
        }

       function removeTicket(id) {
            const item = document.getElementById(id);

            if (item) {
                swal({
                    title: 'Yakin hapus tiket ini?',
                    text: 'Data akan hilang permanen!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    closeOnConfirm: true
                }, function() {
                    item.remove(); // hapus baris tabel
                });
            }
        }
        document.querySelectorAll('.performer-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateGenres);
        });

        function updateGenres() {
            const selectedGenreIds = new Set();
            document.querySelectorAll('.performer-checkbox:checked').forEach(checkbox => {
                const performerItem = checkbox.closest('.performer-item');
                const genres = JSON.parse(performerItem.getAttribute('data-genres'));
                genres.forEach(id => selectedGenreIds.add(id));
            });

            const displayContainer = document.getElementById('selected-genres-list');
            if (selectedGenreIds.size === 0) {
                displayContainer.innerHTML = '<span class="text-muted italic">Pilih performer terlebih dahulu untuk melihat genre yang terkait...</span>';
                return;
            }

            const genreNamesMap = {
                @php
                    $allGenres = \App\Models\Genre::all();
                    foreach($allGenres as $g) {
                        echo "$g->id: '" . addslashes($g->name) . "',";
                    }
                @endphp
            };

            displayContainer.innerHTML = '';
            selectedGenreIds.forEach(id => {
                const name = genreNamesMap[id] || `Genre ${id}`;
                const badge = document.createElement('span');
                badge.className = 'genre-badge selected';
                badge.textContent = name;
                displayContainer.appendChild(badge);
            });
        }

        // Run on load
        updateGenres();

    </script>
@endsection
