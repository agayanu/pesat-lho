@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pelaporan Guru</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Input Presensi & KBM</li>
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

<!-- Form Filter Kelas & Jam -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">tune</i> Pilih Kelas & Jam Pelajaran</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('teaching.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
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
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Buka</button>
            </div>
        </form>
    </div>
</div>

@if($selectedClass)
    @if($isAlreadySubmitted)
        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
            <i class="material-icons-outlined me-2 fs-4">lock</i>
            <div>
                <strong>Sudah Di-Submit!</strong> Presensi dan Jurnal KBM untuk kelas <strong>{{ $selectedClass }}</strong> pada <strong>Jam ke-{{ $selectedJam }}</strong> sudah diisi. Sesuai aturan, data tidak dapat diubah oleh Guru biasa. Hubungi <strong>Guru Piket</strong> jika ada perubahan data.
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
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">groups</i> Presensi Siswa Kelas {{ $selectedClass }} (Jam ke-{{ $selectedJam }})</h5>
                <span class="badge bg-light text-dark">Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="card-body">
                <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                    <i class="material-icons-outlined me-2 fs-5">info</i>
                    <small>Siswa yang sudah dilaporkan <strong>Izin / Sakit / Alpha</strong> oleh guru di jam pelajaran sebelumnya tidak perlu di-input ulang.</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>NIS / ID</th>
                                <th>Nama Siswa</th>
                                <th style="width: 80px;">L/P</th>
                                <th>Status Jam Sebelumnya</th>
                                <th style="width: 280px;" class="text-center">Status Jam ke-{{ $selectedJam }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $std)
                                @php
                                    $prevAbsence = $previousAbsences->get($std->id);
                                    $currAbsence = $currentAbsences->get($std->id);
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $std->id_siswa }}</code></td>
                                    <td class="fw-bold">{{ $std->name }}</td>
                                    <td><span class="badge {{ $std->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">{{ $std->gender }}</span></td>
                                    <td>
                                        @if($prevAbsence)
                                            <span class="badge bg-warning text-dark">
                                                Jam ke-{{ $prevAbsence->jam_ke }}: {{ $prevAbsence->status }} ({{ $prevAbsence->user }})
                                            </span>
                                        @else
                                            <span class="badge bg-success">Hadir di jam sebelumnya</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($prevAbsence)
                                            <div class="text-center text-muted fw-bold">
                                                <i class="material-icons-outlined fs-6 align-middle">check_circle</i> Terbawa Status Jam {{ $prevAbsence->jam_ke }}
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data siswa terdaftar pada kelas ini.</td>
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
                <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">menu_book</i> Jurnal Mengajar (KBM) Guru</h5>
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
@else
    <div class="card p-5 text-center text-muted">
        <i class="material-icons-outlined display-1 text-secondary mb-3">class</i>
        <h4>Silahkan Pilih Kelas dan Jam Pelajaran</h4>
        <p class="mb-0">Gunakan filter di atas untuk memulai menginput presensi siswa dan jurnal kegiatan mengajar.</p>
    </div>
@endif
@endsection
