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

<!-- Filter Tanggal -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('piket.dashboard') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Tanggal Laporan</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Siswa Tidak Hadir</p>
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
                        <p class="mb-0 text-secondary">Jurnal KBM Terisi</p>
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
                        <p class="mb-0 text-secondary">Guru Tidak Hadir</p>
                        <h4 class="my-1 text-warning">{{ $teacherAbsences->count() }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-warning text-warning ms-auto"><i class="material-icons-outlined">record_voice_over</i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Event / Kejadian</p>
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
                    <i class="material-icons-outlined align-middle me-1">menu_book</i> Jurnal KBM Guru ({{ $teachingJournals->count() }})
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
                                    <td>{{ $j->teacher_name }}</td>
                                    <td>{{ $j->material }}</td>
                                    <td>{{ $j->activity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data KBM yang di-submit hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Presensi Siswa -->
            <div class="tab-pane fade" id="tab-siswa" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Daftar Siswa Tidak Hadir Hari Ini</h6>
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
                                    <td colspan="6" class="text-center text-muted">Tidak ada siswa yang dicatat tidak hadir hari ini.</td>
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
                                    <td class="fw-bold text-danger">{{ $ta->teacher_name }}</td>
                                    <td><strong>{{ $ta->class_code }}</strong></td>
                                    <td><span class="badge bg-warning text-dark">{{ $ta->status }}</span></td>
                                    <td>
                                        @if($ta->substitute_teacher)
                                            <span class="badge bg-success">{{ $ta->substitute_teacher }}</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada Guru Pengganti</span>
                                        @endif
                                    </td>
                                    <td>{{ $ta->task_description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Semua guru hadir atau belum ada pencatatan guru tidak hadir.</td>
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
@endsection
