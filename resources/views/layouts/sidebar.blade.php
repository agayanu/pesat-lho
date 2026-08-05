<aside class="sidebar-wrapper">
    <div class="sidebar-header">
        <div class="logo-icon">
            <img src="{{ asset('logos/favicon.png') }}" class="logo-img" alt="">
        </div>
        <div class="logo-name flex-grow-1">
            <h5 class="mb-0">PESAT</h5>
        </div>
        <div class="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>
    <div class="sidebar-nav" data-simplebar="true">
        <ul class="metismenu" id="sidenav">
            <li {{ Request::is('home') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('home') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            <li class="menu-label">MASTER DATA</li>
            <li {{ Request::is('positions*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('positions.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">badge</i></div>
                    <div class="menu-title">Data Jabatan</div>
                </a>
            </li>
            <li {{ Request::is('teachers*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('teachers.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">record_voice_over</i></div>
                    <div class="menu-title">Data Guru</div>
                </a>
            </li>
            <li {{ Request::is('classes*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('classes.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">meeting_room</i></div>
                    <div class="menu-title">Data Kelas</div>
                </a>
            </li>
            <li {{ Request::is('students*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('students.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">groups</i></div>
                    <div class="menu-title">Data Siswa</div>
                </a>
            </li>
            <li {{ Request::is('users*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('users.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">manage_accounts</i></div>
                    <div class="menu-title">Data User</div>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidebar-bottom gap-4">
        <div class="dark-mode">
            <a href="javascript:;" class="footer-icon dark-mode-icon">
                <i class="material-icons-outlined">dark_mode</i>
            </a>
        </div>
        <div class="dropdown dropup-center dropup dropdown-laungauge">
            <a class="dropdown-toggle dropdown-toggle-nocaret footer-icon" href="avascript:;"
                data-bs-toggle="dropdown"><img src="{{ asset('metoxi/images/county/09.png') }}" width="22"
                    alt="">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="javascript:;">
                        <img src="{{ asset('metoxi/images/county/09.png') }}" width="20" alt="">
                        <span class="ms-2">Indonesia</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>