@php
    $panelId = filament()->getCurrentPanel()->getId();

    $panelTitle = match ($panelId) {
        'field-admin' => 'Admin Lapangan',
        'admin'       => 'Super Admin / Auditor',
        'auditor'     => 'Auditor',
        default       => 'Admin Panel',
    };

    $panelSubtitle = match ($panelId) {
        'field-admin' => 'Masuk untuk mengelola venue, lapangan, dan jadwal pertandingan.',
        'admin'       => 'Masuk untuk mengelola sistem MATCHGO secara menyeluruh.',
        'auditor'     => 'Masuk untuk mengakses laporan dan data audit MATCHGO.',
        default       => 'Masuk untuk melanjutkan ke panel MATCHGO.',
    };
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="admin-login-root" data-theme="dark" id="adminLoginRoot">

    <style>
        @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

        /* ─── Reset Filament simple layout ─── */
        .fi-simple-layout,
        .fi-simple-main,
        .fi-simple-main-ctn,
        .fi-simple-page,
        .fi-page,
        .fi-body {
            width: 100% !important;
            max-width: none !important;
            min-height: 100vh !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
        }
        .fi-simple-main,
        .fi-simple-main-ctn { display: block !important; }

        /* Sembunyikan heading/subheading bawaan Filament */
        .fi-simple-page > .fi-simple-page-ctn > h1,
        .fi-simple-page > .fi-simple-page-ctn > p { display: none !important; }

        /* ─── CSS Variables ─── */
        .admin-login-root {
            --lime:           #a3e635;
            --lime-hover:     #bef264;
            --lime-dim:       rgba(163, 230, 53, 0.12);
            --bg:             #070b14;
            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted:     #64748b;
            --border-color:   rgba(148, 163, 184, 0.10);
            --input-bg:       rgba(255, 255, 255, 0.06);
            --input-border:   rgba(255, 255, 255, 0.10);
            --card-bg:        rgba(255, 255, 255, 0.04);
            --card-border:    rgba(255, 255, 255, 0.08);
            --nav-bg:         rgba(7, 11, 20, 0.85);
            --error-text:     #fca5a5;
            width: 100vw !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            background: var(--bg);
            color: var(--text-primary);
            font-family: 'Inter', system-ui, sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        .admin-login-root[data-theme="light"] {
            --lime:           #65a30d;
            --lime-hover:     #84cc16;
            --lime-dim:       rgba(101, 163, 13, 0.10);
            --bg:             #f8fafc;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #64748b;
            --border-color:   rgba(15, 23, 42, 0.08);
            --input-bg:       #ffffff;
            --input-border:   #e2e8f0;
            --card-bg:        rgba(255, 255, 255, 0.95);
            --card-border:    rgba(0, 0, 0, 0.08);
            --nav-bg:         rgba(248, 250, 252, 0.88);
            --error-text:     #be123c;
            background: var(--bg);
        }

        /* ─── Navbar ─── */
        .navbar-matchgo {
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.7rem 0;
            z-index: 1050;
        }
        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .theme-toggle {
            width: 38px; height: 38px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: transparent;
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all .25s;
            font-size: 1rem;
        }
        .theme-toggle:hover { background: var(--lime-dim); color: var(--lime); border-color: var(--lime); }
        .theme-toggle .icon-sun  { display: none; }
        .theme-toggle .icon-moon { display: inline; }
        [data-theme="light"] .theme-toggle .icon-sun  { display: inline; }
        [data-theme="light"] .theme-toggle .icon-moon { display: none; }

        /* ─── Layout ─── */
        .admin-login-page {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            width: 100%;
            padding: clamp(5.5rem, 12vw, 8rem) 1rem clamp(2rem, 5vw, 4rem) !important;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .admin-login-shell { width: 100%; max-width: 440px; }
        .admin-back-home {
            font-size: .82rem;
            color: var(--text-muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            margin-bottom: 1.25rem;
            transition: color .2s;
        }
        .admin-back-home:hover { color: var(--lime); text-decoration: none; }

        /* ─── Card ─── */
        .admin-login-card {
            width: 100%;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2.25rem 2rem;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: relative;
            z-index: 2;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.15);
        }
        [data-theme="light"] .admin-login-card { box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08); }

        .admin-login-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 .3rem;
            line-height: 1.3;
        }
        .admin-login-subtitle {
            font-size: .875rem;
            color: var(--text-muted);
            margin: 0 0 1.5rem;
            line-height: 1.55;
        }

        /* ─── Override Filament v5 form fields ─── */
        .admin-login-card .fi-fo-field-wrp { margin-bottom: .85rem; }

        .admin-login-card .fi-fo-field-wrp-label,
        .admin-login-card .fi-fo-field-wrp > label {
            display: block !important;
            font-size: .82rem !important;
            font-weight: 600 !important;
            color: var(--text-secondary) !important;
            margin-bottom: .38rem !important;
        }
        .admin-login-card .fi-input-wrp {
            background: var(--input-bg) !important;
            border: 1px solid var(--input-border) !important;
            border-radius: 10px !important;
            transition: border-color .2s, box-shadow .2s !important;
            box-shadow: none !important;
        }
        .admin-login-card .fi-input-wrp:focus-within {
            border-color: var(--lime) !important;
            box-shadow: 0 0 0 3px var(--lime-dim) !important;
        }
        .admin-login-card .fi-input {
            background: transparent !important;
            color: var(--text-primary) !important;
            font-size: .9rem !important;
            border: none !important;
            box-shadow: none !important;
            padding: .65rem .9rem !important;
        }
        .admin-login-card .fi-input::placeholder { color: var(--text-muted) !important; }
        .admin-login-card .fi-input:focus { outline: none !important; box-shadow: none !important; }

        /* Password toggle icon */
        .admin-login-card .fi-input-wrp button {
            color: var(--text-muted) !important;
            background: transparent !important;
            border: none !important;
            transition: color .2s !important;
        }
        .admin-login-card .fi-input-wrp button:hover { color: var(--lime) !important; }

        /* Checkbox (Remember me) */
        .admin-login-card .fi-checkbox-input,
        .admin-login-card input[type="checkbox"] { accent-color: var(--lime) !important; }
        .admin-login-card .fi-fo-checkbox .fi-fo-field-wrp-label,
        .admin-login-card .fi-fo-checkbox label {
            color: var(--text-secondary) !important;
            font-size: .82rem !important;
            font-weight: 500 !important;
        }

        /* Validation error */
        .admin-login-card .fi-fo-field-wrp-error-message {
            color: var(--error-text) !important;
            font-size: .78rem !important;
            margin-top: .25rem !important;
        }

        /* Filament form container */
        .admin-login-card .fi-form,
        .admin-login-card [data-form] {
            background: transparent !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* ─── Manual login fields — compatible with Filament BaseLogin ─── */
        .admin-alert {
            background: rgba(239, 68, 68, .11);
            border: 1px solid rgba(239, 68, 68, .22);
            color: var(--error-text);
            border-radius: 10px;
            padding: .75rem .9rem;
            font-size: .84rem;
            margin-bottom: 1rem;
        }
        .admin-field {
            margin-bottom: .95rem;
        }
        .admin-label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: .38rem;
        }
        .admin-input-wrap {
            position: relative;
        }
        .admin-input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            pointer-events: none;
        }
        .admin-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            color: var(--text-primary);
            padding: .65rem .9rem .65rem 2.65rem;
            font-size: .9rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .admin-input:focus {
            border-color: var(--lime);
            box-shadow: 0 0 0 3px var(--lime-dim);
        }
        .admin-input::placeholder {
            color: var(--text-muted);
        }
        .admin-password-input {
            padding-right: 2.8rem;
        }
        .admin-toggle-password {
            position: absolute;
            right: .9rem;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            line-height: 1;
            transition: color .2s;
        }
        .admin-toggle-password:hover {
            color: var(--lime);
        }
        .admin-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 1rem 0 1.25rem;
        }
        .admin-remember {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--text-secondary);
            font-size: .84rem;
            cursor: pointer;
        }
        .admin-remember input {
            width: 15px;
            height: 15px;
            accent-color: var(--lime);
        }

        /* ─── Submit button ─── */
        .admin-submit {
            box-sizing: border-box;
            width: 100%;
            margin-top: 1.25rem;
            border: none;
            border-radius: 10px;
            background: var(--lime);
            color: #0f172a;
            font-weight: 700;
            font-size: .95rem;
            padding: .72rem 1.5rem;
            cursor: pointer;
            transition: background .2s, transform .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }
        .admin-submit:hover { background: var(--lime-hover); transform: translateY(-1px); }
        .admin-submit:active { transform: translateY(0); }
        .admin-submit:disabled { opacity: .7; cursor: not-allowed; transform: none; }

        /* Spinner */
        .admin-spin {
            width: 16px; height: 16px;
            border: 2px solid rgba(15, 23, 42, .3);
            border-top-color: #0f172a;
            border-radius: 50%;
            display: inline-block;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ─── Panel note ─── */
        .admin-panel-note {
            margin-top: 1.25rem;
            background: rgba(163, 230, 53, .05);
            border: 1px solid rgba(163, 230, 53, .15);
            border-radius: 10px;
            padding: .85rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: .7rem;
        }
        [data-theme="light"] .admin-panel-note {
            background: rgba(101, 163, 13, .06);
            border-color: rgba(101, 163, 13, .2);
        }
        .admin-panel-note i { color: var(--lime); margin-top: .1rem; flex-shrink: 0; }
        .admin-panel-note strong { display: block; color: var(--lime); font-size: .82rem; margin-bottom: .15rem; }
        .admin-panel-note small { color: var(--text-muted); font-size: .74rem; line-height: 1.5; }

        /* ─── Responsive ─── */
        @media (max-width: 576px) {
            .admin-login-page { padding-left: .85rem !important; padding-right: .85rem !important; padding-top: 5.75rem !important; }
            .admin-login-card { padding: 1.75rem 1.35rem !important; border-radius: 18px; }
            .admin-login-title { font-size: 1.3rem; }
        }
        @media (max-width: 380px) {
            .admin-login-page { padding-top: 5.5rem !important; padding-left: .65rem !important; padding-right: .65rem !important; }
            .admin-login-card { padding: 1.5rem 1.1rem !important; border-radius: 16px; }
            .admin-login-title { font-size: 1.2rem; }
            .admin-login-subtitle { font-size: .82rem; margin-bottom: 1.25rem; }
        }
    </style>

    {{-- ── Navbar ── --}}
    <nav class="navbar navbar-matchgo fixed-top">
        <div class="container">
            <a class="navbar-brand-custom" href="{{ url('/') }}">
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

    {{-- ── Main ── --}}
    <main class="admin-login-page">
        <div class="admin-login-shell">

            <a href="{{ url('/login') }}" class="admin-back-home">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Login Pengguna
            </a>

            <section class="admin-login-card">

                <h1 class="admin-login-title">Selamat Datang Kembali</h1>
                <p class="admin-login-subtitle">{{ $panelSubtitle }}</p>

                @if ($errors->any())
                    <div class="admin-alert">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form wire:submit.prevent="authenticate" id="adminLoginForm">
                    {{ $this->form }}

                    <button
                        type="submit"
                        class="admin-submit"
                        wire:loading.attr="disabled"
                        wire:target="authenticate"
                    >
                        <span wire:loading.remove wire:target="authenticate">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Masuk ke Panel
                        </span>
                        <span wire:loading wire:target="authenticate">
                            <span class="admin-spin"></span>
                            Memproses...
                        </span>
                    </button>
                </form>

                {{-- Wajib untuk notifikasi & modal Filament --}}
                <x-filament-actions::modals />

                <div class="admin-panel-note" role="note">
                    <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                    <div>
                        <strong>Akses Khusus — {{ $panelTitle }}</strong>
                        <small>
                            Halaman ini hanya untuk Super Admin,
                            Admin Lapangan, dan Auditor yang
                            memiliki hak akses sistem MATCHGO.
                        </small>
                    </div>
                </div>

            </section>
        </div>
    </main>

    <script>
        function toggleAdminPassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (!passwordInput || !toggleIcon) return;

            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        }

        (function () {
            'use strict';

            const root        = document.getElementById('adminLoginRoot');
            const themeToggle = document.getElementById('adminThemeToggle');
            const savedTheme  = localStorage.getItem('matchgo-admin-theme');

            if (savedTheme) root.setAttribute('data-theme', savedTheme);

            themeToggle && themeToggle.addEventListener('click', function () {
                const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                localStorage.setItem('matchgo-admin-theme', next);
            });
        })();
    </script>

</div>