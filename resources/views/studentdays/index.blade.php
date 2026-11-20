@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Studentday</li>
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

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
            <form action="{{ route('studentdays.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari Studentday atau Guru..." value="{{ $search }}">
                <button type="submit" class="btn btn-outline-secondary">Cari</button>
            </form>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('studentday-members.history') }}" class="btn btn-outline-secondary">
                    <i class="material-icons-outlined align-middle me-1">history</i> Riwayat Siswa Dikeluarkan
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="material-icons-outlined align-middle">add</i> Tambah Studentday
                </button>
            </div>
        </div>

        @if($teachers->isEmpty())
            <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                <i class="material-icons-outlined me-2 fs-5">warning</i>
                <div>
                    <strong>Perhatian:</strong> Belum ada user yang memiliki posisi <strong>Guru Studentday</strong>. Silakan tambahkan atau tetapkan jabatan "Guru Studentday" pada akun guru di menu <a href="{{ route('users.index') }}" class="alert-link">Data User & Jabatan</a> agar dapat dipilih saat menentukan guru pembimbing studentday.
                </div>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Nama Studentday</th>
                        <th>Guru Studentday (Pembimbing)</th>
                        <th style="width: 180px;" class="text-center">Jumlah Siswa</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentdays as $index => $sd)
                        <tr>
                            <td>{{ $studentdays->firstItem() + $index }}</td>
                            <td><strong class="text-primary">{{ $sd->name }}</strong></td>
                            <td>
                                @if($sd->teacherUser)
                                    <div class="d-flex align-items-center">
                                        <i class="material-icons-outlined text-success me-2">person</i>
                                        <div>
                                            <span class="fw-bold">{{ $sd->teacherUser->name }}</span>
                                            <small class="text-muted d-block">({{ $sd->teacherUser->username }})</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-danger">Guru Belum Ditentukan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark fs-6">
                                    <i class="material-icons-outlined align-middle fs-6 me-1">groups</i>
                                    {{ $sd->students_count }} Siswa
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('studentday-members.index', ['studentday_id' => $sd->id]) }}" class="btn btn-sm btn-info text-white me-1" title="Kelola Anggota Siswa">
                                    <i class="material-icons-outlined">groups</i>
                                </a>
                                <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $sd->id }}" title="Edit Studentday">
                                    <i class="material-icons-outlined">edit</i>
                                </button>
                                <form action="{{ route('studentdays.destroy', $sd->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data studentday {{ $sd->name }}? Siswa yang terdaftar pada studentday ini akan di-reset pilihannya.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Studentday">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data Studentday yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            {{ $studentdays->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Studentday -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('studentdays.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modalCreateLabel">
                        <i class="material-icons-outlined align-middle me-1">add_circle</i> Tambah Data Studentday
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Studentday <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Futsal, Desain Grafis, English Club..." required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Guru Studentday <span class="text-danger">*</span></label>
                        <select name="teacher" class="form-select" required>
                            <option value="">-- Pilih Guru Berposisi Guru Studentday --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->username }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            <i class="material-icons-outlined fs-6 align-middle">info</i> Hanya menampilkan user yang memiliki jabatan <strong>Guru Studentday</strong>.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" {{ $teachers->isEmpty() ? 'disabled' : '' }}>Simpan Studentday</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Studentday -->
@foreach($studentdays as $sd)
    <div class="modal fade" id="modalEdit{{ $sd->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $sd->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('studentdays.update', $sd->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark" id="modalEditLabel{{ $sd->id }}">
                            <i class="material-icons-outlined align-middle me-1">edit</i> Edit Data Studentday
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Studentday <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $sd->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Guru Studentday <span class="text-danger">*</span></label>
                            <select name="teacher" class="form-select" required>
                                <option value="">-- Pilih Guru Berposisi Guru Studentday --</option>
                                @php
                                    $allTeacherOptions = $teachers->pluck('id')->toArray();
                                    $isCurrentTeacherInList = in_array($sd->teacher, $allTeacherOptions);
                                @endphp
                                @if(!$isCurrentTeacherInList && $sd->teacherUser)
                                    <option value="{{ $sd->teacherUser->id }}" selected>
                                        {{ $sd->teacherUser->name }} ({{ $sd->teacherUser->username }}) - [Guru Saat Ini]
                                    </option>
                                @endif
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher', $sd->teacher) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->username }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="material-icons-outlined fs-6 align-middle">info</i> Hanya menampilkan user yang memiliki jabatan <strong>Guru Studentday</strong>.
                            </small>
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
@endforeach
@endsection
