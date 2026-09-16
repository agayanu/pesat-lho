@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Guru Piket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Monitoring Piket Harian</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<!-- Filter Tanggal, Tingkat, & Kelas -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('piket.dashboard') }}" method="GET" class="row g-3 align-items-end" id="piketFilterForm">
            <div class="col-md-3">
                <label class="form-label fw-bold">Pilih Tanggal Laporan</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Filter Tingkat</label>
                <select name="tingkat" id="filterTingkat" class="form-select">
                    <option value="">-- Semua Tingkat --</option>
                    <option value="10" {{ ($selectedTingkat ?? '') == '10' ? 'selected' : '' }}>Tingkat 10</option>
                    <option value="11" {{ ($selectedTingkat ?? '') == '11' ? 'selected' : '' }}>Tingkat 11</option>
                    <option value="12" {{ ($selectedTingkat ?? '') == '12' ? 'selected' : '' }}>Tingkat 12</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Filter Kelas</label>
                <select name="class_code" id="filterClass" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classList as $c)
                        @php
                            $tingkatCode = '';
                            if (str_starts_with(strtoupper($c->code), 'XII') || str_starts_with($c->code, '12')) {
                                $tingkatCode = '12';
                            } elseif (str_starts_with(strtoupper($c->code), 'XI') || str_starts_with($c->code, '11')) {
                                $tingkatCode = '11';
                            } elseif (str_starts_with(strtoupper($c->code), 'X') || str_starts_with($c->code, '10')) {
                                $tingkatCode = '10';
                            }
                        @endphp
                        <option value="{{ $c->code }}" data-tingkat="{{ $tingkatCode }}" {{ ($selectedClass ?? '') == $c->code ? 'selected' : '' }}>
                            {{ $c->code }} ({{ $c->school }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="material-icons-outlined align-middle fs-6 me-1">filter_alt</i> Filter Data
                </button>
                @if(!empty($selectedTingkat) || !empty($selectedClass))
                    <a href="{{ route('piket.dashboard', ['date' => $date]) }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="material-icons-outlined align-middle fs-6">restart_alt</i> Reset
                    </a>
                @endif
            </div>
        </form>

        @if(!empty($selectedTingkat) || !empty($selectedClass))
            <div class="mt-2">
                <small class="text-muted">Filter aktif: </small>
                @if(!empty($selectedTingkat))
                    <span class="badge bg-primary me-1">Tingkat {{ $selectedTingkat }}</span>
                @endif
                @if(!empty($selectedClass))
                    <span class="badge bg-info text-dark me-1">Kelas {{ $selectedClass }}</span>
                @endif
            </div>
        @endif
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Summary Cards -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-3 mb-4">
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Siswa Tdk Hadir</p>
                        <h4 class="my-1 text-danger">{{ $studentAbsences->count() }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-danger text-danger ms-auto"><i class="material-icons-outlined">person_off</i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Jurnal KBM</p>
                        <h4 class="my-1 text-info">{{ $teachingJournals->count() }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-info text-info ms-auto"><i class="material-icons-outlined">menu_book</i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Guru Tdk Hadir</p>
                        <h4 class="my-1 text-warning">{{ $teacherAbsences->count() }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-warning text-warning ms-auto"><i class="material-icons-outlined">record_voice_over</i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Catatan Siswa</p>
                        <h4 class="my-1 text-primary">{{ $studentNotes->count() }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-primary text-primary ms-auto"><i class="material-icons-outlined">note_alt</i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Event Sekolah</p>
                        <h4 class="my-1 text-success">{{ $schoolEvents->count() }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-success text-success ms-auto"><i class="material-icons-outlined">event</i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Panel Monitoring -->
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-kbm" type="button" role="tab">
                    <i class="material-icons-outlined align-middle me-1">menu_book</i> Jurnal KBM ({{ $teachingJournals->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-siswa" type="button" role="tab">
                    <i class="material-icons-outlined align-middle me-1">person_off</i> Presensi Siswa ({{ $studentAbsences->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-guru" type="button" role="tab">
                    <i class="material-icons-outlined align-middle me-1">record_voice_over</i> Presensi Guru ({{ $teacherAbsences->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-catatan" type="button" role="tab">
                    <i class="material-icons-outlined align-middle me-1">note_alt</i> Catatan Siswa ({{ $studentNotes->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-events" type="button" role="tab">
                    <i class="material-icons-outlined align-middle me-1">event</i> Event Sekolah ({{ $schoolEvents->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Tab Jurnal KBM -->
            <div class="tab-pane fade show active" id="tab-kbm" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jam Ke-</th>
                                <th>Kelas</th>
                                <th>Guru Pengajar</th>
                                <th>Materi Pembelajaran</th>
                                <th>Deskripsi Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachingJournals as $j)
                                <tr>
                                    <td><span class="badge bg-primary">Jam ke-{{ $j->jam_ke }}</span></td>
                                    <td><strong>{{ $j->class_code }}</strong></td>
                                    <td>{{ $j->teacher->name ?? $j->teacher_name }}</td>
                                    <td>{{ $j->material }}</td>
                                    <td>{{ $j->activity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data KBM yang di-submit pada filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Presensi Siswa -->
            <div class="tab-pane fade" id="tab-siswa" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Daftar Siswa Tidak Hadir</h6>
                    <a href="{{ route('piket.student-absences', ['date' => $date]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="material-icons-outlined me-1">edit</i> Buka Modul Koreksi Presensi
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jam Ke-</th>
                                <th>Kelas</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Di-edit Guru Piket?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentAbsences as $abs)
                                <tr>
                                    <td>Jam ke-{{ $abs->jam_ke }}</td>
                                    <td><strong>{{ $abs->class_code }}</strong></td>
                                    <td><code>{{ $abs->student->id_siswa ?? '-' }}</code></td>
                                    <td>{{ $abs->student->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $abs->status == 'Alpha' ? 'bg-danger' : ($abs->status == 'Izin' ? 'bg-warning text-dark' : 'bg-info') }}">
                                             {{ $abs->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($abs->is_edited_by_piket)
                                            <span class="badge bg-success">Dikoreksi oleh {{ $abs->piket_user }}</span>
                                            <br><small class="text-muted">Alasan: {{ $abs->edit_reason }}</small>
                                        @else
                                            <span class="badge bg-secondary">Asli dari Guru Kelas</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada siswa yang dicatat tidak hadir pada filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Presensi Guru -->
            <div class="tab-pane fade" id="tab-guru" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Guru Tidak Hadir & Pengganti / Tugas Kelas</h6>
                    <a href="{{ route('piket.teacher-absences', ['date' => $date]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="material-icons-outlined me-1">add</i> Kelola Presensi Guru
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jam Ke-</th>
                                <th>Guru Tidak Hadir</th>
                                <th>Kelas</th>
                                <th>Status Ketidakhadiran</th>
                                <th>Guru Pengganti</th>
                                <th>Tugas Kelas (Jika Tak Ada Pengganti)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacherAbsences as $ta)
                                <tr>
                                    <td><span class="badge bg-dark">{{ $ta->jam_display }}</span></td>
                                    <td class="fw-bold text-danger">{{ $ta->teacher->name ?? $ta->teacher_name }}</td>
                                    <td><strong>{{ $ta->class_code }}</strong></td>
                                    <td><span class="badge bg-warning text-dark">{{ $ta->status }}</span></td>
                                    <td>
                                        @if($ta->substituteTeacher || $ta->substitute_teacher)
                                            <span class="badge bg-success">{{ $ta->substituteTeacher->name ?? $ta->substitute_teacher }}</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada Guru Pengganti</span>
                                        @endif
                                    </td>
                                    <td>{{ $ta->task_description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Semua guru hadir atau belum ada pencatatan guru tidak hadir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Catatan Khusus Siswa (Fitur Catatan Siswa) -->
            <div class="tab-pane fade" id="tab-catatan" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Daftar Catatan Khusus Siswa Hari Ini (Diberikan oleh Guru Kelas / Pengajar)</h6>
                    <span class="badge bg-primary">Khusus Guru Piket: Dapat Mengedit & Menghapus Catatan</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 100px;">Jam Ke-</th>
                                <th>Kelas</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Guru Pencatat</th>
                                <th>Isi Catatan</th>
                                <th>Status Koreksi</th>
                                <th style="width: 120px;" class="text-center">Aksi (Piket)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentNotes as $sn)
                                <tr>
                                    <td><span class="badge bg-primary">Jam ke-{{ $sn->jam_ke }}</span></td>
                                    <td><strong>{{ $sn->class_code }}</strong></td>
                                    <td><code>{{ $sn->student->id_siswa ?? '-' }}</code></td>
                                    <td class="fw-bold">{{ $sn->student->name ?? '-' }}</td>
                                    <td>{{ $sn->teacher->name ?? $sn->teacher_name ?? $sn->created_by }}</td>
                                    <td>
                                        <div class="p-2 border rounded bg-light text-dark">
                                            {{ $sn->note }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($sn->is_edited_by_piket)
                                            <span class="badge bg-warning text-dark">Diedit Guru Piket ({{ $sn->piket_user }})</span>
                                            <br><small class="text-muted">Alasan: {{ $sn->edit_reason }}</small>
                                        @else
                                            <span class="badge bg-secondary">Asli dari Guru</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#editNoteModal{{ $sn->id }}" title="Edit Catatan">
                                                <i class="material-icons-outlined fs-6 align-middle">edit</i>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteNoteModal{{ $sn->id }}" title="Hapus Catatan">
                                                <i class="material-icons-outlined fs-6 align-middle">delete</i>
                                            </button>
                                        </div>

                                        <!-- Modal Edit Catatan Piket -->
                                        <div class="modal fade text-start" id="editNoteModal{{ $sn->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('piket.student-notes.update', $sn->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title text-dark"><i class="material-icons-outlined align-middle me-1">edit</i> Edit Catatan Siswa (Guru Piket)</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Siswa</label>
                                                                <input type="text" class="form-control" value="{{ $sn->student->name ?? '-' }} ({{ $sn->class_code }})" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Isi Catatan <span class="text-danger">*</span></label>
                                                                <textarea name="note" class="form-control" rows="3" required>{{ old('note', $sn->note) }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Alasan Perubahan oleh Piket <span class="text-danger">*</span></label>
                                                                <input type="text" name="edit_reason" class="form-control" placeholder="Contoh: Klarifikasi wali kelas / salah input..." required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning text-dark">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Catatan Piket -->
                                        <div class="modal fade text-start" id="deleteNoteModal{{ $sn->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('piket.student-notes.destroy', $sn->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title text-white"><i class="material-icons-outlined align-middle me-1">delete</i> Hapus Catatan Siswa</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus catatan untuk siswa <strong>{{ $sn->student->name ?? '-' }}</strong>?</p>
                                                            <div class="p-2 border rounded bg-light mb-3">
                                                                <em>"{{ $sn->note }}"</em>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Alasan Penghapusan (Opsional)</label>
                                                                <input type="text" name="edit_reason" class="form-control" placeholder="Contoh: Pembatalan / catatan tidak valid">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Ya, Hapus Catatan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada catatan siswa pada tanggal/filter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Event Sekolah -->
            <div class="tab-pane fade" id="tab-events" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Acara / Event & Kejadian Sekolah Hari Ini</h6>
                    <a href="{{ route('piket.school-events', ['date' => $date]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="material-icons-outlined me-1">add</i> Tambah Event / Kejadian
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Kategori</th>
                                <th>Judul Acara / Kejadian</th>
                                <th>Deskripsi Detail</th>
                                <th>Petugas Piket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schoolEvents as $ev)
                                <tr>
                                    <td><span class="badge bg-dark">{{ $ev->category }}</span></td>
                                    <td class="fw-bold">{{ $ev->title }}</td>
                                    <td>{{ $ev->description }}</td>
                                    <td><small class="text-muted">{{ $ev->piket_user }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada acara atau kejadian khusus yang dicatat hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTingkat = document.getElementById('filterTingkat');
        const filterClass = document.getElementById('filterClass');

        if (filterTingkat && filterClass) {
            function updateClassOptions() {
                const selectedTingkat = filterTingkat.value;
                const options = filterClass.querySelectorAll('option');

                options.forEach(function(opt) {
                    if (!opt.value) return; // Keep "-- Semua Kelas --"
                    const optTingkat = opt.getAttribute('data-tingkat');
                    if (!selectedTingkat || optTingkat === selectedTingkat) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                        if (opt.selected) {
                            filterClass.value = '';
                        }
                    }
                });
            }

            filterTingkat.addEventListener('change', updateClassOptions);
            updateClassOptions();
        }
    });
</script>
@endsection
