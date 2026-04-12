    <nav class="navbar navbar-expand-lg navbar-matchgo fixed-top">
        <div class="container">
            <a class="navbar-brand-custom" href="{{ url('/') }}">
                <span class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                MATCH<span class="brand-accent">GO</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarUser"
                aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarUser">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                           href="{{ url('/dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('teams*') ? 'active' : '' }}"
                           href="#">
                            <i class="bi bi-people me-1"></i>Tim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('matches*') ? 'active' : '' }}"
                           href="#">
                            <i class="bi bi-trophy me-1"></i>Match
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('venues*') ? 'active' : '' }}"
                           href="#">
                            <i class="bi bi-geo-alt me-1"></i>Venue
                        </a>
                    </li>
                </ul>

                <div class="d-flex gap-2 align-items-center mt-3 mt-lg-0">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                        <i class="bi bi-moon-fill icon-moon"></i>
                        <i class="bi bi-sun-fill icon-sun"></i>
                    </button>

                    @auth
                        <!-- Notifikasi -->
                        <button class="btn btn-sm"
                            style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: var(--text-secondary, #cbd5e1); border-radius: 8px; padding: 0.35rem 0.65rem;">
                            <i class="bi bi-bell"></i>
                        </button>

                        <!-- Dropdown User -->
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-2"
                                style="background: rgba(163,230,53,0.08); border: 1px solid rgba(163,230,53,0.2); color: var(--lime, #a3e635); border-radius: 8px; padding: 0.35rem 0.85rem; font-weight: 600; font-size: 0.85rem;"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                {{ Str::limit(Auth::user()->name, 12) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end"
                                style="background: var(--card-bg-solid, #131c2e); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0.5rem; min-width: 180px;">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        style="color: var(--text-secondary, #cbd5e1); border-radius: 8px; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                        <i class="bi bi-person me-2"></i>Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"
                                        style="color: var(--text-secondary, #cbd5e1); border-radius: 8px; font-size: 0.875rem; padding: 0.5rem 0.75rem;">
                                        <i class="bi bi-gear me-2"></i>Pengaturan
                                    </a>
                                </li>
                                <li><hr style="border-color: rgba(255,255,255,0.08); margin: 0.4rem 0;"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"
                                            style="color: #fca5a5; border-radius: 8px; font-size: 0.875rem; padding: 0.5rem 0.75rem; background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-lime btn-sm">Masuk</a>
                        <a href="#" class="btn btn-lime btn-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>