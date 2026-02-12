<header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-4 d-flex flex-row justify-content-between">
        <div class="btn-toggle">
            <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
        </div>
        <div class="search-bar flex-grow-1">
            <div class="position-relative">
                <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text" placeholder="Search">
                <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
                <div class="search-popup p-3">
                    <div class="card rounded-4 overflow-hidden">
                        <div class="card-header d-lg-none">
                            <div class="position-relative">
                                <input class="form-control rounded-5 px-5 mobile-search-control" type="text" placeholder="Search">
                                <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                                <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
                            </div>
                        </div>
                        <div class="card-body search-content">
                            <p class="search-title">Recent Searches</p>
                            <div class="d-flex align-items-start flex-wrap gap-2 kewords-wrapper"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="navbar-nav gap-1 nav-right-links align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                    data-bs-auto-close="outside" data-bs-toggle="dropdown" href="javascript:;"><i
                        class="material-icons-outlined">notifications</i>
                    <span class="badge-notify">1</span>
                </a>
                <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                    <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="notiy-title mb-0">Notifications</h5>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons-outlined">
                                    more_vert
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                                <div>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">grade</i>What's new ?</a>
                                </div>
                                <div>
                                    <hr class="dropdown-divider">
                                </div>
                                <div>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined fs-6">leaderboard</i>Reports</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="notify-list">
                        <div>
                            <a class="dropdown-item border-bottom py-2" href="javascript:;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="">
                                        @if (Auth::user()->gender === 'L')
                                        <img src="{{ asset('metoxi/images/avatars/04.png') }}" class="rounded-circle" width="45" height="45" alt="">
                                        @else
                                        <img src="{{ asset('metoxi/images/avatars/06.png') }}" class="rounded-circle" width="45" height="45" alt="">
                                        @endif
                                    </div>
                                    <div class="">
                                        <h5 class="notify-title">Hai {{ Auth::user()->name }}</h5>
                                        <p class="mb-0 notify-desc">Selamat Datang 🤗</p>
                                        <p class="mb-0 notify-time">Today</p>
                                    </div>
                                    <div class="notify-close position-absolute end-0 me-3">
                                        <i class="material-icons-outlined fs-6">close</i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                    @if (Auth::user()->gender === 'L')
                    <img src="{{ asset('metoxi/images/avatars/11.png') }}" class="rounded-circle p-1 border" width="45" height="45">
                    @else
                    <img src="{{ asset('metoxi/images/avatars/12.png') }}" class="rounded-circle p-1 border" width="45" height="45">
                    @endif
                </a>
                <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                    <a class="dropdown-item  gap-2 py-2" href="javascript:;">
                        <div class="text-center">
                            @if (Auth::user()->gender === 'L')
                            <img src="{{ asset('metoxi/images/avatars/11.png') }}" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                            @else
                            <img src="{{ asset('metoxi/images/avatars/12.png') }}" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                            @endif
                            @php
                                $userName = Auth::user()->name;
                                $nameLen = strlen($userName);
                                if($nameLen > 10) {
                                    $name = substr($userName, 0, 10).'...';
                                } else {
                                    $name = $userName;
                                }
                            @endphp
                            <h5 class="user-name mb-0 fw-bold">Hello, {{ $name }}</h5>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i class="material-icons-outlined">local_bar</i>Setting</a>
                    <hr class="dropdown-divider">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2">
                            <i data-lucide="log-out" class="material-icons-outlined">power_settings_new</i>
                            {{ __('Sign Out') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
</header>