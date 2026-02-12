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
@yield('scripts')
</body>
</html>