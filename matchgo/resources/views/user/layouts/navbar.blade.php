<header class="mg-topbar">
    {{-- Kiri: judul halaman --}}
    <div class="d-flex align-items-center gap-3">
        {{-- Tombol hamburger mobile --}}
        <button id="sidebarToggle" class="mg-icon-btn d-none d-lg-none" aria-label="Menu"
            style="display: none !important;">
            <i class="bi bi-list" style="font-size: 1.1rem;"></i>
        </button>
        <button id="sidebarToggle" class="mg-icon-btn" aria-label="Menu"
            style="display: none;"
            @media(max-width:1199px) { display: flex; }>
        </button>

        <h1 class="mg-topbar-title">
            @yield('page_title', 'Dashboard')
        </h1>
    </div>

    {{-- Kanan: search + notif + user --}}
    <div class="mg-topbar-right">

        {{-- Search --}}
        <div class="mg-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="mg-search" placeholder="Cari sesuatu...">
        </div>

        @auth
            {{-- Notifikasi --}}
            <a href="#" class="mg-icon-btn" aria-label="Notifikasi">
                <i class="bi bi-bell"></i>
                <span class="mg-notif-dot"></span>
            </a>

            <div class="mg-divider-v"></div>

            {{-- User dropdown --}}
            <div style="position: relative;">
                <button id="topbarUserBtn" class="mg-topbar-user" style="background:none;border:none;">
                    <div class="mg-topbar-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <span class="mg-topbar-username">
                        {{ Str::limit(Auth::user()->name, 12) }}
                    </span>
                    <i class="bi bi-chevron-down" style="font-size:0.7rem;color:var(--txt-muted);"></i>
                </button>

                <div id="topbarDropdown" class="mg-dropdown">
                    <a href="#" class="mg-dropdown-item">
                        <i class="bi bi-person" style="width:16px;text-align:center;"></i>
                        Profil Saya
                    </a>
                    <a href="#" class="mg-dropdown-item">
                        <i class="bi bi-gear" style="width:16px;text-align:center;"></i>
                        Pengaturan
                    </a>
                    <div class="mg-dropdown-sep"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mg-dropdown-item danger">
                            <i class="bi bi-box-arrow-right" style="width:16px;text-align:center;"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-outline-lime btn-sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn-lime btn-sm">Daftar</a>
        @endauth
    </div>
</header>