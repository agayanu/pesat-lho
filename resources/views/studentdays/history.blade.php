@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Riwayat Studentday</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('studentdays.index') }}">Data Studentday</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat Siswa Dikeluarkan</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
            <div>
                <h5 class="card-title mb-1 fw-bold"><i class="material-icons-outlined align-middle me-1">history_edu</i> Riwayat Seluruh Siswa Keluar dari Studentday</h5>
                <p class="text-muted small mb-0">Memantau riwayat siswa yang pernah berpindah atau dikeluarkan dari bidang Studentday (misal: naik ke kelas XII atau penyesuaian minat).</p>
            </div>
            <div>
                <a href="{{ route('studentday-members.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="material-icons-outlined align-middle fs-6 me-1">group</i> Kelola Anggota Studentday
                </a>
            </div>
        </div>

        <form action="{{ route('studentday-members.history') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Cari Nama / NIS Siswa:</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Ketik nama atau NIS..." value="{{ $search }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Filter Studentday:</label>
                <select name="studentday_id" class="form-select form-select-sm">
                    <option value="">-- Semua Studentday --</option>
                    @foreach($studentdayList as $sd)
                        <option value="{{ $sd->id }}" {{ $studentdayFilter == $sd->id ? 'selected' : '' }}>
                            {{ $sd->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Filter Alasan:</label>
                <select name="reason" class="form-select form-select-sm">
                    <option value="">-- Semua Alasan --</option>
                    <option value="Pindah ke Studentday Lain" {{ $reasonFilter == 'Pindah ke Studentday Lain' ? 'selected' : '' }}>Pindah ke Studentday Lain</option>
                    <option value="Naik ke Kelas XII (Tidak Ada Studentday)" {{ $reasonFilter == 'Naik ke Kelas XII (Tidak Ada Studentday)' ? 'selected' : '' }}>Naik ke Kelas XII (Tidak Ada Studentday)</option>
                    <option value="Mengundurkan Diri / Tidak Aktif" {{ $reasonFilter == 'Mengundurkan Diri / Tidak Aktif' ? 'selected' : '' }}>Mengundurkan Diri / Tidak Aktif</option>
                    <option value="Penyesuaian Minat & Bakat" {{ $reasonFilter == 'Penyesuaian Minat & Bakat' ? 'selected' : '' }}>Penyesuaian Minat & Bakat</option>
                    <option value="Lainnya" {{ $reasonFilter == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                @if($search || $studentdayFilter || $reasonFilter)
                    <a href="{{ route('studentday-members.history') }}" class="btn btn-outline-danger btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 130px;">NIS / ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th style="width: 90px;">Kelas</th>
                        <th>Studentday Asal</th>
                        <th style="width: 130px;">Tanggal Keluar</th>
                        <th>Alasan Pengeluaran</th>
                        <th>Catatan / Keterangan</th>
                        <th style="width: 140px;">Dikeluarkan Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $index => $item)
                        <tr>
                            <td>{{ $histories->firstItem() + $index }}</td>
                            <td><code>{{ $item->id_siswa }}</code></td>
                            <td><strong class="text-primary">{{ $item->student_name }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $item->student_class }}</span></td>
                            <td>
                                <strong class="text-dark">{{ $item->studentday_name }}</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->exit_date)->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="badge {{ str_contains($item->reason, 'Kelas XII') ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                                    {{ $item->reason }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $item->notes ?? '-' }}</small></td>
                            <td><small class="fw-bold">{{ $item->user }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="material-icons-outlined display-4 text-secondary mb-2">history</i>
                                <p class="mb-0">Tidak ada data riwayat siswa dikeluarkan yang sesuai dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            {{ $histories->links() }}
        </div>
    </div>
</div>
@endsection
