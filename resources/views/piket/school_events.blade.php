@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Guru Piket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('piket.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Event & Kejadian Sekolah</li>
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

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">event</i> Input Event, Acara, atau Kejadian Sekolah</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('piket.school-events.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Kategori Kejadian / Event <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="Kunjungan Tamu Sekolah">Kunjungan Tamu Sekolah</option>
                        <option value="Kunjungan Orang Tua Siswa">Kunjungan Orang Tua Siswa</option>
                        <option value="Acara / Event Sekolah">Acara / Event Sekolah</option>
                        <option value="Kejadian Khusus / Kedisiplinan">Kejadian Khusus / Kedisiplinan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Judul Acara / Kejadian <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Kunjungan Studi Banding SMA Negeri 1" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-bold">Deskripsi Detail Kejadian <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tuliskan rincian agenda, orang yang berkunjung, atau uraian kejadian..." required></textarea>
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="material-icons-outlined align-middle">save</i> Simpan Catatan Event
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">list</i> Daftar Acara & Kejadian Sekolah ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Kategori</th>
                        <th>Judul Event / Kejadian</th>
                        <th>Deskripsi Detail</th>
                        <th>Petugas Piket</th>
                        <th style="width: 80px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $ev)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-dark fs-6">{{ $ev->category }}</span></td>
                            <td class="fw-bold">{{ $ev->title }}</td>
                            <td>{{ $ev->description }}</td>
                            <td><small class="text-muted">{{ $ev->piket_user }}</small></td>
                            <td class="text-center">
                                <form action="{{ route('piket.school-events.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data event ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Belum ada pencatatan event atau kejadian sekolah untuk tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
