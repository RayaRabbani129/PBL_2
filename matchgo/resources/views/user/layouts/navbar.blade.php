    <header class="mg-topbar">
        {{-- Left: Mobile toggle + Page title --}}
        <div class="d-flex align-items-center gap-3">
            <button
                id="sidebarToggle"
                class="mg-icon-btn"
                aria-label="Toggle sidebar"
                style="display:none;"
            >
                <i class="bi bi-list"></i>
            </button>
            <span class="mg-topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>

        {{-- Right: Search · Notif · Theme · User --}}
        <div class="mg-topbar-right">

            {{-- Tutorial --}}
            <a href="#" class="mg-icon-btn" aria-label="Notifikasi">
                <i class="bi bi-question-circle"></i>
                <span class="mg-notif-dot"></span>
            </a>

            {{-- Notification --}}
            <a href="#" class="mg-icon-btn" aria-label="Notifikasi">
                <i class="bi bi-bell"></i>
                <span class="mg-notif-dot"></span>
            </a>

            {{-- Theme Toggle --}}
            <button
                id="themeToggle"
                class="mg-theme-toggle"
                aria-label="Toggle tema"
                title="Ganti tema"
            >
                <i class="bi bi-moon-fill icon-moon"></i>
                <i class="bi bi-sun-fill icon-sun"></i>
            </button>

            <div class="mg-divider-v hide-mobile"></div>

            {{-- User dropdown --}}
            <div id="topbarUserBtn" class="mg-topbar-user">
                <div class="mg-topbar-avatar">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <span class="mg-topbar-username hide-mobile">
                    {{ auth()->user()->name ?? 'User' }}
                </span>
                <i class="bi bi-chevron-down" style="font-size:0.65rem; color:var(--txt-muted);"></i>

                <div id="topbarDropdown" class="mg-dropdown">
                    <a href="{{ route('profile.index') }}" class="mg-dropdown-item">
                        <i class="bi bi-person"></i> Profil Saya
                    </a>

                    <div class="mg-dropdown-sep"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mg-dropdown-item danger">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>