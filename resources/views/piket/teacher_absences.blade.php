@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Guru Piket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('piket.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Absensi Guru & Guru Pengganti</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">person_off</i> Input Guru Tidak Hadir & Pengganti / Tugas Kelas</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('piket.teacher-absences.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Guru Tidak Hadir <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Kelas Ditinggalkan <span class="text-danger">*</span></label>
                    <select name="class_code" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classList as $c)
                            <option value="{{ $c->code }}">{{ $c->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status Ketidakhadiran <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Dinas">Dinas Luar</option>
                        <option value="Alpha">Alpha</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Guru Pengganti (Jika Ada)</label>
                    <select name="substitute_teacher_id" class="form-select">
                        <option value="">-- Pilih Guru Pengganti (Opsional) --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tugas Kelas (Jika Tidak Ada Guru Pengganti)</label>
                    <input type="text" name="task_description" class="form-control" placeholder="Contoh: Kerjakan Modul Bab 3 Hal 45-50">
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="material-icons-outlined align-middle">save</i> Simpan Data Presensi Guru
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">list</i> Daftar Guru Tidak Hadir ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Guru Tidak Hadir</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Guru Pengganti</th>
                        <th>Tugas Kelas Diberikan</th>
                        <th>Petugas Piket</th>
                        <th style="width: 80px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teacherAbsences as $index => $ta)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-danger">{{ $ta->teacher->name ?? $ta->teacher_name }}</td>
                            <td><strong>{{ $ta->class_code }}</strong></td>
                            <td><span class="badge bg-warning text-dark">{{ $ta->status }}</span></td>
                            <td>
                                @if($ta->substituteTeacher || $ta->substitute_teacher)
                                    <span class="badge bg-success fs-6">{{ $ta->substituteTeacher->name ?? $ta->substitute_teacher }}</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Ada Pengganti</span>
                                @endif
                            </td>
                            <td>{{ $ta->task_description ?? '-' }}</td>
                            <td><small class="text-muted">{{ $ta->piket_user }}</small></td>
                            <td class="text-center">
                                <form action="{{ route('piket.teacher-absences.destroy', $ta->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan presensi guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada pencatatan guru tidak hadir untuk tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
