@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Guru Piket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('piket.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Koreksi Presensi Siswa</li>
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
    <div class="card-body">
        <form action="{{ route('piket.student-absences') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Filter Kelas (Opsional)</label>
                <select name="class_code" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classList as $c)
                        <option value="{{ $c->code }}" {{ $selectedClass == $c->code ? 'selected' : '' }}>{{ $c->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari Data</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h5 class="mb-0 text-white"><i class="material-icons-outlined align-middle me-2">fact_check</i> Koreksi Data Presensi Siswa (Wewenang Guru Piket)</h5>
        <span class="badge bg-light text-dark">Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Jam Ke-</th>
                        <th>Kelas</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Status Ketidakhadiran</th>
                        <th>Penginput Awal</th>
                        <th>Status Edit Piket</th>
                        <th style="width: 120px;" class="text-center">Aksi Piket</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absences as $index => $abs)
                        <tr>
                            <td>{{ $absences->firstItem() + $index }}</td>
                            <td><span class="badge bg-primary">Jam ke-{{ $abs->jam_ke }}</span></td>
                            <td><strong>{{ $abs->class_code }}</strong></td>
                            <td><code>{{ $abs->student->id_siswa ?? '-' }}</code></td>
                            <td class="fw-bold">{{ $abs->student->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $abs->status == 'Alpha' ? 'bg-danger' : ($abs->status == 'Izin' ? 'bg-warning text-dark' : 'bg-info') }} fs-6">
                                    {{ $abs->status }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $abs->user }}</small></td>
                            <td>
                                @if($abs->is_edited_by_piket)
                                    <span class="badge bg-success">Di-koreksi Piket ({{ $abs->piket_user }})</span>
                                    <br><small class="text-muted">Ket: {{ $abs->edit_reason }}</small>
                                @else
                                    <span class="badge bg-secondary">Belum Diubah</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalKoreksi{{ $abs->id }}">
                                    <i class="material-icons-outlined align-middle">edit</i> Edit Status
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Koreksi Presensi -->
                        <div class="modal fade" id="modalKoreksi{{ $abs->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('piket.student-absences.update', $abs->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Koreksi Presensi Siswa (Guru Piket)</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label text-muted mb-1">Siswa</label>
                                                <h6>{{ $abs->student->name ?? '' }} ({{ $abs->class_code }} - Jam ke-{{ $abs->jam_ke }})</h6>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ubah Status Presensi <span class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Hadir" {{ $abs->status == 'Hadir' ? 'selected' : '' }}>Hadir (Hapus Catatan Tidak Hadir)</option>
                                                    <option value="Izin" {{ $abs->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="Sakit" {{ $abs->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                                    <option value="Alpha" {{ $abs->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                                </select>
                                                <small class="text-muted d-block mt-1">Jika diubah ke "Hadir", data ketidakhadiran akan dihapus dari sistem.</small>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Koreksi oleh Guru Piket <span class="text-danger">*</span></label>
                                                <textarea name="edit_reason" class="form-control" rows="3" placeholder="Contoh: Orang tua mengonfirmasi surat sakit jam 09:00" required>{{ old('edit_reason', $abs->edit_reason) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Tidak ada catatan ketidakhadiran siswa pada tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $absences->links() }}
        </div>
    </div>
</div>
@endsection
