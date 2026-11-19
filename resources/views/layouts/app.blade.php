<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('logos/favicon.png') }}" type="image/x-icon">
    <title>Pesat Laporan Harian Operasional</title>
    <link href="{{ asset('metoxi/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('metoxi/plugins/metismenu/metisMenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('metoxi/plugins/metismenu/mm-vertical.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('metoxi/plugins/simplebar/css/simplebar.css') }}">
    <link href="{{ asset('metoxi/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="{{ asset('metoxi/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('metoxi/sass/main.css') }}" rel="stylesheet">
    <link href="{{ asset('metoxi/sass/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('metoxi/sass/semi-dark.css') }}" rel="stylesheet">
    <link href="{{ asset('metoxi/sass/bordered-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('metoxi/sass/responsive.css') }}" rel="stylesheet">
    <style>
        .search-bar {
            display: none;
        }
        .page-footer {
            justify-content: space-between;
        }
        @media (max-width: 600px){
            .page-footer {
                flex-direction: column;
                height: 43px;
            }
            .toggled .sidebar-wrapper .sidebar-header {
                width: 259px;
            }
            .toggled .sidebar-wrapper .sidebar-bottom {
                width: 259px;
            }
        }
        /* Custom Responsive Table Navigation & Pagination CSS */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 1rem;
            padding-top: 0.75rem;
        }

        .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            gap: 4px;
        }

        .pagination .page-item .page-link {
            border-radius: 6px !important;
            color: #495057;
            font-weight: 500;
            padding: 6px 12px;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease-in-out;
            font-size: 0.875rem;
            box-shadow: none !important;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.3) !important;
        }

        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .pagination .page-item:not(.disabled):not(.active) .page-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
            border-color: #ced4da;
        }

        /* Fix any legacy SVG icon scaling in pagination */
        nav[role="navigation"] svg {
            max-width: 1.25rem !important;
            max-height: 1.25rem !important;
            height: 1.25rem !important;
            width: 1.25rem !important;
            vertical-align: middle;
        }

        @media (max-width: 576px) {
            .pagination-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .pagination {
                justify-content: center;
                width: 100%;
            }
        /* Mobile Modal & Touch Fixes */
        .modal {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        [data-bs-toggle="modal"] {
            cursor: pointer;
            touch-action: manipulation;
        }
    </style>
    @yield('css')
</head>
<body>
@include('layouts.topbar')
@include('layouts.sidebar')
<main class="main-wrapper">
    <div class="main-content">
        @yield('content')
    </div>
</main>
<div class="overlay btn-toggle"></div>
<footer class="page-footer px-3">
    <p class="mb-0">Pesat Laporan Harian Operasional © 2026 Departemen TIK</p>
    <p class="mb-0">Powered by <a href="https://smapluspgri.sch.id/">PESAT</a></p>
</footer>
@include('layouts.right-sidebar')
<script src="{{ asset('metoxi/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('metoxi/js/jquery.min.js') }}"></script>
<script src="{{ asset('metoxi/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('metoxi/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('metoxi/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('metoxi/js/main.js') }}"></script>
<!-- Modal Filter Cetak LHO Terpadu -->
<div class="modal fade" id="modalFilterCetakLHO" tabindex="-1" aria-labelledby="modalFilterCetakLHOLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('lho.print') }}" method="GET" target="_blank" onsubmit="$('#modalFilterCetakLHO').modal('hide');">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modalFilterCetakLHOLabel">
                        <i class="material-icons-outlined align-middle me-1">print</i> Cetak LHO Terpadu
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Tanggal Laporan <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                        <small class="text-muted">Pilih tanggal laporan yang akan dicetak.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Kategori Sekolah <span class="text-danger">*</span></label>
                        <select name="school" class="form-select form-select-lg">
                            <option value="">--Semua Sekolah-- (menampilkan data laporan SMAS dan Reguler)</option>
                            <option value="SMAS">SMAS (menampilkan data laporan SMAS)</option>
                            <option value="Reguler">Reguler (menampilkan data laporan Reguler)</option>
                        </select>
                        <small class="text-muted">Pilih cakupan data sekolah yang ingin ditampilkan.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="material-icons-outlined align-middle me-1">open_in_new</i> Buka Dokumen Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@yield('scripts')
</body>
</html>