<div class="sidebar-matchgo">
    <div class="sidebar-header mb-4">
        <p class="sidebar-title mb-1">Player Menu</p>
        <p class="sidebar-description">Akses fitur utama MATCHGO untuk tim dan matchmaking.</p>
    </div>

    <nav class="nav flex-column gap-2">
        <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>
        <a href="{{ url('/teams') }}" class="nav-link {{ request()->is('teams*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Tim Saya
        </a>
        <a href="{{ url('/schedule') }}" class="nav-link {{ request()->is('schedule*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i>
            Jadwal Saya
        </a>
        <a href="{{ url('/matchmaking') }}" class="nav-link {{ request()->is('matchmaking*') ? 'active' : '' }}">
            <i class="bi bi-search"></i>
            Matchmaking
        </a>
        <a href="{{ url('/matches') }}" class="nav-link {{ request()->is('matches*') ? 'active' : '' }}">
            <i class="bi bi-trophy"></i>
            Match
        </a>
        <a href="{{ url('/venues') }}" class="nav-link {{ request()->is('venues*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i>
            Lapangan
        </a>
        <a href="{{ url('/split-bill') }}" class="nav-link {{ request()->is('split-bill*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            Split Bill
        </a>
        <a href="{{ url('/profile') }}" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            Profil
        </a>
    </nav>

    <div class="sidebar-cta">
        <p>Mulai flow utama kamu:</p>
        <a href="{{ url('/matchmaking') }}" class="btn btn-lime">
            <i class="bi bi-lightning-charge-fill me-2"></i>
            Cari Lawan
        </a>
    </div>
</div>
