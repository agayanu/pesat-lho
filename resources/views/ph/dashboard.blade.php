@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">PH (Penanggung Jawab Harian)</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan & Catatan PH</li>
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

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('ph.dashboard') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Tanggal Laporan Harian</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buka Rekap</button>
            </div>
        </form>
    </div>
</div>

<!-- Display Notes from Kepala Departemen if any -->
@if(!empty($lhoReport->kadep_ph_notes))
    <div class="card border-start border-0 border-4 border-warning mb-4 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <i class="material-icons-outlined text-warning fs-1 me-3">record_voice_over</i>
                <div>
                    <h5 class="card-title text-warning mb-1">Catatan Khusus dari Kepala Departemen untuk PH</h5>
                    <p class="mb-1 text-dark">{{ $lhoReport->kadep_ph_notes }}</p>
                    <small class="text-muted">Dikirim oleh: {{ $lhoReport->kadep_user ?? 'Kepala Departemen' }}</small>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Form Catatan Global, Canvas Tulis Tangan, & File Upload PH -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">rate_review</i> Form Catatan Pengawasan, Tulis Tangan, & Lampiran File PH</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('ph.notes.store') }}" method="POST" enctype="multipart/form-data" id="phForm">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="ph_handwriting_data" id="phHandwritingData">

            <div class="mb-3">
                <label class="form-label fw-bold">Catatan Teks Pengawasan Global PH (Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}) <span class="text-danger">*</span></label>
                <textarea name="ph_notes" class="form-control" rows="3" placeholder="Tuliskan evaluasi & catatan umum pengawasan seluruh kegiatan sekolah hari ini..." required>{{ old('ph_notes', $lhoReport->ph_notes) }}</textarea>
            </div>

            <!-- Canvas Tulis Tangan (Tablet / Touch Pen) -->
            <div class="mb-4">
                <label class="form-label fw-bold d-flex align-items-center justify-content-between">
                    <span><i class="material-icons-outlined align-middle text-primary me-1">draw</i> Canvas Catatan Tulis Tangan (Tablet / Touch Screen)</span>
                    <small class="text-muted">Gunakan Stylus / Touchscreen Tablet untuk menggambar atau menulis catatan</small>
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
                    <small class="text-muted d-block mt-1">* Catatan coretan tangan di atas akan otomatis disimpan dan disertakan dalam Dokumen LHO Cetak.</small>

                    @if($lhoReport->ph_handwriting_img)
                        <div class="mt-3 p-2 bg-white border rounded">
                            <small class="fw-bold d-block text-success mb-1"><i class="material-icons-outlined align-middle me-1">check_circle</i> Catatan Tulis Tangan PH yang Tersimpan:</small>
                            <img src="{{ asset($lhoReport->ph_handwriting_img) }}" class="img-fluid border rounded" style="max-height: 180px;" alt="Tulis Tangan PH">
                        </div>
                    @endif
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Upload File Lampiran Pendukung (Opsional)</label>
                    <input type="file" name="ph_file" class="form-control" accept=".pdf,.docx,.doc,.txt">
                    <small class="text-muted">Format: .pdf, .docx, .doc, .txt (Maks 5MB)</small>
                </div>
                <div class="col-md-6">
                    @if($lhoReport->ph_file)
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block">File Lampiran Ter-upload:</small>
                            <a href="{{ asset($lhoReport->ph_file) }}" target="_blank" class="fw-bold text-primary">
                                <i class="material-icons-outlined align-middle me-1">description</i> Unduh File Lampiran PH
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success btn-lg px-4" onclick="prepareSubmit()">
                    <i class="material-icons-outlined align-middle">send</i> Simpan Laporan & Catatan PH
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Rekapitulasi Data Operasional Harian Sekolah -->
<div class="card">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">visibility</i> Rekapitulasi Data Operasional Sekolah Hari Ini</h5>
        <span class="badge bg-light text-dark">Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-kbm" type="button" role="tab">
                    Jurnal KBM Guru ({{ $teachingJournals->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-siswa" type="button" role="tab">
                    Presensi Siswa ({{ $studentAbsences->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-guru" type="button" role="tab">
                    Presensi Guru ({{ $teacherAbsences->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-special" type="button" role="tab">
                    Kegiatan Spesifik ({{ $specialReports->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-events" type="button" role="tab">
                    Event Sekolah ({{ $schoolEvents->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Jurnal KBM -->
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
                                    <td colspan="5" class="text-center text-muted py-3">Tidak ada data Jurnal KBM pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Presensi Siswa -->
            <div class="tab-pane fade" id="tab-siswa" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jam Ke-</th>
                                <th>Kelas</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Pencatat / Koreksi Piket</th>
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
                                            <span class="badge bg-success">Dikoreksi Piket ({{ $abs->piket_user }})</span>
                                            <br><small class="text-muted">Ket: {{ $abs->edit_reason }}</small>
                                        @else
                                            <small class="text-muted">Guru Pengajar: {{ $abs->user }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Tidak ada siswa tidak hadir pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Presensi Guru -->
            <div class="tab-pane fade" id="tab-guru" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Guru Tidak Hadir</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Guru Pengganti</th>
                                <th>Tugas Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacherAbsences as $ta)
                                <tr>
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
                                    <td colspan="5" class="text-center text-muted py-3">Semua guru hadir atau belum ada data guru tidak hadir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kegiatan Spesifik -->
            <div class="tab-pane fade" id="tab-special" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Unit Kegiatan</th>
                                <th>Kelas / Peserta</th>
                                <th>Materi / Deskripsi</th>
                                <th>Pembimbing / Guru</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($specialReports as $sr)
                                <tr>
                                    <td><span class="badge bg-primary">{{ $sr->unit_name }}</span></td>
                                    <td><strong>{{ $sr->class_or_participants }}</strong></td>
                                    <td>{{ $sr->material_activity }}</td>
                                    <td>{{ $sr->pic_teacher }}</td>
                                    <td>{{ $sr->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada laporan kegiatan spesifik pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Event Sekolah -->
            <div class="tab-pane fade" id="tab-events" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Kategori</th>
                                <th>Judul Event / Kejadian</th>
                                <th>Deskripsi</th>
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
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada event sekolah pada tanggal ini.</td>
                                </tr>
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

    // Responsive Canvas
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

    // Touch & Mouse Drawing Handlers
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
            document.getElementById('phHandwritingData').value = dataURL;
        }
    }
</script>
@endsection
@endsection
