@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pelaporan Guru</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat KBM Saya</li>
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

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('teaching.history') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">history_edu</i> Riwayat Mengajar ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h5>
        <a href="{{ route('teaching.index') }}" class="btn btn-sm btn-light"><i class="material-icons-outlined align-middle me-1">add</i> Input KBM Baru</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 100px;">Jam Ke-</th>
                        <th style="width: 120px;">Kelas</th>
                        <th>Materi Pembelajaran</th>
                        <th>Deskripsi Kegiatan</th>
                        <th style="width: 150px;">Waktu Input</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myJournals as $index => $j)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-primary fs-6">Jam ke-{{ $j->jam_ke }}</span></td>
                            <td><strong class="text-dark">{{ $j->class_code }}</strong></td>
                            <td>{{ $j->material }}</td>
                            <td>{{ $j->activity }}</td>
                            <td><small class="text-muted">{{ $j->created_at->format('H:i:s') }} WIB</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat mengajar yang di-submit untuk tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
