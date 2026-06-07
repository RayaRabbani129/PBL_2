@php
    $panelId = filament()->getCurrentPanel()->getId();

    $panelTitle = match ($panelId) {
        'field-admin' => 'Admin Lapangan',
        'admin'       => 'Admin MATCHGO',
        'auditor'     => 'Auditor',
        default       => 'Admin Panel',
    };

    $panelSubtitle = match ($panelId) {
        'field-admin' => 'Masuk untuk mengelola venue, lapangan, dan jadwal pertandingan.',
        'admin'       => 'Masuk sebagai Super Admin, Admin Lapangan, atau Auditor.',
        'auditor'     => 'Masuk untuk mengakses laporan dan data audit MATCHGO.',
        default       => 'Masuk untuk melanjutkan ke panel MATCHGO.',
    };
@endphp

<x-filament-panels::page.simple>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: var(--surface-0) !important;
        }

        .fi-simple-header,
        .fi-simple-header-heading,
        .fi-simple-header-subheading,
        .fi-logo {
            display: none !important;
        }

        .fi-simple-layout,
        .fi-simple-main,
        .fi-simple-main-ctn,
        .fi-simple-page {
            width: 100% !important;
            max-width: none !important;
            min-height: 100vh !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
        }

        .fi-simple-main,
        .fi-simple-main-ctn {
            display: block !important;
        }

        .admin-login-root,
        .admin-login-root[data-theme="dark"] {
            --accent: #A3B14B;
            --accent-hover: #8f9c40;
            --accent-light: #d4e170;

            --accent-dim: rgba(163,177,75,0.12);
            --accent-dim-hover: rgba(163,177,75,0.20);

            --surface-0: #0C0C0C;
            --surface-1: #111111;
            --surface-2: #161616;
            --surface-3: #1C1C1C;
            --surface-4: #242424;
            --surface-5: #2C2C2C;

            --txt-primary: #F5F5F0;
            --txt-secondary: #A8A29E;
            --txt-muted: #78716C;
            --txt-faint: #57534E;

            --border-subtle: rgba(255,255,255,0.06);
            --border-medium: rgba(255,255,255,0.10);
            --border-strong: rgba(255,255,255,0.18);

            --topbar-bg: rgba(12,12,12,0.88);

            --shadow-md: 0 4px 16px rgba(0,0,0,0.6);
            --btn-primary-txt: #0C0C0C;

            width: 100%;
            min-height: 100vh;
            background: var(--surface-0);
            color: var(--txt-primary);
            font-family: Inter, system-ui, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        .admin-login-root[data-theme="light"] {
            --accent: #7A8C2E;
            --accent-hover: #69791f;
            --accent-light: #4D6010;

            --accent-dim: rgba(122,140,46,0.10);
            --accent-dim-hover: rgba(122,140,46,0.18);

            --surface-0: #F8F8F4;
            --surface-1: #FFFFFF;
            --surface-2: #F4F4EF;
            --surface-3: #EEEEE8;
            --surface-4: #E6E6DF;
            --surface-5: #DDDDD5;

            --txt-primary: #1A1A17;
            --txt-secondary: #4A4A42;
            --txt-muted: #6E6E64;
            --txt-faint: #9E9E93;

            --border-subtle: rgba(0,0,0,0.07);
            --border-medium: rgba(0,0,0,0.11);
            --border-strong: rgba(0,0,0,0.18);

            --topbar-bg: rgba(248,248,244,0.92);

            --shadow-md: 0 4px 16px rgba(0,0,0,0.10);
            --btn-primary-txt: #FFFFFF;

            background: var(--surface-0);
            color: var(--txt-primary);
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(var(--border-subtle) 1px, transparent 1px),
                linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: .75;
        }

        .bg-radial-top {
            position: fixed;
            top: -250px;
            left: 50%;
            z-index: 0;
            width: 700px;
            height: 700px;
            transform: translateX(-50%);
            pointer-events: none;
            background: radial-gradient(circle, var(--accent-dim-hover), transparent 70%);
            filter: blur(60px);
        }

        .navbar-matchgo {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: var(--topbar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-subtle);
            z-index: 50;
        }

        .navbar-inner {
            height: 64px;
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .theme-toggle {
            width: 38px;
            height: 38px;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            background: transparent;
            color: var(--txt-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .2s ease;
        }

        .theme-toggle:hover {
            background: var(--accent-dim);
            color: var(--accent);
            border-color: var(--accent);
        }

        .theme-toggle .icon-sun {
            display: none;
        }

        .theme-toggle .icon-moon {
            display: inline;
        }

        [data-theme="light"] .theme-toggle .icon-sun {
            display: inline;
        }

        [data-theme="light"] .theme-toggle .icon-moon {
            display: none;
        }

        .admin-login-page {
            min-height: 100vh;
            width: 100%;
            padding: 7rem 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .admin-login-shell {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 2;
        }

        .admin-back-home {
            font-size: .82rem;
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            margin-bottom: 1.5rem;
            transition: opacity .2s ease;
        }

        .admin-back-home:hover {
            opacity: .75;
            color: var(--accent);
        }

        .admin-login-card {
            width: 100%;
            background: var(--surface-2);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: var(--shadow-md);
            color: var(--txt-primary);
        }

        .admin-login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--txt-primary) !important;
            margin-bottom: .25rem;
        }

        .admin-login-subtitle {
            font-size: .92rem;
            color: var(--txt-muted) !important;
            margin-bottom: 1.5rem;
            line-height: 1.55;
        }

        .admin-login-card form {
            width: 100% !important;
        }

        .admin-login-card label,
        .admin-login-card .fi-fo-field-wrp-label {
            color: var(--txt-secondary) !important;
            font-size: .85rem !important;
            font-weight: 600 !important;
        }

        .admin-login-card .fi-input-wrp {
            background: var(--surface-3) !important;
            border: 1px solid var(--border-medium) !important;
            border-radius: 12px !important;
            box-shadow: none !important;
        }

        .admin-login-card .fi-input-wrp:focus-within {
            border-color: var(--accent) !important;
            background: var(--surface-4) !important;
            box-shadow: 0 0 0 4px var(--accent-dim) !important;
        }

        .admin-login-card .fi-input {
            background: transparent !important;
            color: var(--txt-primary) !important;
            border: none !important;
            box-shadow: none !important;
        }

        .admin-login-card .fi-input::placeholder {
            color: var(--txt-muted) !important;
        }

        .admin-login-card .fi-input-wrp button {
            color: var(--txt-muted) !important;
            background: transparent !important;
        }

        .admin-login-card .fi-input-wrp button:hover {
            color: var(--accent) !important;
        }

        .admin-login-card input[type="checkbox"] {
            accent-color: var(--accent) !important;
        }

        .admin-login-card .fi-fo-checkbox label,
        .admin-login-card .fi-checkbox-input + label {
            color: var(--txt-muted) !important;
        }

        .admin-login-card .fi-btn {
            width: 100% !important;
            justify-content: center !important;
            border-radius: 12px !important;
            background: var(--accent) !important;
            color: var(--btn-primary-txt) !important;
            font-weight: 700 !important;
            margin-top: 1rem !important;
            padding: .8rem 1.5rem !important;
            border: none !important;
            transition: all .2s ease !important;
        }

        .admin-login-card .fi-btn:hover {
            background: var(--accent-hover) !important;
            transform: translateY(-1px);
        }

        .admin-login-card .fi-fo-field-wrp-error-message {
            color: #fca5a5 !important;
            font-size: .8rem !important;
        }

        .admin-panel-note {
            margin-top: 1.25rem;
            background: var(--accent-dim);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: .9rem 1rem;
            display: flex;
            gap: .8rem;
        }

        .admin-panel-note i {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--accent-dim-hover);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            flex-shrink: 0;
        }

        .admin-panel-note strong {
            display: block;
            color: var(--accent);
            font-size: .82rem;
            margin-bottom: .15rem;
        }

        .admin-panel-note small {
            color: var(--txt-muted);
            font-size: .74rem;
            line-height: 1.5;
        }

        @media (max-width: 576px) {
            .admin-login-page {
                padding-top: 6rem;
                padding-left: .85rem;
                padding-right: .85rem;
            }

            .admin-login-card {
                padding: 1.75rem 1.35rem;
                border-radius: 18px;
            }
        }
    </style>

    <div class="admin-login-root" id="adminLoginRoot">
        <div class="bg-grid"></div>
        <div class="bg-radial-top"></div>

        <nav class="navbar-matchgo">
            <div class="navbar-inner">
                <a href="{{ url('/') }}">
                    <img
                        src="{{ asset('img/logo/logo.png') }}"
                        alt="MatchGo Logo"
                        style="height:32px; width:auto; object-fit:contain;"
                    >
                </a>

                <button type="button" class="theme-toggle" id="adminThemeToggle" aria-label="Toggle theme">
                    <i class="bi bi-moon-fill icon-moon"></i>
                    <i class="bi bi-sun-fill icon-sun"></i>
                </button>
            </div>
        </nav>

        <main class="admin-login-page">
            <div class="admin-login-shell">
                <a href="{{ url('/login') }}" class="admin-back-home">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Login Pengguna
                </a>

                <section class="admin-login-card">
                    <h1 class="admin-login-title">Selamat Datang Kembali</h1>
                    <p class="admin-login-subtitle">{{ $panelSubtitle }}</p>

                    {{ $this->content }}

                    <div class="admin-panel-note">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Akses Khusus — {{ $panelTitle }}</strong>
                            <small>
                                Super Admin, Admin Lapangan, dan Auditor masuk dari halaman ini.
                            </small>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.getElementById('adminLoginRoot');
            const html = document.documentElement;
            const themeToggle = document.getElementById('adminThemeToggle');

            function getTheme() {
                return (
                    localStorage.getItem('theme') ||
                    localStorage.getItem('matchgo-theme') ||
                    localStorage.getItem('matchgo-theme-mode') ||
                    html.getAttribute('data-theme') ||
                    'dark'
                );
            }

            function setTheme(theme) {
                if (! root) return;

                root.setAttribute('data-theme', theme);
                html.setAttribute('data-theme', theme);

                localStorage.setItem('theme', theme);
                localStorage.setItem('matchgo-theme', theme);
                localStorage.setItem('matchgo-theme-mode', theme);
            }

            setTheme(getTheme());

            themeToggle?.addEventListener('click', function () {
                const current = root.getAttribute('data-theme') || getTheme();
                const next = current === 'dark' ? 'light' : 'dark';

                setTheme(next);
            });
        });
    </script>
</x-filament-panels::page.simple>
