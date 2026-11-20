@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">
        {{ $isAdmin ? 'Master Data' : 'Pelaporan Guru' }}
    </div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                @if($isAdmin)
                    <li class="breadcrumb-item"><a href="{{ route('studentdays.index') }}">Data Studentday</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Kelola Anggota Studentday</li>
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

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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

@if(!$currentStudentday)
    <div class="card p-5 text-center text-muted">
        <i class="material-icons-outlined display-1 text-secondary mb-3">group_off</i>
        <h4>Belum Ada Penugasan Studentday</h4>
        <p class="mb-0">Akun Anda belum terdaftar sebagai pembimbing pada bidang Studentday manapun. Silakan hubungi Administrator untuk mengatur penugasan Studentday Anda.</p>
    </div>
@else
    <!-- Card Info Studentday & Pemilihan (Khusus Admin) -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4">
                        <i class="material-icons-outlined display-6">palette</i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0 fw-bold text-dark">{{ $currentStudentday->name }}</h4>
                            <span class="badge bg-primary fs-6">Studentday</span>
                        </div>
                        <p class="mb-0 text-muted mt-1">
                            Guru Pembimbing: 
                            <strong class="text-dark">{{ $currentStudentday->teacherUser->name ?? 'Belum Ditentukan' }}</strong>
                            @if($currentStudentday->teacherUser)
                                <small>({{ $currentStudentday->teacherUser->username }})</small>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if($isAdmin && $studentdayList->count() > 1)
                        <form action="{{ route('studentday-members.index') }}" method="GET" class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 fw-bold text-nowrap">Pilih Studentday:</label>
                            <select name="studentday_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($studentdayList as $sd)
                                    <option value="{{ $sd->id }}" {{ $currentStudentday->id == $sd->id ? 'selected' : '' }}>
                                        {{ $sd->name }} ({{ $sd->teacherUser->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                    <a href="{{ route('studentday-members.history') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="material-icons-outlined fs-6">manage_history</i> Riwayat Seluruh Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigasi Anggota Aktif & Riwayat Dikeluarkan -->
    <div class="card">
        <div class="card-header bg-transparent border-bottom">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tab !== 'exited' ? 'active' : '' }}" href="{{ route('studentday-members.index', ['studentday_id' => $currentStudentday->id, 'tab' => 'active']) }}">
                        <i class="material-icons-outlined align-middle me-1">groups</i> Anggota Aktif 
                        <span class="badge bg-primary ms-1">{{ $activeMembers->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'exited' ? 'active' : '' }}" href="{{ route('studentday-members.index', ['studentday_id' => $currentStudentday->id, 'tab' => 'exited']) }}">
                        <i class="material-icons-outlined align-middle me-1">history</i> Riwayat Anggota Dikeluarkan 
                        <span class="badge bg-secondary ms-1">{{ $exitedMembers->total() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            @if($tab !== 'exited')
                {{-- TAB 1: ANGGOTA AKTIF --}}
                <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
                    <form action="{{ route('studentday-members.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="studentday_id" value="{{ $currentStudentday->id }}">
                        <input type="hidden" name="tab" value="active">
                        <input type="text" name="search" class="form-control" placeholder="Cari NIS, Nama, Kelas..." value="{{ $search }}">
                        <button type="submit" class="btn btn-outline-secondary">Cari</button>
                        @if($search)
                            <a href="{{ route('studentday-members.index', ['studentday_id' => $currentStudentday->id, 'tab' => 'active']) }}" class="btn btn-outline-danger">Reset</a>
                        @endif
                    </form>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddMember">
                        <i class="material-icons-outlined align-middle me-1">person_add</i> Tambah Siswa / Anggota
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 140px;">NIS / ID Siswa</th>
                                <th>Nama Lengkap</th>
                                <th style="width: 100px;">Kelas Asal</th>
                                <th style="width: 80px;" class="text-center">L/P</th>
                                <th style="width: 120px;">Program</th>
                                <th style="width: 200px;" class="text-center">Aksi Pengelolaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeMembers as $index => $member)
                                <tr>
                                    <td>{{ $activeMembers->firstItem() + $index }}</td>
                                    <td><code>{{ $member->id_siswa }}</code></td>
                                    <td><strong class="text-dark">{{ $member->name }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $member->classes }}</span></td>
                                    <td class="text-center">
                                        <span class="badge {{ $member->gender == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $member->gender }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $member->program ?? '-' }}</small></td>
                                    <td class="text-center">
                                        {{-- Tombol Keluarkan (Pindah / Naik Kelas XII) --}}
                                        <button type="button" class="btn btn-sm btn-warning text-dark me-1" data-bs-toggle="modal" data-bs-target="#modalExitMember{{ $member->id }}" title="Keluarkan Siswa (Pindah / Naik Kelas XII)">
                                            <i class="material-icons-outlined align-middle fs-6">logout</i> Keluarkan
                                        </button>

                                        {{-- Tombol Hapus (Salah Input) --}}
                                        <form action="{{ route('studentday-members.remove', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $member->name }} dari anggota Studentday ini? Gunakan aksi ini jika salah memasukkan siswa.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Siswa (Salah Input)">
                                                <i class="material-icons-outlined align-middle fs-6">delete</i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="material-icons-outlined display-4 text-secondary mb-2">person_search</i>
                                        <p class="mb-0">Belum ada anggota terdaftar pada Studentday {{ $currentStudentday->name }}.</p>
                                        <small class="text-muted">Klik tombol "Tambah Siswa / Anggota" di atas untuk menambahkan anggota baru.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container mt-3">
                    {{ $activeMembers->links() }}
                </div>
            @else
                {{-- TAB 2: RIWAYAT ANGGOTA DIKELUARKAN --}}
                <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
                    <form action="{{ route('studentday-members.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="studentday_id" value="{{ $currentStudentday->id }}">
                        <input type="hidden" name="tab" value="exited">
                        <input type="text" name="search" class="form-control" placeholder="Cari Riwayat Siswa..." value="{{ $search }}">
                        <button type="submit" class="btn btn-outline-secondary">Cari</button>
                        @if($search)
                            <a href="{{ route('studentday-members.index', ['studentday_id' => $currentStudentday->id, 'tab' => 'exited']) }}" class="btn btn-outline-danger">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 130px;">NIS / ID Siswa</th>
                                <th>Nama Siswa</th>
                                <th style="width: 100px;">Kelas</th>
                                <th style="width: 130px;">Tanggal Keluar</th>
                                <th>Alasan Pengeluaran</th>
                                <th>Catatan / Keterangan</th>
                                <th style="width: 140px;">Dikeluarkan Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exitedMembers as $index => $history)
                                <tr>
                                    <td>{{ $exitedMembers->firstItem() + $index }}</td>
                                    <td><code>{{ $history->id_siswa }}</code></td>
                                    <td><strong class="text-dark">{{ $history->student_name }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $history->student_class }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($history->exit_date)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ str_contains($history->reason, 'Kelas XII') ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                                            {{ $history->reason }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $history->notes ?? '-' }}</small></td>
                                    <td><small class="fw-bold">{{ $history->user }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="material-icons-outlined display-4 text-secondary mb-2">history</i>
                                        <p class="mb-0">Belum ada riwayat siswa yang dikeluarkan dari Studentday {{ $currentStudentday->name }}.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container mt-3">
                    {{ $exitedMembers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Siswa / Anggota -->
    <div class="modal fade" id="modalAddMember" tabindex="-1" aria-labelledby="modalAddMemberLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('studentday-members.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="studentday_id" value="{{ $currentStudentday->id }}">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white" id="modalAddMemberLabel">
                            <i class="material-icons-outlined align-middle me-1">person_add</i> Tambah Anggota ke Studentday: {{ $currentStudentday->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info py-2 mb-3">
                            <small>
                                <i class="material-icons-outlined fs-6 align-middle me-1">info</i> 
                                Siswa yang sudah terdaftar pada Studentday lain <strong>tidak dapat dipilih</strong> dan ditandai dengan nama Studentday yang diikutinya.
                            </small>
                        </div>

                        <!-- Filter Kelas & Search di Dalam Modal -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold mb-1">Filter Kelas:</label>
                                <select id="modalFilterClass" class="form-select form-select-sm">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($classesList as $c)
                                        <option value="{{ $c->code }}">{{ $c->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small fw-bold mb-1">Cari Nama / NIS Siswa:</label>
                                <input type="text" id="modalSearchText" class="form-control form-control-sm" placeholder="Ketik nama atau NIS siswa...">
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkSelectAllAvailable">
                                <label class="form-check-label fw-bold small text-primary" for="checkSelectAllAvailable">
                                    Pilih Semua Siswa yang Tersedia
                                </label>
                            </div>
                            <span class="small text-muted" id="selectedCountBadge">0 siswa terpilih</span>
                        </div>

                        <!-- Daftar Siswa -->
                        <div class="border rounded p-2" style="max-height: 380px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle mb-0" id="tableModalStudents">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">#</th>
                                        <th>Nama Siswa & Status Studentday</th>
                                        <th style="width: 90px;">Kelas</th>
                                        <th style="width: 60px;" class="text-center">L/P</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allStudents as $std)
                                        @php
                                            $isAlreadyInThis = ($std->studentday == $currentStudentday->id);
                                            $isAlreadyInOther = (!empty($std->studentday) && $std->studentday != $currentStudentday->id);
                                            $otherSdName = $isAlreadyInOther ? ($std->studentdayRef->name ?? 'STUDENTDAY LAIN') : '';
                                        @endphp
                                        <tr class="student-row {{ $isAlreadyInOther ? 'table-light opacity-75' : '' }}" data-class="{{ $std->classes }}" data-name="{{ strtolower($std->name) }}" data-nis="{{ $std->id_siswa }}">
                                            <td class="text-center">
                                                @if($isAlreadyInThis)
                                                    <span class="badge bg-success" title="Sudah terdaftar di Studentday ini"><i class="material-icons-outlined fs-6">check</i></span>
                                                @elseif($isAlreadyInOther)
                                                    <input type="checkbox" class="form-check-input" disabled title="Sudah berada di studentday lain">
                                                @else
                                                    <input type="checkbox" name="student_ids[]" value="{{ $std->id }}" class="form-check-input check-student-available">
                                                @endif
                                            </td>
                                            <td>
                                                @if($isAlreadyInOther)
                                                    {{-- Sesuai format permintaan user: NAMA SISWA (STUDENTDAY KELAS) --}}
                                                    <span class="text-muted fw-bold">
                                                        {{ $std->name }} ({{ strtoupper($otherSdName) }} {{ $std->classes }})
                                                    </span>
                                                    <span class="badge bg-secondary ms-1">Sudah di {{ $otherSdName }}</span>
                                                @elseif($isAlreadyInThis)
                                                    <span class="text-success fw-bold">
                                                        {{ $std->name }} ({{ $std->classes }})
                                                    </span>
                                                    <span class="badge bg-success ms-1">Anggota Saat Ini</span>
                                                @else
                                                    <label class="form-check-label fw-bold text-dark cursor-pointer mb-0" for="std_check_{{ $std->id }}">
                                                        {{ $std->name }} ({{ $std->classes }})
                                                    </label>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $std->classes }}</span></td>
                                            <td class="text-center">
                                                <small class="{{ $std->gender == 'L' ? 'text-primary' : 'text-danger' }} fw-bold">{{ $std->gender }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada data siswa ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitAddMember">
                            <i class="material-icons-outlined align-middle fs-6 me-1">check</i> Tambahkan Siswa Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals Keluarkan Siswa (Untuk Setiap Anggota Aktif) -->
    @foreach($activeMembers as $member)
        <div class="modal fade" id="modalExitMember{{ $member->id }}" tabindex="-1" aria-labelledby="modalExitMemberLabel{{ $member->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('studentday-members.exit', $member->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-dark" id="modalExitMemberLabel{{ $member->id }}">
                                <i class="material-icons-outlined align-middle me-1">logout</i> Keluarkan Siswa dari Studentday
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="alert alert-warning py-2 mb-3">
                                <small>
                                    <i class="material-icons-outlined fs-6 align-middle me-1">info</i>
                                    Siswa yang dikeluarkan akan dicatat dalam riwayat keikutsertaan Studentday sehingga pembimbing dan admin dapat memantau jejak keaktifan siswa.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Siswa & Kelas</label>
                                <input type="text" class="form-control bg-light" value="{{ $member->name }} ({{ $member->classes }}) - {{ $member->id_siswa }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Studentday Asal</label>
                                <input type="text" class="form-control bg-light" value="{{ $currentStudentday->name }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Dikeluarkan <span class="text-danger">*</span></label>
                                <input type="date" name="exit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alasan Pengeluaran <span class="text-danger">*</span></label>
                                <select name="reason" class="form-select" required>
                                    <option value="">-- Pilih Alasan --</option>
                                    <option value="Pindah ke Studentday Lain">Pindah ke Studentday Lain</option>
                                    <option value="Naik ke Kelas XII (Tidak Ada Studentday)">Naik ke Kelas XII (Tidak Ada Studentday)</option>
                                    <option value="Mengundurkan Diri / Tidak Aktif">Mengundurkan Diri / Tidak Aktif</option>
                                    <option value="Penyesuaian Minat & Bakat">Penyesuaian Minat & Bakat</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan / Keterangan Tambahan</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Siswa berminat pindah ke Studentday Programming, atau siswa naik ke kelas XII..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="material-icons-outlined align-middle fs-6 me-1">logout</i> Konfirmasi Keluarkan Siswa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classFilter = document.getElementById('modalFilterClass');
            const searchInput = document.getElementById('modalSearchText');
            const rows = document.querySelectorAll('#tableModalStudents tbody tr.student-row');
            const selectAllCheck = document.getElementById('checkSelectAllAvailable');
            const selectedCountBadge = document.getElementById('selectedCountBadge');

            function filterRows() {
                const selectedClass = classFilter.value.trim().toLowerCase();
                const searchText = searchInput.value.trim().toLowerCase();

                rows.forEach(function (row) {
                    const rowClass = (row.getAttribute('data-class') || '').toLowerCase();
                    const rowName = (row.getAttribute('data-name') || '').toLowerCase();
                    const rowNis = (row.getAttribute('data-nis') || '').toLowerCase();

                    const matchClass = (!selectedClass || rowClass === selectedClass);
                    const matchSearch = (!searchText || rowName.includes(searchText) || rowNis.includes(searchText));

                    if (matchClass && matchSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                updateSelectedCount();
            }

            function updateSelectedCount() {
                const checkedBoxes = document.querySelectorAll('.check-student-available:checked');
                selectedCountBadge.textContent = checkedBoxes.length + ' siswa terpilih';
            }

            if (classFilter) {
                classFilter.addEventListener('change', filterRows);
            }
            if (searchInput) {
                searchInput.addEventListener('input', filterRows);
            }

            if (selectAllCheck) {
                selectAllCheck.addEventListener('change', function () {
                    const isChecked = this.checked;
                    rows.forEach(function (row) {
                        if (row.style.display !== 'none') {
                            const check = row.querySelector('.check-student-available');
                            if (check && !check.disabled) {
                                check.checked = isChecked;
                            }
                        }
                    });
                    updateSelectedCount();
                });
            }

            document.querySelectorAll('.check-student-available').forEach(function (cb) {
                cb.addEventListener('change', updateSelectedCount);
            });
        });
    </script>
@endif
@endsection
