@extends('layouts.app')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengaturan Akun</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="material-icons-outlined">home</i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Ubah Password</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="material-icons-outlined">check_circle</i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="material-icons-outlined">error</i>
            <strong>Terjadi kesalahan pengisian form:</strong>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card rounded-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="material-icons-outlined text-primary fs-4">lock_reset</i>
                    <h5 class="mb-0 fw-bold">Formulir Perubahan Password</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <!-- Password Saat Ini -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">key</i></span>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan password saat ini" required>
                            <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="current_password">
                                <i class="material-icons-outlined fs-6 align-middle">visibility</i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Masukkan password lama Anda untuk konfirmasi keamanan.</small>
                    </div>

                    <hr class="my-4">

                    <!-- Password Baru -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">lock</i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="password">
                                <i class="material-icons-outlined fs-6 align-middle">visibility</i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Minimal 6 karakter, buat kombinasi yang aman dan mudah Anda ingat.</small>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons-outlined">lock_clock</i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang password baru" required>
                            <button class="btn btn-outline-secondary toggle-pass" type="button" data-target="password_confirmation">
                                <i class="material-icons-outlined fs-6 align-middle">visibility</i>
                            </button>
                        </div>
                        <small class="text-muted">Pastikan ketikan sama persis dengan kolom Password Baru.</small>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                            <i class="material-icons-outlined fs-6">arrow_back</i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-1 px-4">
                            <i class="material-icons-outlined fs-6">save</i> Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Informasi Akun & Panduan Keamanan -->
    <div class="col-12 col-lg-4 col-xl-5">
        <div class="card rounded-4 border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="material-icons-outlined text-secondary fs-5">account_circle</i>
                    Profil Pengguna Anda
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if (Auth::user()->gender === 'L')
                        <img src="{{ asset('metoxi/images/avatars/11.png') }}" class="rounded-circle p-1 border" width="60" height="60" alt="Avatar">
                    @else
                        <img src="{{ asset('metoxi/images/avatars/12.png') }}" class="rounded-circle p-1 border" width="60" height="60" alt="Avatar">
                    @endif
                    <div>
                        <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                        <p class="mb-0 text-muted small">Username: <code>{{ Auth::user()->username }}</code></p>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <p class="mb-1 text-muted small fw-bold">Jabatan / Role Aktif:</p>
                    <div class="d-flex flex-wrap gap-1">
                        @if(Auth::user()->positions && Auth::user()->positions->count() > 0)
                            @foreach(Auth::user()->positions as $pos)
                                <span class="badge bg-primary">{{ $pos->name }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">{{ Auth::user()->pos->name ?? 'Pengguna' }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-4 border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-2 text-dark d-flex align-items-center gap-1">
                    <i class="material-icons-outlined text-warning fs-5">tips_and_updates</i>
                    Tips Keamanan Password
                </h6>
                <ul class="text-muted small ps-3 mb-0" style="line-height: 1.6;">
                    <li>Gunakan minimal 6 karakter.</li>
                    <li>Hindari menggunakan kata sandi yang mudah ditebak seperti tanggal lahir atau <code>123456</code>.</li>
                    <li>Jangan beritahukan kata sandi Anda kepada orang lain.</li>
                    <li>Jika Anda lupa password, Administrator dapat me-reset password akun Anda ke nilai default.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('.toggle-pass');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            });
        });
    });
</script>
@endsection
