@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Data Siswa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Siswa Keluar</li>
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
    <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0 text-white">
            <i class="material-icons-outlined align-middle me-1">person_remove</i> Data Siswa Keluar / Mutasi
        </h5>
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalCreateExitedStudent">
            <i class="material-icons-outlined align-middle">add</i> Catat Siswa Keluar
        </button>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
            <form action="{{ route('exited-students.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" placeholder="Cari NIS, Nama, Kelas, Alasan..." value="{{ $search }}">
                <select name="class_code" class="form-select w-auto">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classesList as $c)
                        <option value="{{ $c->code }}" {{ $selectedClass == $c->code ? 'selected' : '' }}>{{ $c->code }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                @if($search || $selectedClass)
                    <a href="{{ route('exited-students.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>NIS / ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th style="width: 70px;" class="text-center">L/P</th>
                        <th>Kelas Terakhir</th>
                        <th>Tanggal Keluar</th>
                        <th>Alasan Keluar</th>
                        <th>Catatan / Keterangan</th>
                        <th>Petugas</th>
                        <th style="width: 140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exitedStudents as $index => $es)
                        <tr>
                            <td>{{ $exitedStudents->firstItem() + $index }}</td>
                            <td><code>{{ $es->id_siswa }}</code></td>
                            <td class="fw-bold">{{ $es->name }}</td>
                            <td class="text-center">
                                <span class="badge {{ $es->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                    {{ $es->gender }}
                                </span>
                            </td>
                            <td><span class="badge bg-secondary">{{ $es->classes }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($es->exit_date)->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="badge bg-danger text-white">{{ $es->reason }}</span>
                            </td>
                            <td><small class="text-muted">{{ $es->notes ?? '-' }}</small></td>
                            <td><small class="badge bg-light text-dark">{{ $es->user }}</small></td>
                            <td class="text-center">
                                <form action="{{ route('exited-students.restore', $es->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan siswa ini ke data siswa aktif?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1" title="Kembalikan ke Siswa Aktif">
                                        <i class="material-icons-outlined">undo</i>
                                    </button>
                                </form>
                                <form action="{{ route('exited-students.destroy', $es->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen catatan siswa keluar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Catatan">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Belum ada data siswa keluar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $exitedStudents->links() }}
        </div>
    </div>
</div>

<!-- Modal Catat Siswa Keluar Satu Persatu -->
<div class="modal fade" id="modalCreateExitedStudent" tabindex="-1" aria-labelledby="modalCreateExitedStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('exited-students.store') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mencatat siswa ini keluar? Data siswa akan otomatis dipindahkan dari tabel siswa aktif ke tabel Siswa Keluar.');">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="modalCreateExitedStudentLabel">
                        <i class="material-icons-outlined align-middle me-1">person_remove</i> Catat Siswa Keluar
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 mb-3">
                        <i class="material-icons-outlined align-middle me-1">info</i>
                        Tentukan siswa yang keluar satu per satu. Data siswa terpilih akan <strong>dipindahkan dari tabel siswa aktif</strong> ke arsip <strong>Siswa Keluar</strong>.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">1. Filter Kelas Siswa <span class="text-danger">*</span></label>
                            <select id="selectClassForExit" class="form-select form-select-lg">
                                <option value="">-- Pilih Kelas Siswa --</option>
                                @foreach($classesList as $c)
                                    <option value="{{ $c->code }}">{{ $c->code }} ({{ $c->program }} - {{ $c->school }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih kelas untuk mempersempit daftar siswa.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">2. Pilih Siswa Aktif <span class="text-danger">*</span></label>
                            <select name="student_id" id="selectStudentForExit" class="form-select form-select-lg" required disabled>
                                <option value="">-- Pilih Kelas Dulu --</option>
                            </select>
                            <small class="text-muted">Pilih siswa yang akan dicatat keluar.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" name="exit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Alasan Keluar <span class="text-danger">*</span></label>
                            <input type="text" name="reason" class="form-control" list="reasonOptions" placeholder="Ketik atau pilih alasan keluar..." required>
                            <datalist id="reasonOptions">
                                <option value="Pindah Sekolah / Mutasi">
                                <option value="Mengundurkan Diri">
                                <option value="Drop Out (DO)">
                                <option value="Pindah Domisili">
                                <option value="Bekerja / Wirausaha">
                                <option value="Masalah Kesehatan">
                            </datalist>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan / Keterangan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Pindah ke SMAN 1 Cibinong, Surat Pindah No: 421/123/2026"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4" id="btnSubmitExit" disabled>
                        <i class="material-icons-outlined align-middle me-1">person_remove</i> Simpan & Pindahkan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#selectClassForExit').on('change', function() {
        var classCode = $(this).val();
        var $studentSelect = $('#selectStudentForExit');
        var $submitBtn = $('#btnSubmitExit');

        if (!classCode) {
            $studentSelect.html('<option value="">-- Pilih Kelas Dulu --</option>').prop('disabled', true);
            $submitBtn.prop('disabled', true);
            return;
        }

        $studentSelect.html('<option value="">Memuat data siswa...</option>').prop('disabled', true);
        $submitBtn.prop('disabled', true);

        $.ajax({
            url: "{{ route('graduated-students.get-students-by-class') }}",
            type: "GET",
            data: { class_code: classCode },
            success: function(response) {
                if (!response || response.length === 0) {
                    $studentSelect.html('<option value="">(Tidak ada siswa aktif di kelas ini)</option>').prop('disabled', true);
                    return;
                }

                var options = '<option value="">-- Pilih Siswa yang Keluar --</option>';
                $.each(response, function(index, s) {
                    options += '<option value="' + s.id + '">' + s.name + ' (' + s.id_siswa + ' - ' + s.gender + ')</option>';
                });

                $studentSelect.html(options).prop('disabled', false);
            },
            error: function() {
                $studentSelect.html('<option value="">Gagal memuat siswa</option>').prop('disabled', true);
            }
        });
    });

    $('#selectStudentForExit').on('change', function() {
        if ($(this).val()) {
            $('#btnSubmitExit').prop('disabled', false);
        } else {
            $('#btnSubmitExit').prop('disabled', true);
        }
    });
});
</script>
@endsection
