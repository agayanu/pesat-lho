@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Siswa</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="material-icons-outlined align-middle me-1">check_circle</i>
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

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
            <form action="{{ route('students.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari NIS, Nama, Kelas..." value="{{ $search }}">
                <button type="submit" class="btn btn-outline-secondary">Cari</button>
                @if($search)
                    <a href="{{ route('students.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('students.template') }}" class="btn btn-outline-success">
                    <i class="material-icons-outlined align-middle">download</i> Download Template Excel
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUploadStudents">
                    <i class="material-icons-outlined align-middle">upload_file</i> Upload Data Siswa
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="material-icons-outlined align-middle">add</i> Tambah Siswa
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>NIS / ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th style="width: 70px;" class="text-center">L/P</th>
                        <th>Kelas</th>
                        <th>Program</th>
                        <th>Student Day</th>
                        <th style="width: 140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $std)
                        <tr>
                            <td>{{ $students->firstItem() + $index }}</td>
                            <td><code>{{ $std->id_siswa }}</code></td>
                            <td class="fw-bold">{{ $std->name }}</td>
                            <td class="text-center">
                                <span class="badge {{ $std->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                    {{ $std->gender }}
                                </span>
                            </td>
                            <td><span class="badge bg-secondary">{{ $std->classes }}</span></td>
                            <td>{{ $std->program }}</td>
                            <td>
                                @if($std->studentdayRef)
                                    <span class="badge bg-info text-dark">{{ $std->studentdayRef->name }}</span>
                                @elseif($std->studentday)
                                    <span class="badge bg-light text-dark">{{ $std->studentday }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning me-1" title="Edit Data" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $std->id }}">
                                    <i class="material-icons-outlined">edit</i>
                                </button>
                                <form action="{{ route('students.destroy', $std->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data siswa aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- Modal Upload Data Siswa Excel -->
<div class="modal fade" id="modalUploadStudents" tabindex="-1" aria-labelledby="modalUploadStudentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-white" id="modalUploadStudentsLabel">
                        <i class="material-icons-outlined align-middle me-1">upload_file</i> Upload Data Siswa (Excel)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading fw-bold mb-2"><i class="material-icons-outlined align-middle me-1">info</i> Ketentuan Template Upload:</h6>
                        <ul class="mb-2 ps-3">
                            <li>Template Excel hanya berisi kolom: <strong>id_siswa, nama, jenis_kelamin, kelas, studentday</strong>.</li>
                            <li><strong>kelas</strong>: Harus sesuai dengan database tabel <em>classes</em> (field <code>code</code>).</li>
                            <li><strong>studentday</strong>: Harus sesuai dengan database tabel <em>studentdays</em> (field <code>name</code>). Jika siswa tidak memiliki student day, kosongkan kolom ini.</li>
                            <li><strong class="text-danger">PENTING (Auto-Replace):</strong> Jika ditemukan <code>id_siswa</code> yang sudah ada di database, data di database akan <strong>ter-replace</strong> otomatis sesuai data terbaru yang diupload (solusi untuk koreksi masal nama, jenis kelamin, kelas, maupun studentday).</li>
                        </ul>
                        <div class="mt-2">
                            <a href="{{ route('students.template') }}" class="btn btn-sm btn-dark">
                                <i class="material-icons-outlined align-middle">download</i> Download Template Excel (.xlsx)
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel / CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control form-control-lg" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Mendukung format .xlsx, .xls, dan .csv (Maksimal 10MB)</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="material-icons-outlined align-middle me-1">cloud_upload</i> Mulai Upload & Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Section (Out of table) -->
@foreach($students as $std)
    <div class="modal fade" id="modalEdit{{ $std->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $std->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('students.update', $std->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel{{ $std->id }}">Edit Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID / NIS Siswa</label>
                            <input type="text" name="id_siswa" class="form-control" value="{{ old('id_siswa', $std->id_siswa) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Siswa</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $std->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="gender" class="form-select" required>
                                <option value="L" {{ $std->gender == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ $std->gender == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelas</label>
                            <select name="classes" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classesList as $c)
                                    <option value="{{ $c->code }}" {{ $std->classes == $c->code ? 'selected' : '' }}>
                                        {{ $c->code }} ({{ $c->program }} - {{ $c->school }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Program</label>
                            <select name="program" class="form-select">
                                <option value="INTERNASIONAL" {{ strtoupper($std->program) == 'INTERNASIONAL' ? 'selected' : '' }}>Internasional</option>
                                <option value="UNGGULAN" {{ strtoupper($std->program) == 'UNGGULAN' ? 'selected' : '' }}>Unggulan</option>
                                <option value="REGULER" {{ strtoupper($std->program) == 'REGULER' ? 'selected' : '' }}>Reguler</option>
                            </select>
                            <small class="text-muted">Opsional (otomatis menyesuaikan kelas jika diubah).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Student Day (Opsional)</label>
                            <select name="studentday" class="form-select">
                                <option value="">-- Tidak Memilih / Tanpa Student Day --</option>
                                @foreach($studentdaysList as $sd)
                                    <option value="{{ $sd->id }}" {{ $std->studentday == $sd->id ? 'selected' : '' }}>
                                        {{ $sd->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID / NIS Siswa</label>
                        <input type="text" name="id_siswa" class="form-control" placeholder="Contoh: 20261001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Siswa</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap Siswa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="gender" class="form-select" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kelas</label>
                        <select name="classes" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classesList as $c)
                                <option value="{{ $c->code }}">{{ $c->code }} ({{ $c->program }} - {{ $c->school }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Program</label>
                        <select name="program" class="form-select">
                            <option value="INTERNASIONAL">Internasional</option>
                            <option value="UNGGULAN">Unggulan</option>
                            <option value="REGULER" selected>Reguler</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student Day (Opsional)</label>
                        <select name="studentday" class="form-select">
                            <option value="">-- Tidak Memilih / Tanpa Student Day --</option>
                            @foreach($studentdaysList as $sd)
                                <option value="{{ $sd->id }}">{{ $sd->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
