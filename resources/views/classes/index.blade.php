@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Kelas</li>
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
            <form action="{{ route('classes.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari Kode Kelas..." value="{{ $search }}">
                <button type="submit" class="btn btn-outline-secondary">Cari</button>
            </form>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="material-icons-outlined">add</i> Tambah Kelas
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Kode / Nama Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Kategori Sekolah</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $index => $cls)
                        <tr>
                            <td>{{ $classes->firstItem() + $index }}</td>
                            <td><strong class="text-primary">{{ $cls->code }}</strong></td>
                            <td>{{ $cls->homeroomTeacher->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $cls->school == 'Unggulan' ? 'bg-success' : 'bg-info' }}">
                                    {{ $cls->school }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $cls->id }}">
                                    <i class="material-icons-outlined">edit</i>
                                </button>
                                <form action="{{ route('classes.destroy', $cls->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $classes->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit Section (Out of table) -->
@foreach($classes as $cls)
    <div class="modal fade" id="modalEdit{{ $cls->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $cls->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('classes.update', $cls->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel{{ $cls->id }}">Edit Data Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode / Nama Kelas</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $cls->code) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select name="homeroom" class="form-select" required>
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" {{ $cls->homeroom == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori Sekolah</label>
                            <select name="school" class="form-select" required>
                                <option value="Unggulan" {{ $cls->school == 'Unggulan' ? 'selected' : '' }}>Unggulan</option>
                                <option value="Reguler" {{ $cls->school == 'Reguler' ? 'selected' : '' }}>Reguler</option>
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
            <form action="{{ route('classes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah Data Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode / Nama Kelas</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: X IPA 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wali Kelas</label>
                        <select name="homeroom" class="form-select" required>
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Sekolah</label>
                        <select name="school" class="form-select" required>
                            <option value="Unggulan">Unggulan</option>
                            <option value="Reguler">Reguler</option>
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
