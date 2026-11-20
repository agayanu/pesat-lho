@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pelaporan Guru</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $isStudentdayMode ? 'Input Presensi & KBM Studentday' : 'Input Presensi & KBM Kelas' }}
                </li>
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

{{-- Tombol beralih mode jika user memiliki multi-role (Guru Studentday & Guru Kelas / Admin) --}}
@if($isSpecificGuruStudentday && $hasRegularTeacherRole)
    <div class="mb-3 d-flex justify-content-end">
        @if($isStudentdayMode)
            <a href="{{ route('teaching.index', ['mode' => 'regular', 'date' => $date]) }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="material-icons-outlined align-middle fs-6">swap_horiz</i> Beralih ke Mode Guru Kelas Reguler
            </a>
        @else
            <a href="{{ route('teaching.index', ['mode' => 'studentday', 'date' => $date]) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="material-icons-outlined align-middle fs-6">swap_horiz</i> Beralih ke Mode Guru Studentday
            </a>
        @endif
    </div>
@endif

{{-- Peringatan jika Guru Studentday belum di-assign ke Studentday tertentu oleh Admin --}}
@if($isStudentdayMode && $noStudentdayAssigned)
    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
        <i class="material-icons-outlined me-3 fs-3">warning</i>
        <div>
            <h5 class="alert-heading mb-1">Belum Ada Penugasan Studentday</h5>
            <p class="mb-0">
                Akun Anda berposisi sebagai <strong>Guru Studentday</strong>, namun belum terhubung ke bidang Studentday tertentu di database. Silakan hubungi <strong>Administrator</strong> untuk menentukan bidang Studentday Anda pada menu <strong>Data Studentday</strong>.
            </p>
        </div>
    </div>
@endif

<!-- Form Filter Kelas / Studentday & Jam Pelajaran -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white">
            <i class="material-icons-outlined align-middle me-2">tune</i>
            @if($isStudentdayMode)
                Pilih Sesi Studentday: {{ $myStudentday ? $myStudentday->name : 'Guru Studentday' }}
            @else
                Pilih Kelas & Jam Pelajaran
            @endif
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('teaching.index') }}" method="GET" class="row g-3 align-items-end">
            @if($isStudentdayMode)
                <input type="hidden" name="mode" value="studentday">
            @else
                <input type="hidden" name="mode" value="regular">
            @endif

            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>

            @if($isStudentdayMode)
                <div class="col-md-4">
                    <label class="form-label fw-bold">Bidang Studentday</label>
                    <input type="text" class="form-control fw-bold bg-light text-primary" value="{{ $myStudentday ? $myStudentday->name : 'Belum Ditentukan' }}" readonly>
                    <small class="text-muted">
                        <i class="material-icons-outlined fs-6 align-middle text-success">check_circle</i> Terdata otomatis untuk akun Anda
                    </small>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Jam Pelajaran Ke-</label>
                    <select name="jam_ke" class="form-select" required>
                        <option value="1" {{ $selectedJam == 1 ? 'selected' : '' }}>Jam Ke-1 (Pagi)</option>
                        <option value="2" {{ $selectedJam == 2 ? 'selected' : '' }}>Jam Ke-2 (Siang)</option>
                    </select>
                </div>
            @else
                <div class="col-md-4">
                    <label class="form-label fw-bold">Kelas</label>
                    <select name="class_code" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classList as $c)
                            <option value="{{ $c->code }}" {{ $selectedClass == $c->code ? 'selected' : '' }}>
                                {{ $c->code }} ({{ $c->school }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Jam Pelajaran Ke-</label>
                    <select name="jam_ke" class="form-select" required>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $selectedJam == $i ? 'selected' : '' }}>Jam ke-{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endif

            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100" {{ ($isStudentdayMode && !$myStudentday) ? 'disabled' : '' }}>Buka</button>
            </div>
        </form>
    </div>
</div>

@if($selectedClass)
    @if($isAlreadySubmitted)
        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
            <i class="material-icons-outlined me-2 fs-4">lock</i>
            <div>
                <strong>Sudah Di-Submit!</strong> Presensi dan Jurnal KBM untuk <strong>{{ $selectedClass }}</strong> pada 
                <strong>
                    @if($isStudentdayMode)
                        {{ $selectedJam == 1 ? 'Jam Ke-1 (Pagi)' : 'Jam Ke-2 (Siang)' }}
                    @else
                        Jam ke-{{ $selectedJam }}
                    @endif
                </strong> sudah diisi. Sesuai aturan, data tidak dapat diubah oleh Guru biasa. Hubungi <strong>Guru Piket</strong> jika ada perubahan data.
            </div>
        </div>
    @endif

    <form action="{{ route('teaching.store') }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="class_code" value="{{ $selectedClass }}">
        <input type="hidden" name="jam_ke" value="{{ $selectedJam }}">

        <!-- Card Presensi Siswa -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="mb-0 text-white">
                    <i class="material-icons-outlined align-middle me-2">groups</i>
                    @if($isStudentdayMode)
                        Presensi Siswa Studentday {{ $selectedClass }} ({{ $selectedJam == 1 ? 'Jam Ke-1 (Pagi)' : 'Jam Ke-2 (Siang)' }})
                    @else
                        Presensi Siswa Kelas {{ $selectedClass }} (Jam ke-{{ $selectedJam }})
                    @endif
                </h5>
                <div>
                    <span class="badge bg-primary me-1">Total: {{ $students->count() }} Siswa</span>
                    <span class="badge bg-light text-dark">Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                    <i class="material-icons-outlined me-2 fs-5">info</i>
                    <small>
                        @if($isStudentdayMode)
                            Siswa yang sudah dilaporkan <strong>Izin / Sakit / Alpha</strong> pada <strong>Jam Ke-1 (Pagi)</strong> tidak perlu di-input ulang pada Jam Ke-2 (Siang).
                        @else
                            Siswa yang sudah dilaporkan <strong>Izin / Sakit / Alpha</strong> oleh guru di jam pelajaran sebelumnya tidak perlu di-input ulang.
                        @endif
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>NIS / ID</th>
                                <th>Nama Siswa</th>
                                @if($isStudentdayMode)
                                    <th>Kelas Asal</th>
                                @endif
                                <th style="width: 80px;">L/P</th>
                                <th>Status Jam Sebelumnya</th>
                                <th style="width: 250px;" class="text-center">
                                    @if($isStudentdayMode)
                                        Status {{ $selectedJam == 1 ? 'Jam Ke-1 (Pagi)' : 'Jam Ke-2 (Siang)' }}
                                    @else
                                        Status Jam ke-{{ $selectedJam }}
                                    @endif
                                </th>
                                <th style="width: 240px;" class="text-center">Catatan Khusus Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $std)
                                @php
                                    $prevAbsence = $previousAbsences->get($std->id);
                                    $currAbsence = $currentAbsences->get($std->id);
                                    $stdNotes = $studentNotes->get($std->id, collect());
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $std->id_siswa }}</code></td>
                                    <td class="fw-bold">{{ $std->name }}</td>
                                    @if($isStudentdayMode)
                                        <td>
                                            <span class="badge bg-secondary">{{ $std->classes ?? '-' }}</span>
                                        </td>
                                    @endif
                                    <td><span class="badge {{ $std->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">{{ $std->gender }}</span></td>
                                    <td>
                                        @if($prevAbsence)
                                            <span class="badge bg-warning text-dark">
                                                @if($isStudentdayMode)
                                                    {{ $prevAbsence->jam_ke == 1 ? 'Jam Ke-1 (Pagi)' : 'Jam ke-' . $prevAbsence->jam_ke }}: {{ $prevAbsence->status }} ({{ $prevAbsence->user }})
                                                @else
                                                    Jam ke-{{ $prevAbsence->jam_ke }}: {{ $prevAbsence->status }} ({{ $prevAbsence->user }})
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-success">Hadir di jam sebelumnya</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($prevAbsence)
                                            <div class="text-center text-muted fw-bold">
                                                <i class="material-icons-outlined fs-6 align-middle">check_circle</i>
                                                Terbawa Status {{ $isStudentdayMode && $prevAbsence->jam_ke == 1 ? 'Jam Ke-1 (Pagi)' : 'Jam ' . $prevAbsence->jam_ke }}
                                            </div>
                                        @else
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="absences[{{ $std->id }}]" id="hadir_{{ $std->id }}" value="Hadir" {{ !$currAbsence ? 'checked' : '' }} {{ $isAlreadySubmitted ? 'disabled' : '' }}>
                                                <label class="btn btn-outline-success btn-sm" for="hadir_{{ $std->id }}">Hadir</label>

                                                <input type="radio" class="btn-check" name="absences[{{ $std->id }}]" id="izin_{{ $std->id }}" value="Izin" {{ $currAbsence && $currAbsence->status == 'Izin' ? 'checked' : '' }} {{ $isAlreadySubmitted ? 'disabled' : '' }}>
                                                <label class="btn btn-outline-warning btn-sm" for="izin_{{ $std->id }}">Izin</label>

                                                <input type="radio" class="btn-check" name="absences[{{ $std->id }}]" id="sakit_{{ $std->id }}" value="Sakit" {{ $currAbsence && $currAbsence->status == 'Sakit' ? 'checked' : '' }} {{ $isAlreadySubmitted ? 'disabled' : '' }}>
                                                <label class="btn btn-outline-info btn-sm" for="sakit_{{ $std->id }}">Sakit</label>

                                                <input type="radio" class="btn-check" name="absences[{{ $std->id }}]" id="alpha_{{ $std->id }}" value="Alpha" {{ $currAbsence && $currAbsence->status == 'Alpha' ? 'checked' : '' }} {{ $isAlreadySubmitted ? 'disabled' : '' }}>
                                                <label class="btn btn-outline-danger btn-sm" for="alpha_{{ $std->id }}">Alpha</label>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($stdNotes->isNotEmpty())
                                                <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewNotesModal{{ $std->id }}">
                                                    <i class="material-icons-outlined fs-6 align-middle">visibility</i> Lihat Catatan ({{ $stdNotes->count() }})
                                                </button>
                                            @endif

                                            @if(!$isAlreadySubmitted)
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#noteCollapse{{ $std->id }}">
                                                    <i class="material-icons-outlined fs-6 align-middle">edit_note</i> Beri Catatan
                                                </button>
                                                <div class="collapse mt-1" id="noteCollapse{{ $std->id }}">
                                                    <textarea name="student_notes[{{ $std->id }}]" class="form-control form-control-sm" rows="2" placeholder="Tulis catatan untuk {{ $std->name }}..."></textarea>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#quickAddNoteModal{{ $std->id }}">
                                                    <i class="material-icons-outlined fs-6 align-middle">add_comment</i> Tambah Catatan
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isStudentdayMode ? 8 : 7 }}" class="text-center text-muted py-4">
                                        @if($isStudentdayMode)
                                            Belum ada siswa yang terdaftar pada bidang Studentday {{ $selectedClass }}.
                                        @else
                                            Tidak ada data siswa terdaftar pada kelas ini.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card Jurnal Mengajar KBM -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0 text-white">
                    <i class="material-icons-outlined align-middle me-2">menu_book</i>
                    @if($isStudentdayMode)
                        Jurnal Pembelajaran Studentday (KBM)
                    @else
                        Jurnal Mengajar (KBM) Guru
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Materi Pembelajaran yang Diajarkan <span class="text-danger">*</span></label>
                    <textarea name="material" class="form-control" rows="3" placeholder="Tuliskan judul/topik materi pembelajaran..." required {{ $isAlreadySubmitted ? 'readonly' : '' }}>{{ old('material', $existingJournal->material ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Kegiatan Pembelajaran <span class="text-danger">*</span></label>
                    <textarea name="activity" class="form-control" rows="4" placeholder="Tuliskan aktivitas/kegiatan siswa selama jam pelajaran..." required {{ $isAlreadySubmitted ? 'readonly' : '' }}>{{ old('activity', $existingJournal->activity ?? '') }}</textarea>
                </div>

                @if(!$isAlreadySubmitted)
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="material-icons-outlined align-middle">send</i> Simpan Presensi & Jurnal KBM
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <!-- Modals untuk Catatan Siswa -->
    @foreach($students as $std)
        @php
            $stdNotes = $studentNotes->get($std->id, collect());
        @endphp

        <!-- Modal Lihat Catatan Siswa -->
        @if($stdNotes->isNotEmpty())
            <div class="modal fade" id="viewNotesModal{{ $std->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title text-white">
                                <i class="material-icons-outlined align-middle me-1">note_alt</i> Catatan Siswa: {{ $std->name }} (<code>{{ $std->id_siswa }}</code>)
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info py-2 mb-3">
                                <small><i class="material-icons-outlined fs-6 align-middle me-1">info</i> Catatan ini bersifat akumulatif dan dapat dilihat oleh seluruh pengajar, wali kelas, guru piket, dan manajemen sekolah.</small>
                            </div>

                            <div class="list-group">
                                @foreach($stdNotes as $n)
                                    <div class="list-group-item list-group-item-action flex-column align-items-start mb-2 border rounded shadow-sm">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                            <h6 class="mb-1 text-primary">
                                                <i class="material-icons-outlined fs-6 align-middle">person</i> {{ $n->teacher->name ?? $n->teacher_name ?? $n->created_by }}
                                                <span class="badge bg-secondary ms-2">
                                                    @if($isStudentdayMode)
                                                        {{ $n->jam_ke == 1 ? 'Jam Ke-1 (Pagi)' : ($n->jam_ke == 2 ? 'Jam Ke-2 (Siang)' : 'Jam ke-' . $n->jam_ke) }}
                                                    @else
                                                        Jam ke-{{ $n->jam_ke }}
                                                    @endif
                                                </span>
                                            </h6>
                                            <small class="text-muted">{{ $n->created_at ? $n->created_at->format('H:i') : '' }} WIB</small>
                                        </div>
                                        <div class="p-2 bg-light rounded text-dark fs-6">
                                            {{ $n->note }}
                                        </div>
                                        @if($n->is_edited_by_piket)
                                            <small class="text-warning fw-bold d-block mt-2">
                                                <i class="material-icons-outlined fs-6 align-middle">history_edu</i> Dikoreksi Guru Piket: {{ $n->piket_user }} (Alasan: {{ $n->edit_reason }})
                                            </small>
                                        @endif

                                        @if(Auth::user()->hasPosition('Guru Piket') || Auth::user()->hasPosition('Piket') || Auth::user()->position == 1)
                                            <div class="mt-2 text-end">
                                                <button type="button" class="btn btn-sm btn-warning text-dark me-1" data-bs-toggle="modal" data-bs-target="#editStudentNoteModal{{ $n->id }}">
                                                    <i class="material-icons-outlined fs-6 align-middle">edit</i> Edit (Piket)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentNoteModal{{ $n->id }}">
                                                    <i class="material-icons-outlined fs-6 align-middle">delete</i> Hapus (Piket)
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modals Edit & Delete Khusus Piket -->
            @if(Auth::user()->hasPosition('Guru Piket') || Auth::user()->hasPosition('Piket') || Auth::user()->position == 1)
                @foreach($stdNotes as $n)
                    <!-- Modal Edit Catatan Piket -->
                    <div class="modal fade text-start" id="editStudentNoteModal{{ $n->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <form action="{{ route('piket.student-notes.update', $n->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title text-dark"><i class="material-icons-outlined align-middle me-1">edit</i> Edit Catatan Siswa (Guru Piket)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Siswa</label>
                                            <input type="text" class="form-control" value="{{ $std->name }} ({{ $n->class_code }})" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Isi Catatan <span class="text-danger">*</span></label>
                                            <textarea name="note" class="form-control" rows="3" required>{{ old('note', $n->note) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Alasan Perubahan oleh Piket <span class="text-danger">*</span></label>
                                            <input type="text" name="edit_reason" class="form-control" placeholder="Contoh: Klarifikasi wali kelas / revisi..." required>
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
                    <div class="modal fade text-start" id="deleteStudentNoteModal{{ $n->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <form action="{{ route('piket.student-notes.destroy', $n->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title text-white"><i class="material-icons-outlined align-middle me-1">delete</i> Hapus Catatan Siswa</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Yakin ingin menghapus catatan untuk siswa <strong>{{ $std->name }}</strong>?</p>
                                        <div class="p-2 border rounded bg-light mb-3">
                                            <em>"{{ $n->note }}"</em>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Alasan Penghapusan (Opsional)</label>
                                            <input type="text" name="edit_reason" class="form-control" placeholder="Contoh: Catatan salah / dibatalkan">
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
                @endforeach
            @endif
        @endif

        <!-- Modal Tambah Catatan Cepat (jika form utama sudah di-submit) -->
        <div class="modal fade" id="quickAddNoteModal{{ $std->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('teaching.student-notes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="class_code" value="{{ $selectedClass }}">
                        <input type="hidden" name="jam_ke" value="{{ $selectedJam }}">
                        <input type="hidden" name="student_id" value="{{ $std->id }}">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title text-white"><i class="material-icons-outlined align-middle me-1">rate_review</i> Beri Catatan: {{ $std->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Siswa</label>
                                <input type="text" class="form-control" value="{{ $std->name }} ({{ $std->id_siswa }}) - {{ $selectedClass }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Isi Catatan <span class="text-danger">*</span></label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Tuliskan catatan tentang siswa ini..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@else
    @if(!$isStudentdayMode || $noStudentdayAssigned)
        <div class="card p-5 text-center text-muted">
            <i class="material-icons-outlined display-1 text-secondary mb-3">class</i>
            <h4>Silahkan Pilih Kelas dan Jam Pelajaran</h4>
            <p class="mb-0">Gunakan filter di atas untuk memulai menginput presensi siswa dan jurnal kegiatan mengajar.</p>
        </div>
    @endif
@endif
@endsection
