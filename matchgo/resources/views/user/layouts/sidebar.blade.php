<aside class="mg-sidebar" id="mgSidebar">
    
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="mg-sidebar-logo">
        <div class="mg-sidebar-logo-icon">
            <img src="{{ asset('img/logo/logo.png') }}" alt="MatchGo Logo" style="width:100%;height:100%;object-fit:contain;">
        </div>
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

        <a href="{{ url('/schedule') }}"
           class="mg-nav-item {{ request()->is('schedule*') ? 'active' : '' }}">
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

        <div class="mg-nav-section" style="margin-top: 8px;">Akun</div>

        <a href="{{ url('profile') }}"
           class="mg-nav-item {{ request()->is('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            Profil
        </a>

        {{-- Notifikasi --}}
        <a href="{{ route('notifications.index') }}"
           class="mg-nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i>
            Notifikasi
            @if($unreadCount > 0)
                <span class="mg-nav-badge" id="notif-sidebar-badge">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @else
                <span class="mg-nav-badge" id="notif-sidebar-badge" style="display:none;">0</span>
            @endif
        </a>

    </nav>

{{-- User bottom --}}
@auth
<div class="mg-sidebar-user">
    <div class="mg-user-row">

        {{-- Klik area user untuk ke profile --}}
        <a href="{{ url('/profile') }}"
           style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;text-decoration:none;color:inherit;">

            <div class="mg-user-avatar">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                         alt="Avatar"
                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @endif
            </div>

            <div style="flex:1;min-width:0;">
                <p class="mg-user-name">{{ Auth::user()->name }}</p>
                <p class="mg-user-team">
                    @if(Auth::user()->team)
                        {{ Auth::user()->team->name }}
                    @else
                        Player
                    @endif
                </p>
            </div>
        </a>

        {{-- Tombol logout tetap sendiri --}}
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