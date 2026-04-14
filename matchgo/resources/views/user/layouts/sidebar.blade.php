<aside class="mg-sidebar" id="mgSidebar">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="mg-sidebar-logo">
        <div class="mg-sidebar-logo-icon">M</div>
        <div>
            <span class="mg-sidebar-logo-text">MatchGo</span>
            <span class="mg-sidebar-logo-sub">v1.0.0</span>
        </div>
    </a>

    {{-- Nav --}}
    <nav class="mg-sidebar-nav">

        <div class="mg-nav-section">Main Menu</div>

        <a href="{{ url('/dashboard') }}"
           class="mg-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="{{ url('/team') }}"
           class="mg-nav-item {{ request()->is('team*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Tim Saya
        </a>

        <a href="{{ url('/team/schedule') }}"
           class="mg-nav-item {{ request()->is('team/schedule*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i>
            Jadwal Saya
        </a>

        <a href="{{ url('/matchmaking') }}"
           class="mg-nav-item {{ request()->is('matchmaking*') ? 'active' : '' }}">
            <i class="bi bi-search"></i>
            Matchmaking
        </a>

        <a href="{{ url('/matches') }}"
           class="mg-nav-item {{ request()->is('matches*') ? 'active' : '' }}">
            <i class="bi bi-trophy"></i>
            Match
        </a>

        <a href="{{ url('/venues') }}"
           class="mg-nav-item {{ request()->is('venues*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i>
            Lapangan
        </a>

        <a href="{{ url('/split-bill') }}"
           class="mg-nav-item {{ request()->is('split-bill*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            Split Bill
        </a>

        <div class="mg-nav-section" style="margin-top: 8px;">Akun</div>

        <a href="{{ url('/profile') }}"
           class="mg-nav-item {{ request()->is('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            Profil
        </a>

        <a href="#"
           class="mg-nav-item"
           style="position: relative;">
            <i class="bi bi-bell"></i>
            Notifikasi
            {{-- Uncomment jika ada unread notif: --}}
            {{-- <span style="position:absolute;top:8px;left:28px;width:16px;height:16px;background:#EF4444;border-radius:50%;font-size:9px;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;border:2px solid var(--surface-1);">3</span> --}}
        </a>

    </nav>

    {{-- User bottom --}}
    @auth
    <div class="mg-sidebar-user">
        <div class="mg-user-row">
            <div class="mg-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <p class="mg-user-name">{{ Auth::user()->name }}</p>
                <p class="mg-user-team">Player</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="flex-shrink:0;">
                @csrf
                <button type="submit"
                    style="width:30px;height:30px;border-radius:8px;background:none;border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.15s;color:var(--txt-muted);"
                    onmouseover="this.style.background='rgba(255,255,255,0.06)'"
                    onmouseout="this.style.background='none'"
                    title="Keluar">
                    <i class="bi bi-box-arrow-right" style="font-size:0.85rem;"></i>
                </button>
            </form>
        </div>
    </div>
    @endauth

</aside>