@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Wali Kelas</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Monitoring Kelas Binaan</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<!-- Header Filter & Class Info -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('walikelas.dashboard') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal Pelaporan</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">Pilih Kelas Binaan</label>
                <select name="class_code" class="form-select">
                    @foreach($classList as $c)
                        <option value="{{ $c->code }}" {{ $selectedClassCode == $c->code ? 'selected' : '' }}>
                            {{ $c->code }} ({{ $c->school }}) {{ $assignedClass && $assignedClass->id == $c->id ? ' - [Kelas Anda]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="material-icons-outlined align-middle me-1">search</i> Tampilkan Rekap
                </button>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-info d-flex align-items-center mb-4" role="alert">
    <i class="material-icons-outlined me-2 fs-4">visibility</i>
    <div>
        <strong>Mode Pemantauan Wali Kelas (ReadOnly):</strong> Anda dapat memantau seluruh catatan presensi siswa dan materi/kegiatan KBM yang diajarkan oleh guru-guru di kelas binaan Anda. Sesuai aturan, data bersifat ReadOnly (tidak dapat diubah atau dihapus).
    </div>
</div>

@if($selectedClassCode)
    <!-- Section 1: Jurnal Mengajar (KBM) Guru di Kelas Binaan -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">menu_book</i> Jurnal KBM Guru di Kelas {{ $selectedClassCode }}</h5>
            <span class="badge bg-light text-dark">Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 80px;">Jam Ke-</th>
                            <th style="width: 200px;">Guru Pengajar</th>
                            <th>Materi Pembelajaran</th>
                            <th>Deskripsi Kegiatan</th>
                            <th style="width: 140px;">Waktu Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachingJournals as $j)
                            <tr>
                                <td><span class="badge bg-primary fs-6">Jam ke-{{ $j->jam_ke }}</span></td>
                                <td><strong class="text-dark">{{ $j->teacher_name }}</strong></td>
                                <td>{{ $j->material }}</td>
                                <td>{{ $j->activity }}</td>
                                <td><small class="text-muted">{{ $j->created_at->format('H:i') }} WIB</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada jurnal mengajar KBM yang di-submit oleh guru pengajar di kelas ini untuk tanggal tersebut.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section 2: Rekapitulasi Presensi Ketidakhadiran Siswa -->
    <div class="card">
        <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">person_off</i> Presensi Ketidakhadiran Siswa Kelas {{ $selectedClassCode }}</h5>
            <span class="badge bg-warning text-dark">Total Tidak Hadir: {{ $studentAbsences->count() }} Siswa</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Jam Ke-</th>
                            <th>NIS / ID</th>
                            <th>Nama Siswa</th>
                            <th>Status Presensi</th>
                            <th>Pencatat / Status Koreksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentAbsences as $index => $abs)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-secondary">Jam ke-{{ $abs->jam_ke }}</span></td>
                                <td><code>{{ $abs->student->id_siswa ?? '-' }}</code></td>
                                <td class="fw-bold">{{ $abs->student->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $abs->status == 'Alpha' ? 'bg-danger' : ($abs->status == 'Izin' ? 'bg-warning text-dark' : 'bg-info') }} fs-6">
                                        {{ $abs->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($abs->is_edited_by_piket)
                                        <span class="badge bg-success">Dikoreksi Piket ({{ $abs->piket_user }})</span>
                                        <br><small class="text-muted">Ket: {{ $abs->edit_reason }}</small>
                                    @else
                                        <small class="text-muted">Guru Pengajar: {{ $abs->user }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nihil (Seluruh siswa dilaporkan hadir di kelas ini).</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
