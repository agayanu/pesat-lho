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
            </form>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="material-icons-outlined">add</i> Tambah Siswa
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>NIS / ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th style="width: 100px;">L/P</th>
                        <th>Kelas</th>
                        <th>Program</th>
                        <th>Student Day</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $std)
                        <tr>
                            <td>{{ $students->firstItem() + $index }}</td>
                            <td><code>{{ $std->id_siswa }}</code></td>
                            <td>{{ $std->name }}</td>
                            <td>
                                <span class="badge {{ $std->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                    {{ $std->gender }}
                                </span>
                            </td>
                            <td>{{ $std->classes }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $std->program }}</span>
                            </td>
                            <td>{{ $std->studentday ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $std->id }}">
                                    <i class="material-icons-outlined">edit</i>
                                </button>
                                <form action="{{ route('students.destroy', $std->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEdit{{ $std->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('students.update', $std->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Siswa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">ID / NIS Siswa</label>
                                                <input type="text" name="id_siswa" class="form-control" value="{{ old('id_siswa', $std->id_siswa) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Siswa</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $std->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select name="gender" class="form-select" required>
                                                    <option value="L" {{ $std->gender == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                                    <option value="P" {{ $std->gender == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kelas</label>
                                                <select name="classes" class="form-select" required>
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach($classesList as $c)
                                                        <option value="{{ $c->code }}" {{ $std->classes == $c->code ? 'selected' : '' }}>
                                                            {{ $c->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Program</label>
                                                <select name="program" class="form-select" required>
                                                    <option value="Pioneer" {{ $std->program == 'Pioneer' ? 'selected' : '' }}>Pioneer</option>
                                                    <option value="Unggulan" {{ $std->program == 'Unggulan' ? 'selected' : '' }}>Unggulan</option>
                                                    <option value="Reguler" {{ $std->program == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Student Day (Opsional)</label>
                                                <input type="text" name="studentday" class="form-control" value="{{ old('studentday', $std->studentday) }}">
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data siswa.</td>
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

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID / NIS Siswa</label>
                        <input type="text" name="id_siswa" class="form-control" placeholder="Contoh: 20261001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap Siswa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="classes" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classesList as $c)
                                <option value="{{ $c->code }}">{{ $c->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <select name="program" class="form-select" required>
                            <option value="Pioneer">Pioneer</option>
                            <option value="Unggulan">Unggulan</option>
                            <option value="Reguler">Reguler</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student Day (Opsional)</label>
                        <input type="text" name="studentday" class="form-control" placeholder="Contoh: Desain Grafis / Futsal">
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
