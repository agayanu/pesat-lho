@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">PJ Kegiatan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Kegiatan Spesifik</li>
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

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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

<!-- Form Input Laporan Kegiatan Spesifik -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">assignment</i> Form Laporan Penanggung Jawab Kegiatan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('special-activities.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Unit / Jenis Kegiatan Spesifik <span class="text-danger">*</span></label>
                    <select name="unit_name" class="form-select" required>
                        <option value="">-- Pilih Unit Kegiatan --</option>
                        @foreach($unitList as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kelas / Jumlah Peserta <span class="text-danger">*</span></label>
                    <input type="text" name="class_or_participants" class="form-control" placeholder="Contoh: Kelas X IPA 1 (32 Siswa)" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Pembimbing / Guru Bertugas <span class="text-danger">*</span></label>
                    <select name="pic_teacher" class="form-select" required>
                        <option value="">-- Pilih Pembimbing / Guru Bertugas --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Berjalan lancar, sarana lengkap">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-bold">Materi / Deskripsi Kegiatan Spesifik <span class="text-danger">*</span></label>
                    <textarea name="material_activity" class="form-control" rows="3" placeholder="Tuliskan judul materi, urutan kegiatan, atau hasil pelaksanaan..." required></textarea>
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="material-icons-outlined align-middle">send</i> Simpan Laporan Kegiatan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Laporan Kegiatan -->
<div class="card">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">list_alt</i> Rekap Laporan Kegiatan Spesifik ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h5>
        
        <form action="{{ route('special-activities.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <input type="hidden" name="date" value="{{ $date }}">
            <select name="unit_name" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Semua Unit --</option>
                @foreach($unitList as $unit)
                    <option value="{{ $unit }}" {{ $selectedUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Unit Kegiatan</th>
                        <th>Kelas / Peserta</th>
                        <th>Materi / Deskripsi Kegiatan</th>
                        <th>Pembimbing / Guru</th>
                        <th>Keterangan</th>
                        <th>Pencatat</th>
                        <th style="width: 100px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $r)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-primary fs-6">{{ $r->unit_name }}</span></td>
                            <td><strong>{{ $r->class_or_participants }}</strong></td>
                            <td>{{ $r->material_activity }}</td>
                            <td><span class="badge bg-info text-dark">{{ $r->pic_teacher }}</span></td>
                            <td>{{ $r->notes ?? '-' }}</td>
                            <td><small class="text-muted">{{ $r->user }}</small></td>
                            <td class="text-center">
                                @if(Auth::user()->name == $r->user || Auth::user()->position == 1 || Auth::user()->position == 4)
                                    <form action="{{ route('special-activities.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="material-icons-outlined">delete</i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">ReadOnly</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada laporan kegiatan spesifik yang terdaftar pada tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
