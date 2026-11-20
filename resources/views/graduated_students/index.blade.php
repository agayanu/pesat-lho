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
                <li class="breadcrumb-item active" aria-current="page">Siswa Lulus</li>
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
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0 text-white">
            <i class="material-icons-outlined align-middle me-1">school</i> Data Alumni / Siswa Lulus
        </h5>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalGraduateByClasses">
                <i class="material-icons-outlined align-middle">checklist</i> Luluskan Berdasarkan Kelas
            </button>
            <button type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalGraduateByStudents">
                <i class="material-icons-outlined align-middle">person_add</i> Pilih Siswa Tertentu
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
            <form action="{{ route('graduated-students.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                <input type="text" name="search" class="form-control" placeholder="Cari NIS, Nama, Kelas..." value="{{ $search }}">
                <select name="class_code" class="form-select w-auto">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classesList as $c)
                        <option value="{{ $c->code }}" {{ $selectedClass == $c->code ? 'selected' : '' }}>{{ $c->code }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
                @if($search || $selectedClass)
                    <a href="{{ route('graduated-students.index') }}" class="btn btn-outline-danger">Reset</a>
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
                        <th>Program</th>
                        <th>Tanggal Lulus</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                        <th style="width: 140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($graduatedStudents as $index => $gs)
                        <tr>
                            <td>{{ $graduatedStudents->firstItem() + $index }}</td>
                            <td><code>{{ $gs->id_siswa }}</code></td>
                            <td class="fw-bold">{{ $gs->name }}</td>
                            <td class="text-center">
                                <span class="badge {{ $gs->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                    {{ $gs->gender }}
                                </span>
                            </td>
                            <td><span class="badge bg-secondary">{{ $gs->classes }}</span></td>
                            <td>{{ $gs->program ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($gs->graduation_date)->translatedFormat('d M Y') }}</td>
                            <td><small class="text-muted">{{ $gs->notes ?? '-' }}</small></td>
                            <td><small class="badge bg-light text-dark">{{ $gs->user }}</small></td>
                            <td class="text-center">
                                <form action="{{ route('graduated-students.restore', $gs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan siswa ini ke data siswa aktif?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1" title="Kembalikan ke Siswa Aktif">
                                        <i class="material-icons-outlined">undo</i>
                                    </button>
                                </form>
                                <form action="{{ route('graduated-students.destroy', $gs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen arsip kelulusan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Arsip">
                                        <i class="material-icons-outlined">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Belum ada data siswa lulus/alumni.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $graduatedStudents->links() }}
        </div>
    </div>
</div>

<!-- Modal 1: Kelulusan Berdasarkan Kelas (Checklist Banyak Kelas) -->
<div class="modal fade" id="modalGraduateByClasses" tabindex="-1" aria-labelledby="modalGraduateByClassesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('graduated-students.store-by-classes') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses kelulusan untuk seluruh siswa aktif pada kelas-kelas yang dipilih? Data siswa akan dipindahkan dari tabel siswa aktif ke tabel Siswa Lulus.');">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modalGraduateByClassesLabel">
                        <i class="material-icons-outlined align-middle me-1">checklist</i> Luluskan Siswa Berdasarkan Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 mb-3">
                        <i class="material-icons-outlined align-middle me-1">info</i>
                        Ceklis satu atau beberapa kelas yang akan diluluskan. Seluruh siswa aktif pada kelas terpilih akan otomatis dipindahkan ke menu <strong>Siswa Lulus</strong>.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Kelulusan <span class="text-danger">*</span></label>
                            <input type="date" name="graduation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Catatan / Keterangan (Opsional)</label>
                            <input type="text" name="notes" class="form-control" placeholder="Contoh: Kelulusan Angkatan Tahun Ajaran 2025/2026">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label fw-bold mb-0">Pilih Kelas yang Diluluskan: <span class="text-danger">*</span></label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnSelectAllXII">Pilih Semua Kelas XII</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="btnUncheckAllClass">Batal Pilih</button>
                        </div>
                    </div>

                    <div class="border rounded p-3" style="max-height: 280px; overflow-y: auto;">
                        <h6 class="fw-bold text-primary border-bottom pb-1 mb-2">Kelas Tingkat XII</h6>
                        <div class="row row-cols-2 row-cols-md-4 g-2 mb-3">
                            @foreach($xiiClasses as $c)
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input class-checkbox class-xii" type="checkbox" name="classes[]" value="{{ $c->code }}" id="cls_{{ $c->id }}">
                                        <label class="form-check-label fw-semibold" for="cls_{{ $c->id }}">
                                            {{ $c->code }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <h6 class="fw-bold text-secondary border-bottom pb-1 mb-2">Kelas Lainnya</h6>
                        <div class="row row-cols-2 row-cols-md-4 g-2">
                            @foreach($classesList->whereNotIn('id', $xiiClasses->pluck('id')) as $c)
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input class-checkbox" type="checkbox" name="classes[]" value="{{ $c->code }}" id="cls_{{ $c->id }}">
                                        <label class="form-check-label text-muted" for="cls_{{ $c->id }}">
                                            {{ $c->code }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="material-icons-outlined align-middle me-1">school</i> Proses Kelulusan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Kelulusan Siswa Tertentu (Memilih Siswa Karena Ada Yang Tidak Lulus) -->
<div class="modal fade" id="modalGraduateByStudents" tabindex="-1" aria-labelledby="modalGraduateByStudentsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('graduated-students.store-by-students') }}" method="POST" onsubmit="return confirm('Proses kelulusan untuk siswa yang telah dicentang? Siswa yang diluluskan akan dipindahkan dari tabel siswa aktif ke tabel Siswa Lulus.');">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalGraduateByStudentsLabel">
                        <i class="material-icons-outlined align-middle me-1">how_to_reg</i> Pilih Siswa Tertentu yang Diluluskan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 mb-3">
                        <i class="material-icons-outlined align-middle me-1">info</i>
                        Gunakan fitur ini apabila dalam satu kelas <strong>terdapat siswa yang tidak lulus</strong>. Pilih kelas terlebih dahulu, kemudian centang siswa-siswa yang dinyatakan lulus.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pilih Kelas <span class="text-danger">*</span></label>
                            <select id="selectClassForStudents" class="form-select form-select-lg">
                                <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                                @foreach($classesList as $c)
                                    <option value="{{ $c->code }}">{{ $c->code }} ({{ $c->program }} - {{ $c->school }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Kelulusan <span class="text-danger">*</span></label>
                            <input type="date" name="graduation_date" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan / Keterangan (Opsional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Contoh: Lulus Bersyarat / Kelulusan Terpilih">
                    </div>

                    <div id="studentSelectionContainer" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold mb-0">Daftar Siswa Aktif di Kelas Ini:</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnCheckAllStudents">Centang Semua Siswa</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="btnUncheckAllStudents">Hapus Centang</button>
                            </div>
                        </div>

                        <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;" id="studentsListCheckboxes">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <div id="studentLoadingMsg" class="text-center py-3 text-muted" style="display: none;">
                        <span class="spinner-border spinner-border-sm me-1"></span> Mengambil data siswa...
                    </div>
                    <div id="studentEmptyMsg" class="text-center py-3 text-muted">
                        Pilih kelas di atas untuk memuat daftar siswa.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4" id="btnSubmitStudents" disabled>
                        <i class="material-icons-outlined align-middle me-1">school</i> Proses Kelulusan Siswa Terpilih
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
    // Select all XII classes
    $('#btnSelectAllXII').on('click', function() {
        $('.class-xii').prop('checked', true);
    });

    // Uncheck all classes
    $('#btnUncheckAllClass').on('click', function() {
        $('.class-checkbox').prop('checked', false);
    });

    // Dynamic fetch students by class
    $('#selectClassForStudents').on('change', function() {
        var classCode = $(this).val();
        if (!classCode) {
            $('#studentSelectionContainer').hide();
            $('#studentEmptyMsg').show().text('Pilih kelas di atas untuk memuat daftar siswa.');
            $('#btnSubmitStudents').prop('disabled', true);
            return;
        }

        $('#studentEmptyMsg').hide();
        $('#studentLoadingMsg').show();
        $('#studentSelectionContainer').hide();
        $('#btnSubmitStudents').prop('disabled', true);

        $.ajax({
            url: "{{ route('graduated-students.get-students-by-class') }}",
            type: "GET",
            data: { class_code: classCode },
            success: function(response) {
                $('#studentLoadingMsg').hide();
                if (!response || response.length === 0) {
                    $('#studentEmptyMsg').show().text('Tidak ada siswa aktif di kelas ' + classCode + '.');
                    return;
                }

                var html = '';
                $.each(response, function(index, student) {
                    html += '<div class="form-check py-1 border-bottom border-light">';
                    html += '  <input class="form-check-input student-chk" type="checkbox" name="student_ids[]" value="' + student.id + '" id="std_chk_' + student.id + '" checked>';
                    html += '  <label class="form-check-label w-100" for="std_chk_' + student.id + '">';
                    html += '    <span class="fw-bold">' + student.name + '</span> ';
                    html += '    <span class="badge bg-secondary ms-1">' + student.id_siswa + '</span> ';
                    html += '    <span class="badge ' + (student.gender === 'L' ? 'bg-primary' : 'bg-danger') + ' ms-1">' + student.gender + '</span>';
                    html += '  </label>';
                    html += '</div>';
                });

                $('#studentsListCheckboxes').html(html);
                $('#studentSelectionContainer').show();
                $('#btnSubmitStudents').prop('disabled', false);
            },
            error: function() {
                $('#studentLoadingMsg').hide();
                $('#studentEmptyMsg').show().text('Gagal mengambil data siswa. Silakan coba lagi.');
            }
        });
    });

    // Check all / uncheck all students
    $('#btnCheckAllStudents').on('click', function() {
        $('.student-chk').prop('checked', true);
    });
    $('#btnUncheckAllStudents').on('click', function() {
        $('.student-chk').prop('checked', false);
    });
});
</script>
@endsection
