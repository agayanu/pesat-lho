<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('logos/favicon.png') }}" type="image/x-icon">
    <title>Pesat Laporan Harian Operasional</title>
    <link href="{{ asset('metoxi/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="{{ asset('metoxi/sass/main.css') }}" rel="stylesheet">
    <style>
        .bg-login {
            background-image: url({{ asset('images/bg2.jpg') }});
        }
        #loadBtn {
            display: none;
        }
    </style>
</head>
<body class="bg-login">
<div class="container-fluid my-5">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
            @if($message = Session::get('error'))
            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white">
                        <span class="material-icons-outlined fs-2">report_gmailerrorred</span>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-white">Gagal !!!</h6>
                        <div class="text-white">{!! $message !!}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="card rounded-4">
                <div class="card-body p-5">
                    <img src="{{ asset('logos/favicon.png') }}" class="mb-4" width="115" alt="">
                    <h4 class="fw-bold">Pesat Laporan Harian Operasional</h4>
                    <p class="mb-0">Sekolahnya Para Kader Bangsa</p>
                    <div class="form-body my-4">
                        <form class="row g-3" action="" method="POST">
                        @csrf
                            <div class="col-12">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Masukkan username anda">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" name="password" placeholder="Masukkan password anda"> 
                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Ingatkan Saya</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" id="loginBtn">Login</button>
                                    <button class="btn btn-primary" type="button" id="loadBtn" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('metoxi/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('metoxi/js/jquery.min.js') }}"></script>
<script src="{{ asset('metoxi/js/main.js') }}"></script>
<script>
$(document).ready(function () {
    $("#show_hide_password a").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass("bi-eye-slash-fill");
            $('#show_hide_password i').removeClass("bi-eye-fill");
        } else if ($('#show_hide_password input').attr("type") == "password") {
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass("bi-eye-slash-fill");
            $('#show_hide_password i').addClass("bi-eye-fill");
        }
    });
});
$('#loginBtn').on('click', function(){
    $('#loginBtn').hide();
    $('#loadBtn').show();
});
</script>
</body>
</html>