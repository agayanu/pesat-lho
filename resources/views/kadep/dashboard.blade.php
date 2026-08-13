@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Kepala Departemen</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan & Catatan Kadep</li>
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
        <form action="{{ route('kadep.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
            <label class="form-label fw-bold mb-0 text-nowrap">Tanggal Laporan:</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            <button type="submit" class="btn btn-primary text-nowrap">Buka Rekap</button>
        </form>
        <a href="{{ route('lho.print', ['date' => $date]) }}" target="_blank" class="btn btn-outline-dark">
            <i class="material-icons-outlined align-middle me-1">print</i> Cetak LHO Terpadu
        </a>
    </div>
</div>

<!-- Catatan dari Kepala Sekolah jika ada -->
@if(!empty($lhoReport->kepsek_notes))
    <div class="card border-start border-0 border-4 border-info mb-4 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <i class="material-icons-outlined text-info fs-1 me-3">school</i>
                <div>
                    <h5 class="card-title text-info mb-1">Arahan / Catatan dari Kepala Sekolah</h5>
                    <p class="mb-1 text-dark">{{ $lhoReport->kepsek_notes }}</p>
                    <small class="text-muted">Oleh: {{ $lhoReport->kepsek_user ?? 'Kepala Sekolah' }}</small>
                    @if($lhoReport->kepsek_handwriting_img)
                        <div class="mt-2">
                            <img src="{{ asset($lhoReport->kepsek_handwriting_img) }}" class="img-fluid border rounded" style="max-height: 120px;" alt="Tulis Tangan Kepsek">
                        </div>
                    @endif
                    @if($lhoReport->kepsek_file)
                        <div class="mt-2">
                            <a href="{{ asset($lhoReport->kepsek_file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="material-icons-outlined align-middle me-1">download</i> Unduh Lampiran Kepsek
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Catatan dari PH -->
@if(!empty($lhoReport->ph_notes))
    <div class="card border-start border-0 border-4 border-primary mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-primary mb-1"><i class="material-icons-outlined align-middle me-1">fact_check</i> Catatan Pengawasan Global PH</h5>
            <p class="mb-1 text-dark">{{ $lhoReport->ph_notes }}</p>
            <small class="text-muted">Petugas PH: {{ $lhoReport->ph_user ?? '-' }}</small>
            @if($lhoReport->ph_handwriting_img)
                <div class="mt-2">
                    <img src="{{ asset($lhoReport->ph_handwriting_img) }}" class="img-fluid border rounded" style="max-height: 120px;" alt="Tulis Tangan PH">
                </div>
            @endif
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

<!-- Form Catatan & Canvas Kadep -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">supervisor_account</i> Form Catatan, Tulis Tangan, & Lampiran Kepala Departemen</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('kadep.notes.store') }}" method="POST" enctype="multipart/form-data" id="kadepForm">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="kadep_handwriting_data" id="kadepHandwritingData">

            <div class="mb-3">
                <label class="form-label fw-bold">Catatan Pengawasan Global Kepala Departemen <span class="text-danger">*</span></label>
                <textarea name="kadep_global_notes" class="form-control" rows="3" placeholder="Tuliskan catatan evaluasi pengawasan seluruh kegiatan sekolah..." required>{{ old('kadep_global_notes', $lhoReport->kadep_global_notes) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Catatan Khusus untuk PH (Penanggung Jawab Harian)</label>
                <textarea name="kadep_ph_notes" class="form-control" rows="2" placeholder="Tuliskan instruksi / umpan balik khusus yang dapat dibaca langsung oleh PH...">{{ old('kadep_ph_notes', $lhoReport->kadep_ph_notes) }}</textarea>
            </div>

            <!-- Canvas Tulis Tangan Kadep -->
            <div class="mb-4">
                <label class="form-label fw-bold d-flex align-items-center justify-content-between">
                    <span><i class="material-icons-outlined align-middle text-primary me-1">draw</i> Canvas Catatan Tulis Tangan (Tablet / Stylus Kadep)</span>
                    <small class="text-muted">Tulis catatan tangan atau tanda tangan langsung di canvas ini</small>
                </label>
                
                <div class="p-3 border rounded bg-light">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="fw-bold fs-7">Warna:</span>
                        <button type="button" class="btn btn-sm btn-dark px-3 py-1" onclick="setPenColor('#000000')">Hitam</button>
                        <button type="button" class="btn btn-sm btn-primary px-3 py-1" onclick="setPenColor('#0d6efd')">Biru</button>
                        <button type="button" class="btn btn-sm btn-danger px-3 py-1" onclick="setPenColor('#dc3545')">Merah</button>
                        
                        <span class="ms-3 fw-bold fs-7">Ketebalan:</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="setLineWidth(2)">Tipis</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="setLineWidth(4)">Sedang</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="setLineWidth(8)">Tebal</button>

                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="clearCanvas()">
                            <i class="material-icons-outlined align-middle fs-6">delete_sweep</i> Bersihkan Canvas
                        </button>
                    </div>

                    <div class="position-relative border rounded bg-white" style="touch-action: none;">
                        <canvas id="handwritingCanvas" width="900" height="250" class="w-100 style-canvas"></canvas>
                    </div>

                    @if($lhoReport->kadep_handwriting_img)
                        <div class="mt-3 p-2 bg-white border rounded">
                            <small class="fw-bold d-block text-success mb-1"><i class="material-icons-outlined align-middle me-1">check_circle</i> Catatan Tulis Tangan Kadep Tersimpan:</small>
                            <img src="{{ asset($lhoReport->kadep_handwriting_img) }}" class="img-fluid border rounded" style="max-height: 180px;" alt="Tulis Tangan Kadep">
                        </div>
                    @endif
                </div>
            </div>

            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Upload File Lampiran Kadep (Opsional)</label>
                    <input type="file" name="kadep_file" class="form-control" accept=".pdf,.docx,.doc,.txt">
                    <small class="text-muted">Format: .pdf, .docx, .doc, .txt (Maks 5MB)</small>
                </div>
                <div class="col-md-6">
                    @if($lhoReport->kadep_file)
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">Lampiran Kadep Ter-upload:</small>
                            <a href="{{ asset($lhoReport->kadep_file) }}" target="_blank" class="fw-bold text-primary">
                                <i class="material-icons-outlined align-middle me-1">description</i> Unduh File Lampiran Kadep
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success btn-lg px-4" onclick="prepareSubmit()">
                    <i class="material-icons-outlined align-middle">send</i> Simpan Catatan & Lampiran Kadep
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Monitoring Operational -->
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">visibility</i> Monitoring Data Harian Sekolah</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-kbm" type="button" role="tab">Jurnal KBM ({{ $teachingJournals->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-siswa" type="button" role="tab">Presensi Siswa ({{ $studentAbsences->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-guru" type="button" role="tab">Presensi Guru ({{ $teacherAbsences->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-special" type="button" role="tab">Kegiatan Spesifik ({{ $specialReports->count() }})</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-events" type="button" role="tab">Event Sekolah ({{ $schoolEvents->count() }})</button>
            </li>
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
                                <tr><td>Jam ke-{{ $j->jam_ke }}</td><td>{{ $j->class_code }}</td><td>{{ $j->teacher->name ?? $j->teacher_name }}</td><td>{{ $j->material }}</td><td>{{ $j->activity }}</td></tr>
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
                                <tr><td class="text-danger fw-bold">{{ $ta->teacher->name ?? $ta->teacher_name }}</td><td>{{ $ta->class_code }}</td><td>{{ $ta->status }}</td><td>{{ $ta->substituteTeacher->name ?? $ta->substitute_teacher ?? '-' }}</td><td>{{ $ta->task_description ?? '-' }}</td></tr>
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

@section('scripts')
<script>
    let canvas = document.getElementById('handwritingCanvas');
    let ctx = canvas.getContext('2d');
    let isDrawing = false;
    let currentColor = '#000000';
    let currentWidth = 3;
    let isCanvasBlank = true;

    function resizeCanvas() {
        let rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = 250;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = currentColor;
        ctx.lineWidth = currentWidth;
    }

    window.addEventListener('resize', resizeCanvas);
    setTimeout(resizeCanvas, 300);

    function setPenColor(color) {
        currentColor = color;
        ctx.strokeStyle = currentColor;
    }

    function setLineWidth(width) {
        currentWidth = width;
        ctx.lineWidth = currentWidth;
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        isCanvasBlank = true;
    }

    function getPos(e) {
        let rect = canvas.getBoundingClientRect();
        let clientX = e.touches ? e.touches[0].clientX : e.clientX;
        let clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        isCanvasBlank = false;
        let pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        let pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function stopDrawing() {
        if (isDrawing) {
            ctx.closePath();
            isDrawing = false;
        }
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing, {passive: false});
    canvas.addEventListener('touchmove', draw, {passive: false});
    canvas.addEventListener('touchend', stopDrawing);

    function prepareSubmit() {
        if (!isCanvasBlank) {
            let dataURL = canvas.toDataURL('image/png');
            document.getElementById('kadepHandwritingData').value = dataURL;
        }
    }
</script>
@endsection
@endsection
