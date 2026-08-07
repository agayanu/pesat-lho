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
            <li class="menu-label">PELAPORAN GURU</li>
            <li {{ Request::is('teaching') && !Request::is('teaching/history') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('teaching.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">edit_note</i></div>
                    <div class="menu-title">Input Presensi & KBM</div>
                </a>
            </li>
            <li {{ Request::is('teaching/history') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('teaching.history') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">history_edu</i></div>
                    <div class="menu-title">Riwayat KBM Saya</div>
                </a>
            </li>
            <li class="menu-label">GURU PIKET</li>
            <li {{ Request::is('piket/dashboard*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('piket.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">dashboard</i></div>
                    <div class="menu-title">Monitoring Piket</div>
                </a>
            </li>
            <li {{ Request::is('piket/student-absences*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('piket.student-absences') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">fact_check</i></div>
                    <div class="menu-title">Koreksi Presensi Siswa</div>
                </a>
            </li>
            <li {{ Request::is('piket/teacher-absences*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('piket.teacher-absences') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">person_off</i></div>
                    <div class="menu-title">Absensi Guru & Tugas</div>
                </a>
            </li>
            <li {{ Request::is('piket/school-events*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('piket.school-events') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">event</i></div>
                    <div class="menu-title">Event & Kejadian Sekolah</div>
                </a>
            </li>
            <li class="menu-label">PENANGGUNG JAWAB KEGIATAN</li>
            <li {{ Request::is('special-activities*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('special-activities.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">assignment</i></div>
                    <div class="menu-title">Laporan Kegiatan Spesifik</div>
                </a>
            </li>
            <li class="menu-label">PENANGGUNG JAWAB HARIAN (PH)</li>
            <li {{ Request::is('ph/dashboard*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('ph.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">fact_check</i></div>
                    <div class="menu-title">Laporan & Catatan PH</div>
                </a>
            </li>
            <li class="menu-label">KEPALA DEPARTEMEN</li>
            <li {{ Request::is('kadep/dashboard*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('kadep.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">supervisor_account</i></div>
                    <div class="menu-title">Laporan & Catatan Kadep</div>
                </a>
            </li>
            <li class="menu-label">KEPALA SEKOLAH</li>
            <li {{ Request::is('kepsek/dashboard*') ? 'class="mm-active"' : '' }}>
                <a href="{{ route('kepsek.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">school</i></div>
                    <div class="menu-title">Laporan & Arahan Kepsek</div>
                </a>
            </li>
            <li class="menu-label">CETAK LAPORAN</li>
            <li>
                <a href="{{ route('lho.print') }}" target="_blank">
                    <div class="parent-icon"><i class="material-icons-outlined">print</i></div>
                    <div class="menu-title">Cetak LHO Terpadu</div>
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