@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data User & Multi-Jabatan</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="material-icons-outlined align-middle">add</i> Tambah User Baru
        </button>
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
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Daftar Akun User & Multi-Jabatan Sekolah</h5>
        <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari nama / username..." value="{{ $search }}">
            <button type="submit" class="btn btn-outline-secondary">Cari</button>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Pengguna</th>
                        <th>Username</th>
                        <th>L/P</th>
                        <th>Jabatan / Posisi (Bisa Lebih Dari 1)</th>
                        <th>Admin Input</th>
                        <th style="width: 130px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td><strong class="text-primary">{{ $u->name }}</strong></td>
                            <td><code>{{ $u->username }}</code></td>
                            <td><span class="badge {{ $u->gender == 'L' ? 'bg-info text-dark' : 'bg-danger' }}">{{ $u->gender }}</span></td>
                            <td>
                                @if($u->positions->count() > 0)
                                    @foreach($u->positions as $p)
                                        <span class="badge bg-primary me-1 mb-1 fs-6">{{ $p->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary fs-6">{{ $u->pos->name ?? '-' }}</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $u->user }}</small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $u->id }}">
                                    <i class="material-icons-outlined">edit</i>
                                </button>
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit User -->
                        <div class="modal fade" id="editModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('users.update', $u->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title fw-bold">Edit User & Jabatan: {{ $u->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $u->name) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                                                    <input type="text" name="username" class="form-control" value="{{ old('username', $u->username) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Password Baru (Kosongkan jika tidak diubah)</label>
                                                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                                    <select name="gender" class="form-select" required>
                                                        <option value="L" {{ $u->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                        <option value="P" {{ $u->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold d-block">Pilih Jabatan / Posisi (Centang Semua yang Berlaku): <span class="text-danger">*</span></label>
                                                    <div class="p-3 border rounded bg-light row g-2">
                                                        @php
                                                            $userPosIds = $u->positions->pluck('id')->toArray();
                                                            if (empty($userPosIds) && $u->position) {
                                                                $userPosIds = [$u->position];
                                                            }
                                                        @endphp
                                                        @foreach($positions as $p)
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="positions[]" value="{{ $p->id }}" id="edit_pos_{{ $u->id }}_{{ $p->id }}" {{ in_array($p->id, $userPosIds) ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold" for="edit_pos_{{ $u->id }}_{{ $p->id }}">
                                                                        {{ $p->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold text-white">Tambah User Baru & Pilih Multi-Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Tyo, S.Pd." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: tyo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Password Login <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold d-block">Pilih Jabatan / Posisi (Dapat Mencentang Lebih dari 1): <span class="text-danger">*</span></label>
                            <div class="p-3 border rounded bg-light row g-2">
                                @foreach($positions as $p)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="positions[]" value="{{ $p->id }}" id="add_pos_{{ $p->id }}">
                                            <label class="form-check-label fw-bold" for="add_pos_{{ $p->id }}">
                                                {{ $p->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
