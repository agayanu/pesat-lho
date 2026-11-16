@extends('layouts.app')

@section('content')
<div class="card mb-3 rounded-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                @if (Auth::user()->gender === 'L')
                    <img src="{{ asset('metoxi/images/avatars/11.png') }}" class="rounded-circle p-1 border" width="65" height="65" alt="Avatar">
                @else
                    <img src="{{ asset('metoxi/images/avatars/12.png') }}" class="rounded-circle p-1 border" width="65" height="65" alt="Avatar">
                @endif
                <div>
                    <h4 class="mb-1 fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 text-muted">Jabatan / Posisi: <span class="badge bg-primary fs-6">{{ $pos }}</span></p>
                </div>
            </div>
            <div>
                <a href="{{ route('password.change') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <i class="material-icons-outlined">lock_reset</i> Ubah Password Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
