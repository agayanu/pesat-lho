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
                        <i class="material-icons-outlined align-middle me-1">download</i> Unduh Lampiran Utama PH
                    </a>
                </div>
            @endif
            @if($lhoReport->phAttachments && $lhoReport->phAttachments->count() > 0)
                <div class="mt-3 pt-2 border-top">
                    <small class="fw-bold text-muted d-block mb-1">Lampiran Dokumen / Foto dari PH:</small>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($lhoReport->phAttachments as $pAtt)
                            <a href="{{ asset($pAtt->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                @if($pAtt->isImage())
                                    <img src="{{ asset($pAtt->file_path) }}" class="rounded me-1" style="width: 20px; height: 20px; object-fit: cover;" alt="{{ $pAtt->file_label }}">
                                @else
                                    <i class="material-icons-outlined fs-6 me-1">description</i>
                                @endif
                                <span>{{ $pAtt->file_label }}</span>
                            </a>
                        @endforeach
                    </div>
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

            <!-- Multi-File Upload Section Kadep dengan Ekstensi Gambar & Label Wajib -->
            <div class="mb-4 p-3 border rounded bg-light">
                <label class="form-label fw-bold d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span><i class="material-icons-outlined align-middle text-primary me-1">attach_file</i> Upload Lampiran File / Foto Kadep (Bisa lebih dari 1 file)</span>
                    <span class="badge bg-secondary">Format: Foto (.jpg, .jpeg, .png, .webp, .gif) & Dokumen (.pdf, .docx, .doc, .txt) | Maks 10MB</span>
                </label>

                <div class="row g-3">
                    <div class="col-12">
                        <input type="file" id="kadepFileInput" name="kadep_files[]" class="form-control" multiple accept=".pdf,.docx,.doc,.txt,.jpg,.jpeg,.png,.webp,.gif,image/*">
                        <small class="text-muted d-block mt-1">
                            <i class="material-icons-outlined fs-6 align-middle">info</i> Anda dapat memilih beberapa file sekaligus. <strong>Wajib memberikan nama / label pada masing-masing file sebelum klik tombol simpan.</strong>
                        </small>
                    </div>
                </div>

                <!-- Preview file yang dipilih & input label wajib -->
                <div id="kadepFilesPreviewContainer" class="mt-3" style="display: none;">
                    <h6 class="fw-bold text-dark mb-2"><i class="material-icons-outlined fs-6 align-middle text-success">label</i> Beri Nama / Label pada Masing-Masing File:</h6>
                    <div id="kadepFilesList" class="d-flex flex-column gap-2"></div>
                </div>

                <!-- Daftar File Lampiran yang Sudah Tersimpan -->
                @php
                    $kadepAttachments = $lhoReport->kadepAttachments ?? collect();
                @endphp
                @if(($kadepAttachments && $kadepAttachments->count() > 0) || $lhoReport->kadep_file)
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-2"><i class="material-icons-outlined fs-6 align-middle text-primary">folder</i> File Lampiran Kadep yang Sudah Tersimpan:</h6>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-2">
                            @if($lhoReport->kadep_file)
                                <div class="col">
                                    <div class="p-2 border rounded bg-white d-flex align-items-center justify-content-between shadow-sm">
                                        <div class="d-flex align-items-center text-truncate me-2">
                                            <i class="material-icons-outlined text-primary fs-3 me-2">description</i>
                                            <div class="text-truncate">
                                                <strong class="d-block text-truncate">Lampiran Utama</strong>
                                                <small class="text-muted">{{ basename($lhoReport->kadep_file) }}</small>
                                            </div>
                                        </div>
                                        <a href="{{ asset($lhoReport->kadep_file) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="material-icons-outlined">download</i></a>
                                    </div>
                                </div>
                            @endif
                            @foreach($kadepAttachments as $att)
                                <div class="col">
                                    <div class="p-2 border rounded bg-white d-flex align-items-center justify-content-between shadow-sm">
                                        <div class="d-flex align-items-center text-truncate me-2">
                                            @if($att->isImage())
                                                <img src="{{ asset($att->file_path) }}" class="rounded me-2 border" style="width: 45px; height: 45px; object-fit: cover;" alt="{{ $att->file_label }}">
                                            @else
                                                <i class="material-icons-outlined text-danger fs-3 me-2">description</i>
                                            @endif
                                            <div class="text-truncate">
                                                <strong class="d-block text-truncate text-primary" title="{{ $att->file_label }}">{{ $att->file_label }}</strong>
                                                <small class="text-muted text-truncate d-block">{{ $att->file_name }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="{{ asset($att->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Buka / Download">
                                                <i class="material-icons-outlined fs-6 align-middle">open_in_new</i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteAttachment('{{ route('lho.attachments.destroy', $att->id) }}', '{{ $att->file_label }}')" title="Hapus Lampiran">
                                                <i class="material-icons-outlined fs-6 align-middle">delete</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success btn-lg px-4" onclick="return prepareSubmit()">
                    <i class="material-icons-outlined align-middle">send</i> Simpan Catatan & Lampiran Kadep
                </button>
            </div>
        </form>
    </div>
</div>

<form id="deleteAttachmentForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-catatan-siswa" type="button" role="tab">Catatan Siswa ({{ $studentNotes->count() }})</button>
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
                            <tr><th>Jam Ke-</th><th>Guru Tidak Hadir</th><th>Kelas</th><th>Status</th><th>Guru Pengganti</th><th>Tugas</th></tr>
                        </thead>
                        <tbody>
                            @forelse($teacherAbsences as $ta)
                                <tr><td><span class="badge bg-dark">{{ $ta->jam_display }}</span></td><td class="text-danger fw-bold">{{ $ta->teacher->name ?? $ta->teacher_name }}</td><td>{{ $ta->class_code }}</td><td>{{ $ta->status }}</td><td>{{ $ta->substituteTeacher->name ?? $ta->substitute_teacher ?? '-' }}</td><td>{{ $ta->task_description ?? '-' }}</td></tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Tidak ada guru tidak hadir.</td></tr>
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
            <div class="tab-pane fade" id="tab-catatan-siswa" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jam Ke-</th>
                                <th>Kelas</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Guru Pengajar</th>
                                <th>Catatan Siswa</th>
                                <th>Status Koreksi Piket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentNotes as $sn)
                                <tr>
                                    <td><span class="badge bg-primary">Jam ke-{{ $sn->jam_ke }}</span></td>
                                    <td><strong>{{ $sn->class_code }}</strong></td>
                                    <td><code>{{ $sn->student->id_siswa ?? '-' }}</code></td>
                                    <td class="fw-bold">{{ $sn->student->name ?? '-' }}</td>
                                    <td>{{ $sn->teacher->name ?? $sn->teacher_name }}</td>
                                    <td>
                                        <div class="p-2 bg-light border rounded">
                                            {{ $sn->note }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($sn->is_edited_by_piket)
                                            <span class="badge bg-success">Diedit Piket ({{ $sn->piket_user }})</span>
                                            @if($sn->edit_reason)
                                                <br><small class="text-muted">{{ $sn->edit_reason }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Asli Guru Pengajar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada catatan siswa pada tanggal ini.</td></tr>
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

    // Dynamic Multi-File Preview & Label Input Kadep
    const kadepFileInput = document.getElementById('kadepFileInput');
    const kadepFilesPreviewContainer = document.getElementById('kadepFilesPreviewContainer');
    const kadepFilesList = document.getElementById('kadepFilesList');

    if (kadepFileInput) {
        kadepFileInput.addEventListener('change', function () {
            kadepFilesList.innerHTML = '';
            const files = Array.from(this.files);

            if (files.length === 0) {
                kadepFilesPreviewContainer.style.display = 'none';
                return;
            }

            kadepFilesPreviewContainer.style.display = 'block';

            files.forEach((file) => {
                const card = document.createElement('div');
                card.className = 'card border shadow-sm p-3 bg-white mb-2';

                const isImage = file.type.startsWith('image/');
                const sizeKb = (file.size / 1024).toFixed(1);
                const sizeStr = file.size > 1048576 ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' : sizeKb + ' KB';

                let previewHtml = '';
                if (isImage) {
                    const objectUrl = URL.createObjectURL(file);
                    previewHtml = `<img src="${objectUrl}" class="rounded border me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="Preview">`;
                } else {
                    previewHtml = `<div class="rounded border me-3 d-flex align-items-center justify-content-center bg-light text-primary" style="width: 60px; height: 60px;"><i class="material-icons-outlined fs-2">description</i></div>`;
                }

                const defaultLabel = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;

                card.innerHTML = `
                    <div class="row align-items-center g-2">
                        <div class="col-auto d-flex align-items-center">
                            ${previewHtml}
                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark text-truncate" style="max-width: 250px;">${file.name}</span>
                                <span class="badge bg-light text-secondary border">${sizeStr}</span>
                            </div>
                            <div>
                                <label class="form-label fs-7 fw-bold text-danger mb-1">
                                    <i class="material-icons-outlined fs-7 align-middle">label</i> Nama / Label Lampiran (Wajib diisi):
                                </label>
                                <input type="text" name="kadep_file_labels[]" class="form-control form-control-sm kadep-file-label" 
                                    placeholder="Contoh: Foto Rapat, Dokumen Kurikulum, Lampiran Disposisi..." 
                                    value="${defaultLabel}" required>
                            </div>
                        </div>
                    </div>
                `;

                kadepFilesList.appendChild(card);
            });
        });
    }

    function prepareSubmit() {
        if (!isCanvasBlank) {
            let dataURL = canvas.toDataURL('image/png');
            document.getElementById('kadepHandwritingData').value = dataURL;
        }

        // Validate that all selected files have labels
        if (kadepFileInput && kadepFileInput.files.length > 0) {
            const labelInputs = document.querySelectorAll('.kadep-file-label');
            for (let i = 0; i < labelInputs.length; i++) {
                if (!labelInputs[i].value.trim()) {
                    alert('Semua file lampiran yang diupload wajib diberikan Nama / Label sebelum disimpan!');
                    labelInputs[i].focus();
                    labelInputs[i].classList.add('is-invalid');
                    return false;
                } else {
                    labelInputs[i].classList.remove('is-invalid');
                }
            }
        }
        return true;
    }

    function confirmDeleteAttachment(url, label) {
        if (confirm(`Apakah Anda yakin ingin menghapus lampiran "${label}"?`)) {
            let form = document.getElementById('deleteAttachmentForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
@endsection
