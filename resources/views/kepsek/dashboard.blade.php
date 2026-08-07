@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Kepala Sekolah</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan & Arahan Kepsek</li>
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

<!-- Date Filter & Quick Print -->
<div class="card mb-4">
    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
        <form action="{{ route('kepsek.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
            <label class="form-label fw-bold mb-0 text-nowrap">Tanggal Laporan:</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            <button type="submit" class="btn btn-primary text-nowrap">Buka Rekap</button>
        </form>
        <a href="{{ route('lho.print', ['date' => $date]) }}" target="_blank" class="btn btn-dark">
            <i class="material-icons-outlined align-middle me-1">print</i> Cetak LHO Terpadu
        </a>
    </div>
</div>

<!-- Catatan dari PH -->
@if(!empty($lhoReport->ph_notes))
    <div class="card border-start border-0 border-4 border-primary mb-3 shadow-sm">
        <div class="card-body">
            <h6 class="card-title text-primary mb-1"><i class="material-icons-outlined align-middle me-1">fact_check</i> Catatan Pengawasan Global PH</h6>
            <p class="mb-1 text-dark">{{ $lhoReport->ph_notes }}</p>
            <small class="text-muted">Petugas PH: {{ $lhoReport->ph_user ?? '-' }}</small>
            @if($lhoReport->ph_file)
                <div class="mt-2">
                    <a href="{{ asset($lhoReport->ph_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="material-icons-outlined align-middle me-1">download</i> Unduh Lampiran PH
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Catatan dari Kepala Departemen -->
@if(!empty($lhoReport->kadep_global_notes))
    <div class="card border-start border-0 border-4 border-warning mb-4 shadow-sm">
        <div class="card-body">
            <h6 class="card-title text-warning mb-1"><i class="material-icons-outlined align-middle me-1">supervisor_account</i> Catatan Pengawasan Kepala Departemen</h6>
            <p class="mb-1 text-dark">{{ $lhoReport->kadep_global_notes }}</p>
            <small class="text-muted">Kepala Departemen: {{ $lhoReport->kadep_user ?? '-' }}</small>
            @if($lhoReport->kadep_file)
                <div class="mt-2">
                    <a href="{{ asset($lhoReport->kadep_file) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                        <i class="material-icons-outlined align-middle me-1">download</i> Unduh Lampiran Kadep
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Form Catatan & Arahan Kepala Sekolah -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">school</i> Form Arahan & Catatan Kepala Sekolah</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('kepsek.notes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="mb-3">
                <label class="form-label fw-bold">Arahan / Catatan Kepala Sekolah (Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}) <span class="text-danger">*</span></label>
                <textarea name="kepsek_notes" class="form-control" rows="4" placeholder="Tuliskan arahan, tindak lanjut, atau tanggapan Kepala Sekolah terhadap laporan harian ini..." required>{{ old('kepsek_notes', $lhoReport->kepsek_notes) }}</textarea>
            </div>

            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Upload File Lampiran Kepsek (Opsional)</label>
                    <input type="file" name="kepsek_file" class="form-control" accept=".pdf,.docx,.doc,.txt">
                    <small class="text-muted">Format: .pdf, .docx, .doc, .txt (Maks 5MB)</small>
                </div>
                <div class="col-md-6">
                    @if($lhoReport->kepsek_file)
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Lampiran Kepsek Ter-upload:</small>
                            <a href="{{ asset($lhoReport->kepsek_file) }}" target="_blank" class="fw-bold text-primary">
                                <i class="material-icons-outlined align-middle me-1">description</i> Unduh File Lampiran Kepsek
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success btn-lg px-4">
                    <i class="material-icons-outlined align-middle">send</i> Simpan Arahan & Lampiran Kepsek
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Monitoring Operational -->
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">visibility</i> Rekapitulasi Seluruh Data Harian Sekolah</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-kbm" type="button" role="tab">Jurnal KBM ({{ $teachingJournals->count() }})</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-siswa" type="button" role="tab">Presensi Siswa ({{ $studentAbsences->count() }})</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-guru" type="button" role="tab">Presensi Guru ({{ $teacherAbsences->count() }})</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-special" type="button" role="tab">Kegiatan Spesifik ({{ $specialReports->count() }})</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-events" type="button" role="tab">Event Sekolah ({{ $schoolEvents->count() }})</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-kbm" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr><th>Jam Ke-</th><th>Kelas</th><th>Guru Pengajar</th><th>Materi</th><th>Kegiatan</th></tr>
                        </thead>
                        <tbody>
                            @forelse($teachingJournals as $j)
                                <tr><td>Jam ke-{{ $j->jam_ke }}</td><td>{{ $j->class_code }}</td><td>{{ $j->teacher_name }}</td><td>{{ $j->material }}</td><td>{{ $j->activity }}</td></tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada KBM.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-siswa" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr><th>Jam Ke-</th><th>Kelas</th><th>NIS</th><th>Nama Siswa</th><th>Status</th><th>Pencatat / Koreksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($studentAbsences as $abs)
                                <tr>
                                    <td>Jam ke-{{ $abs->jam_ke }}</td><td>{{ $abs->class_code }}</td><td><code>{{ $abs->student->id_siswa ?? '-' }}</code></td><td>{{ $abs->student->name ?? '-' }}</td>
                                    <td><span class="badge {{ $abs->status == 'Alpha' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $abs->status }}</span></td>
                                    <td>{{ $abs->is_edited_by_piket ? 'Koreksi Piket ('.$abs->piket_user.')' : $abs->user }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Tidak ada siswa tidak hadir.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-guru" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr><th>Guru Tidak Hadir</th><th>Kelas</th><th>Status</th><th>Guru Pengganti</th><th>Tugas</th></tr>
                        </thead>
                        <tbody>
                            @forelse($teacherAbsences as $ta)
                                <tr><td class="text-danger fw-bold">{{ $ta->teacher_name }}</td><td>{{ $ta->class_code }}</td><td>{{ $ta->status }}</td><td>{{ $ta->substitute_teacher ?? '-' }}</td><td>{{ $ta->task_description ?? '-' }}</td></tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Tidak ada guru tidak hadir.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-special" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr><th>Unit</th><th>Peserta</th><th>Materi</th><th>Pembimbing</th><th>Notes</th></tr>
                        </thead>
                        <tbody>
                            @forelse($specialReports as $sr)
                                <tr><td>{{ $sr->unit_name }}</td><td>{{ $sr->class_or_participants }}</td><td>{{ $sr->material_activity }}</td><td>{{ $sr->pic_teacher }}</td><td>{{ $sr->notes ?? '-' }}</td></tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada kegiatan spesifik.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-events" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr><th>Kategori</th><th>Judul Event</th><th>Deskripsi</th><th>Piket</th></tr>
                        </thead>
                        <tbody>
                            @forelse($schoolEvents as $ev)
                                <tr><td>{{ $ev->category }}</td><td>{{ $ev->title }}</td><td>{{ $ev->description }}</td><td>{{ $ev->piket_user }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada event.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
