<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MATCHGO - Platform matchmaking futsal terbaik.">
    <title>@yield('title', 'MATCHGO')</title>

    {{-- Logo --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Bootstrap JS only -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(163,177,75,0.2); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(163,177,75,0.4); }

        /* ── Typography ── */
        .font-display { font-family: 'Manrope', sans-serif; }

        /* ══════════════════════════════════════════
           DARK MODE VARIABLES (default)
        ══════════════════════════════════════════ */
        :root,
        [data-theme="dark"] {
            --accent:          #A3B14B;
            --accent-hover:    #8f9c40;
            --accent-light:    #d4e170;
            --accent-dim:      rgba(163,177,75,0.12);
            --accent-dim-hover:rgba(163,177,75,0.20);

            --surface-0:       #0C0C0C;
            --surface-1:       #111111;
            --surface-2:       #161616;
            --surface-3:       #1C1C1C;
            --surface-4:       #242424;
            --surface-5:       #2C2C2C;

            --txt-primary:     #F5F5F0;
            --txt-secondary:   #A8A29E;
            --txt-muted:       #78716C;
            --txt-faint:       #57534E;

            --border-subtle:   rgba(255,255,255,0.06);
            --border-medium:   rgba(255,255,255,0.10);
            --border-strong:   rgba(255,255,255,0.18);

            --topbar-bg:       rgba(12,12,12,0.88);
            --shadow-sm:       0 1px 3px rgba(0,0,0,0.5);
            --shadow-md:       0 4px 16px rgba(0,0,0,0.6);

            --btn-primary-txt: #0C0C0C;

            --alert-success-bg:    rgba(163,177,75,0.08);
            --alert-success-bdr:   rgba(163,177,75,0.20);
            --alert-success-txt:   #d4e170;
            --alert-danger-bg:     rgba(239,68,68,0.08);
            --alert-danger-bdr:    rgba(239,68,68,0.18);
            --alert-danger-txt:    #fca5a5;
            --alert-warning-bg:    rgba(251,191,36,0.08);
            --alert-warning-bdr:   rgba(251,191,36,0.18);
            --alert-warning-txt:   #fcd34d;
            --alert-info-bg:       rgba(34,211,238,0.08);
            --alert-info-bdr:      rgba(34,211,238,0.18);
            --alert-info-txt:      #67e8f9;

            --sidebar-width:   260px;
            --topbar-h:        64px;
        }

        /* ══════════════════════════════════════════
           LIGHT MODE VARIABLES
        ══════════════════════════════════════════ */
        [data-theme="light"] {
            --accent:          #7A8C2E;
            --accent-hover:    #69791f;
            --accent-light:    #4D6010;
            --accent-dim:      rgba(122,140,46,0.10);
            --accent-dim-hover:rgba(122,140,46,0.18);

            --surface-0:       #F8F8F4;
            --surface-1:       #FFFFFF;
            --surface-2:       #F4F4EF;
            --surface-3:       #EEEEE8;
            --surface-4:       #E6E6DF;
            --surface-5:       #DDDDD5;

            --txt-primary:     #1A1A17;
            --txt-secondary:   #4A4A42;
            --txt-muted:       #6E6E64;
            --txt-faint:       #9E9E93;

            --border-subtle:   rgba(0,0,0,0.07);
            --border-medium:   rgba(0,0,0,0.11);
            --border-strong:   rgba(0,0,0,0.18);

            --topbar-bg:       rgba(248,248,244,0.92);
            --shadow-sm:       0 1px 3px rgba(0,0,0,0.08);
            --shadow-md:       0 4px 16px rgba(0,0,0,0.10);

            --btn-primary-txt: #FFFFFF;

            --alert-success-bg:    rgba(122,140,46,0.08);
            --alert-success-bdr:   rgba(122,140,46,0.22);
            --alert-success-txt:   #4D6010;
            --alert-danger-bg:     rgba(220,38,38,0.07);
            --alert-danger-bdr:    rgba(220,38,38,0.18);
            --alert-danger-txt:    #991b1b;
            --alert-warning-bg:    rgba(202,138,4,0.08);
            --alert-warning-bdr:   rgba(202,138,4,0.20);
            --alert-warning-txt:   #854d0e;
            --alert-info-bg:       rgba(6,182,212,0.07);
            --alert-info-bdr:      rgba(6,182,212,0.18);
            --alert-info-txt:      #155e75;
        }

        /* ── Body ── */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--surface-0);
            color: var(--txt-primary);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* ══════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════ */
        .mg-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--surface-1);
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            overflow-y: auto;
            transition: background 0.3s ease, border-color 0.3s ease, transform 0.25s ease;
        }

        /* Logo */
        .mg-sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            text-decoration: none;
            flex-shrink: 0;
            transition: border-color 0.3s;
        }

        .mg-sidebar-logo-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--accent-dim);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            color: var(--accent);
            font-size: 1rem;
            flex-shrink: 0;
            transition: background 0.3s;
        }

        .mg-sidebar-logo-text {
            font-family: 'Manrope', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--txt-primary);
            line-height: 1.2;
            transition: color 0.3s;
        }

        .mg-sidebar-logo-sub {
            font-size: 0.65rem;
            color: var(--txt-faint);
            display: block;
            transition: color 0.3s;
        }

        /* Nav */
        .mg-sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .mg-nav-section {
            padding: 16px 20px 6px;
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--txt-faint);
            transition: color 0.3s;
        }

        .mg-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            margin: 1px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            color: var(--txt-muted);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            position: relative;
        }

        .mg-nav-item i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .mg-nav-item:hover {
            background: var(--accent-dim);
            color: var(--txt-secondary);
            text-decoration: none;
        }

        .mg-nav-item.active {
            background: var(--accent-dim);
            color: var(--accent);
        }

        .mg-nav-item.active i { color: var(--accent); }

        /* Nav badge */
        .mg-nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: var(--btn-primary-txt);
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
            line-height: 1.6;
        }

        /* Sidebar user */
        .mg-sidebar-user {
            padding: 12px 16px;
            border-top: 1px solid var(--border-subtle);
            flex-shrink: 0;
            transition: border-color 0.3s;
        }

        .mg-user-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
        }

        .mg-user-row:hover { background: var(--accent-dim); text-decoration: none; }

        .mg-user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--accent-dim);
            border: 1.5px solid var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--accent);
            flex-shrink: 0;
        }

        .mg-user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--txt-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
            min-width: 0;
            transition: color 0.3s;
        }

        .mg-user-team {
            font-size: 0.7rem;
            color: var(--txt-muted);
            transition: color 0.3s;
        }

        /* ══════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════ */
        .mg-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-h);
            background: var(--topbar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1040;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .mg-topbar-title {
            font-family: 'Manrope', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--txt-primary);
            transition: color 0.3s;
        }

        .mg-topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Search */
        .mg-search-wrap { position: relative; }
        .mg-search-wrap i {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--txt-muted);
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.3s;
        }

        .mg-search {
            background: var(--surface-2);
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 8px 16px 8px 36px;
            font-size: 0.8rem;
            color: var(--txt-primary);
            outline: none;
            width: 220px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.3s, color 0.3s;
        }

        .mg-search::placeholder { color: var(--txt-faint); }
        .mg-search:focus { border-color: rgba(163,177,75,0.5); }

        /* Icon buttons */
        .mg-icon-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--surface-3);
            border: 1px solid var(--border-subtle);
            display: flex; align-items: center; justify-content: center;
            color: var(--txt-secondary);
            cursor: pointer;
            transition: background 0.15s, color 0.15s, border-color 0.3s;
            font-size: 1rem;
            position: relative;
            text-decoration: none;
        }

        .mg-icon-btn:hover {
            background: var(--accent-dim);
            color: var(--accent);
            border-color: rgba(163,177,75,0.3);
            text-decoration: none;
        }

        .mg-notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid var(--surface-1);
        }

        .mg-divider-v {
            width: 1px;
            height: 28px;
            background: var(--border-subtle);
        }

        /* ── Theme Toggle ── */
        .mg-theme-toggle {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--surface-3);
            border: 1px solid var(--border-subtle);
            display: flex; align-items: center; justify-content: center;
            color: var(--txt-secondary);
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border-color 0.3s;
            font-size: 0.95rem;
        }

        .mg-theme-toggle:hover {
            background: var(--accent-dim);
            color: var(--accent);
            border-color: rgba(163,177,75,0.3);
        }

        .mg-theme-toggle .icon-sun  { display: none; }
        .mg-theme-toggle .icon-moon { display: block; }

        [data-theme="light"] .mg-theme-toggle .icon-sun  { display: block; }
        [data-theme="light"] .mg-theme-toggle .icon-moon { display: none; }

        /* ── Topbar user dropdown ── */
        .mg-topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 10px;
            transition: background 0.15s;
            position: relative;
        }

        .mg-topbar-user:hover { background: var(--accent-dim); }

        .mg-topbar-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--accent-dim);
            border: 1.5px solid var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--accent);
        }

        .mg-topbar-username {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--txt-primary);
            transition: color 0.3s;
        }

        /* Dropdown */
        .mg-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 190px;
            background: var(--surface-2);
            border: 1px solid var(--border-medium);
            border-radius: 14px;
            padding: 6px;
            z-index: 9999;
            display: none;
            box-shadow: var(--shadow-md);
            transition: background 0.3s;
        }

        .mg-dropdown.show { display: block; }

        .mg-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 0.825rem;
            color: var(--txt-secondary);
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-family: 'Inter', sans-serif;
        }

        .mg-dropdown-item:hover {
            background: var(--accent-dim);
            color: var(--txt-primary);
            text-decoration: none;
        }

        .mg-dropdown-item.danger { color: #f87171; }
        .mg-dropdown-item.danger:hover { background: rgba(239,68,68,0.08); color: #fca5a5; }

        .mg-dropdown-sep {
            height: 1px;
            background: var(--border-subtle);
            margin: 4px 0;
        }

        /* ══════════════════════════════════════════
           MAIN LAYOUT
        ══════════════════════════════════════════ */
        .mg-main {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .mg-content {
            padding: 32px 40px 60px;
        }

        /* ══════════════════════════════════════════
           CARDS
        ══════════════════════════════════════════ */
        .card-matchgo {
            background: var(--surface-2);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 1.5rem;
            transition: border-color 0.2s, background 0.3s;
        }

        .card-matchgo:hover { border-color: var(--border-medium); }

        /* Accent card */
        .card-matchgo-accent {
            background: var(--accent-dim);
            border: 1px solid rgba(163,177,75,0.20);
            border-radius: 16px;
            padding: 1.5rem;
            transition: border-color 0.2s, background 0.3s;
        }

        /* ══════════════════════════════════════════
           PAGE HEADER
        ══════════════════════════════════════════ */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
            transition: border-color 0.3s;
        }

        .page-header h1,
        .page-header h2 {
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--txt-primary);
            margin-bottom: 0.25rem;
            transition: color 0.3s;
        }

        .page-header p {
            color: var(--txt-muted);
            font-size: 0.875rem;
            transition: color 0.3s;
        }

        /* ══════════════════════════════════════════
           BREADCRUMB
        ══════════════════════════════════════════ */
        .breadcrumb-matchgo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--txt-faint);
            margin-bottom: 1.5rem;
            list-style: none;
            padding: 0;
        }

        .breadcrumb-matchgo a {
            color: var(--txt-muted);
            text-decoration: none;
            transition: color 0.15s;
        }

        .breadcrumb-matchgo a:hover { color: var(--accent); }
        .breadcrumb-matchgo .active { color: var(--txt-primary); }
        .breadcrumb-matchgo .separator { color: var(--txt-faint); font-size: 0.65rem; }

        /* ══════════════════════════════════════════
           ALERTS
        ══════════════════════════════════════════ */
        .alert-matchgo-danger {
            background: var(--alert-danger-bg);
            border: 1px solid var(--alert-danger-bdr);
            border-radius: 12px;
            color: var(--alert-danger-txt);
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            transition: background 0.3s, border-color 0.3s, color 0.3s;
        }

        .alert-matchgo-danger ul { margin: 0; padding-left: 1.25rem; }
        .alert-matchgo-danger li { margin-bottom: 0.2rem; }
        .alert-matchgo-danger li:last-child { margin-bottom: 0; }

        .alert-matchgo-success {
            background: var(--alert-success-bg);
            border: 1px solid var(--alert-success-bdr);
            border-radius: 12px;
            color: var(--alert-success-txt);
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            transition: background 0.3s, border-color 0.3s, color 0.3s;
        }

        .alert-matchgo-info {
            background: var(--alert-info-bg);
            border: 1px solid var(--alert-info-bdr);
            border-radius: 12px;
            color: var(--alert-info-txt);
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            transition: background 0.3s, border-color 0.3s, color 0.3s;
        }

        .alert-matchgo-warning {
            background: var(--alert-warning-bg);
            border: 1px solid var(--alert-warning-bdr);
            border-radius: 12px;
            color: var(--alert-warning-txt);
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            transition: background 0.3s, border-color 0.3s, color 0.3s;
        }

        /* ══════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════ */
        .btn-lime, .btn-matchgo-primary {
            background: var(--accent);
            color: var(--btn-primary-txt);
            font-weight: 600;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.825rem;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s, color 0.3s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-lime:hover, .btn-matchgo-primary:hover {
            background: var(--accent-hover);
            color: var(--btn-primary-txt);
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-lime.btn-sm, .btn-matchgo-primary.btn-sm {
            padding: 7px 14px;
            font-size: 0.775rem;
            border-radius: 8px;
        }

        .btn-outline-lime, .btn-matchgo-outline {
            background: transparent;
            color: var(--txt-primary);
            font-weight: 500;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.825rem;
            border: 1px solid var(--border-strong);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, color 0.3s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-outline-lime:hover, .btn-matchgo-outline:hover {
            background: var(--accent-dim);
            border-color: var(--accent);
            color: var(--accent);
            text-decoration: none;
        }

        .btn-outline-lime.btn-sm, .btn-matchgo-outline.btn-sm {
            padding: 7px 14px;
            font-size: 0.775rem;
            border-radius: 8px;
        }

        /* Danger button */
        .btn-matchgo-danger {
            background: rgba(239,68,68,0.10);
            color: #f87171;
            font-weight: 600;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.825rem;
            border: 1px solid rgba(239,68,68,0.20);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-matchgo-danger:hover {
            background: rgba(239,68,68,0.18);
            color: #fca5a5;
            text-decoration: none;
        }

        /* ══════════════════════════════════════════
           BADGES / PILLS
        ══════════════════════════════════════════ */
        .badge-lime {
            background: var(--accent-dim);
            color: var(--accent);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 99px;
            border: 1px solid rgba(163,177,75,0.20);
        }

        .badge-muted {
            background: var(--surface-4);
            color: var(--txt-muted);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 99px;
            border: 1px solid var(--border-subtle);
        }

        /* ══════════════════════════════════════════
           STATS / METRIC CARDS
        ══════════════════════════════════════════ */
        .stat-card {
            background: var(--surface-2);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            transition: border-color 0.2s, background 0.3s;
        }

        .stat-card:hover { border-color: rgba(163,177,75,0.25); }

        .stat-card-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--txt-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .stat-card-value {
            font-family: 'Manrope', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--txt-primary);
            line-height: 1.1;
        }

        .stat-card-sub {
            font-size: 0.75rem;
            color: var(--txt-muted);
            margin-top: 4px;
        }

        .stat-card-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--accent-dim);
            display: flex; align-items: center; justify-content: center;
            color: var(--accent);
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════════
           TABLE
        ══════════════════════════════════════════ */
        .table-matchgo {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .table-matchgo thead th {
            padding: 10px 16px;
            text-align: left;
            font-size: 0.725rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--txt-faint);
            border-bottom: 1px solid var(--border-subtle);
            background: var(--surface-3);
            transition: color 0.3s, border-color 0.3s, background 0.3s;
        }

        .table-matchgo thead th:first-child { border-radius: 10px 0 0 0; }
        .table-matchgo thead th:last-child  { border-radius: 0 10px 0 0; }

        .table-matchgo tbody tr {
            border-bottom: 1px solid var(--border-subtle);
            transition: background 0.12s;
        }

        .table-matchgo tbody tr:hover { background: var(--accent-dim); }
        .table-matchgo tbody tr:last-child { border-bottom: none; }

        .table-matchgo td {
            padding: 12px 16px;
            color: var(--txt-secondary);
            vertical-align: middle;
            transition: color 0.3s;
        }

        /* ══════════════════════════════════════════
           FORMS
        ══════════════════════════════════════════ */
        .form-group-mg { margin-bottom: 1.25rem; }

        .form-label-mg {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--txt-secondary);
            margin-bottom: 6px;
            transition: color 0.3s;
        }

        .form-control-mg {
            width: 100%;
            background: var(--surface-3);
            border: 1px solid var(--border-medium);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: var(--txt-primary);
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.3s, color 0.3s;
        }

        .form-control-mg::placeholder { color: var(--txt-faint); }

        .form-control-mg:focus {
            border-color: var(--accent);
            background: var(--surface-2);
            box-shadow: 0 0 0 3px var(--accent-dim);
        }

        .form-control-mg:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        select.form-control-mg {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2378716C' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
        }

        textarea.form-control-mg { resize: vertical; min-height: 100px; }

        /* ══════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════ */
        .mg-footer {
            text-align: center;
            padding: 24px 40px;
            border-top: 1px solid var(--border-subtle);
            color: var(--txt-faint);
            font-size: 0.775rem;
            transition: border-color 0.3s, color 0.3s;
        }

        .mg-footer a {
            color: var(--accent);
            text-decoration: none;
        }

        .mg-footer a:hover { text-decoration: underline; }

        /* ══════════════════════════════════════════
           UTILITIES
        ══════════════════════════════════════════ */
        .row { display: flex; flex-wrap: wrap; margin: 0 -12px; }
        .row.g-3 { margin: -8px; }
        .row.g-3 > * { padding: 8px; }
        .row.g-4 { margin: -12px; }
        .row.g-4 > * { padding: 12px; }
        .col-lg-3 { width: 25%; padding: 0 12px; }
        .col-lg-4 { width: 33.333%; padding: 0 12px; }
        .col-lg-6 { width: 50%; padding: 0 12px; }
        .col-lg-8 { width: 66.666%; padding: 0 12px; }
        .col-lg-9 { width: 75%; padding: 0 12px; }
        .col-6 { width: 50%; padding: 0 12px; }
        .col-md-6 { width: 50%; padding: 0 12px; }
        .col-12 { width: 100%; padding: 0 12px; }
        .h-100 { height: 100%; }
        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .align-items-start { align-items: flex-start; }
        .justify-content-between { justify-content: space-between; }
        .justify-content-end { justify-content: flex-end; }
        .justify-content-center { justify-content: center; }
        .flex-wrap { flex-wrap: wrap; }
        .flex-column { flex-direction: column; }
        .flex-1 { flex: 1; }
        .gap-1 { gap: 4px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-5 { margin-bottom: 24px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .ms-auto { margin-left: auto; }
        .me-1 { margin-right: 4px; }
        .me-2 { margin-right: 8px; }
        .p-0 { padding: 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-accent { color: var(--accent) !important; }
        .text-muted-mg { color: var(--txt-muted); }
        .text-faint { color: var(--txt-faint); }
        .text-primary-mg { color: var(--txt-primary); }
        .text-danger-mg { color: #f87171; }
        .text-success-mg { color: var(--accent-light); }
        .font-bold { font-weight: 700; }
        .font-semi { font-weight: 600; }
        .font-display { font-family: 'Manrope', sans-serif; }
        .w-100 { width: 100%; }
        .overflow-hidden { overflow: hidden; }
        .position-relative { position: relative; }
        .border-bottom-subtle { border-bottom: 1px solid var(--border-subtle); }

        /* ── Divider ── */
        .mg-divider {
            height: 1px;
            background: var(--border-subtle);
            margin: 1.5rem 0;
        }

        /* ── Separator text ── */
        .mg-section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--txt-faint);
            margin-bottom: 12px;
        }

        /* ── Spinner ── */
        .mg-spinner {
            width: 20px; height: 20px;
            border: 2px solid var(--border-medium);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: mg-spin 0.7s linear infinite;
        }

        @keyframes mg-spin { to { transform: rotate(360deg); } }

        /* ── Empty state ── */
        .mg-empty {
            text-align: center;
            padding: 3rem 1rem;
        }

        .mg-empty-icon {
            font-size: 2.5rem;
            color: var(--txt-faint);
            margin-bottom: 12px;
        }

        .mg-empty h4 {
            font-family: 'Manrope', sans-serif;
            font-size: 1rem;
            color: var(--txt-secondary);
            margin-bottom: 6px;
        }

        .mg-empty p {
            font-size: 0.85rem;
            color: var(--txt-muted);
        }

        /* ══════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════ */
        @media (max-width: 1199px) {
            .mg-sidebar { transform: translateX(-100%); }
            .mg-sidebar.open { transform: translateX(0); }
            .mg-topbar { left: 0; }
            .mg-main { margin-left: 0; }
            .mg-content { padding: 24px 20px 60px; }
            .col-lg-3, .col-lg-4, .col-lg-6,
            .col-lg-8, .col-lg-9 { width: 100%; }
        }

        @media (max-width: 767px) {
            .col-md-6, .col-6 { width: 100%; }
            .mg-topbar { padding: 0 16px; }
            .mg-search { width: 140px; }
            .hide-mobile { display: none; }
        }

        @media (max-width: 576px) {
            .col-6 { width: 100%; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- app.blade.php --}}
    @php
        $unreadCount = 0;
        if (auth()->check()) {
            $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('user_id', auth()->id())
                ->whereIn('status', ['unread', 'sent'])
                ->count();
        }
    @endphp
    @include('user.layouts.sidebar')

    {{-- ============ SIDEBAR ============ --}}
    @include('user.layouts.sidebar')

    {{-- ============ TOPBAR ============ --}}
    {{-- Render topbar inline or via include --}}


    @include('user.layouts.navbar')

    {{-- ============ MAIN ============ --}}
    <main class="mg-main">
        <div class="mg-content">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert-matchgo-success">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-matchgo-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert-matchgo-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert-matchgo-info">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-matchgo-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Terdapat beberapa kesalahan:</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')

        </div>

        {{-- Footer --}}
        <footer class="mg-footer">
            &copy; {{ date('Y') }} <a href="#">MATCHGO</a>. All rights reserved. &mdash; Futsal Matchmaking Platform
        </footer>
    </main>

    <script>
        /* ── Theme system ── */
        (function () {
            const html      = document.documentElement;
            const toggleBtn = document.getElementById('themeToggle');
            const STORAGE   = 'matchgo-theme';

            // Apply saved or system preference
            const saved = localStorage.getItem(STORAGE);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initial = saved || (prefersDark ? 'dark' : 'light');
            html.setAttribute('data-theme', initial);

            // Toggle on click
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const current = html.getAttribute('data-theme');
                    const next    = current === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-theme', next);
                    localStorage.setItem(STORAGE, next);
                });
            }
        })();

        /* ── Topbar dropdown ── */
        const topbarUser = document.getElementById('topbarUserBtn');
        const topbarDrop = document.getElementById('topbarDropdown');
        if (topbarUser && topbarDrop) {
            topbarUser.addEventListener('click', function (e) {
                e.stopPropagation();
                topbarDrop.classList.toggle('show');
            });
            document.addEventListener('click', function () {
                topbarDrop.classList.remove('show');
            });
        }

        /* ── Mobile sidebar toggle ── */
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar       = document.getElementById('mgSidebar');

        function checkMobile() {
            if (sidebarToggle) {
                sidebarToggle.style.display = window.innerWidth < 1200 ? 'flex' : 'none';
            }
        }

        checkMobile();
        window.addEventListener('resize', checkMobile);

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('open');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (e) {
                if (
                    window.innerWidth < 1200 &&
                    sidebar.classList.contains('open') &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)
                ) {
                    sidebar.classList.remove('open');
                }
            });
        }
    </script>

    <script>
    /* ═══════════════════════════════════════════════
    REALTIME NOTIFICATION POLLING
    Interval: setiap 15 detik
    ═══════════════════════════════════════════════ */
    @auth
    (function () {
        const POLL_URL      = '{{ route("notifications.poll") }}';
        const POLL_INTERVAL = 15000; // 15 detik
        const CSRF          = '{{ csrf_token() }}';

        let lastUnreadCount = {{ $unreadCount ?? 0 }};

        function updateSidebarBadge(count) {
            const badge = document.getElementById('notif-sidebar-badge');
            const dot   = document.getElementById('notif-topbar-dot');

            if (count > 0) {
                if (badge) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'inline-flex';
                }
                if (dot) dot.style.display = 'block';
            } else {
                if (badge) badge.style.display = 'none';
                if (dot)   dot.style.display   = 'none';
            }
        }

        function renderNotifList(notifications, unreadCount) {
            const list      = document.getElementById('notif-list-wrapper');
            const emptyEl   = document.getElementById('notif-empty-state');
            const countEl   = document.getElementById('notif-section-num');
            const subEl     = document.getElementById('notif-section-sub');
            const markAllEl = document.getElementById('notif-mark-all-form');

            // Update section num & sub
            if (countEl) countEl.textContent = notifications.length > 0 ? notifications.length : '—';
            if (subEl) {
                subEl.textContent = unreadCount > 0
                    ? `${unreadCount} notifikasi belum dibaca.`
                    : 'Semua notifikasi sudah dibaca.';
            }

            // Tampil/sembunyikan tombol mark all
            if (markAllEl) {
                markAllEl.style.display = unreadCount > 0 ? 'block' : 'none';
            }

            if (!list) return;

            if (notifications.length === 0) {
                list.innerHTML = '';
                if (emptyEl) emptyEl.style.display = 'block';
                return;
            }

            if (emptyEl) emptyEl.style.display = 'none';

            list.innerHTML = notifications.map(function (n) {
                const iconClass = {
                    'match_confirmed': 'type-match',
                    'match_challenge': 'type-challenge',
                    'match_reminder':  'type-reminder',
                    'match_result':    'type-result',
                }[n.type] || 'type-system';

                const iconEl = {
                    'match_confirmed': '<i class="bi bi-trophy-fill"></i>',
                    'match_challenge': '<i class="bi bi-send-fill"></i>',
                    'match_reminder':  '<i class="bi bi-calendar-event-fill"></i>',
                    'match_result':    '<i class="bi bi-check-circle-fill"></i>',
                }[n.type] || '<i class="bi bi-bell-fill"></i>';

                // Tombol terima/tolak untuk challenge
                let actionBtns = '';
                if (
                    n.type === 'match_challenge' &&
                    n.is_unread &&
                    n.data &&
                    n.data.match_request_id
                ) {
                    actionBtns = `
                        <div class="mg-notif-actions">
                            <form action="/matchmaking/accept/${n.data.match_request_id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${CSRF}">
                                <button type="submit" class="btn-notif-accept">
                                    <i class="bi bi-check-lg"></i> Terima
                                </button>
                            </form>
                            <form action="/matchmaking/reject/${n.data.match_request_id}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${CSRF}">
                                <button type="submit" class="btn-notif-reject">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </form>
                        </div>
                    `;
                }

                return `
                    <div class="mg-notif-item ${n.is_unread ? 'unread' : 'read'}" data-id="${n.id}">
                        <div class="mg-notif-icon ${iconClass}">${iconEl}</div>
                        <div class="mg-notif-body">
                            <p class="mg-notif-body-title">${n.title}</p>
                            <p class="mg-notif-body-desc">${n.message}</p>
                            ${actionBtns}
                            <p class="mg-notif-time">
                                <i class="bi bi-clock me-1"></i>${n.time}
                            </p>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function poll() {
            fetch(POLL_URL, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                const newCount = data.unread_count;

                // Update badge sidebar & topbar dot
                updateSidebarBadge(newCount);

                // Re-render list jika ada perubahan jumlah unread
                if (newCount !== lastUnreadCount) {
                    lastUnreadCount = newCount;

                    // Play sound jika ada notif baru masuk
                    if (newCount > lastUnreadCount) {
                        playNotifSound();
                    }
                }

                // Selalu render ulang list jika berada di halaman notifikasi
                if (window.location.pathname.includes('/notifications')) {
                    renderNotifList(data.notifications, newCount);
                }
            })
            .catch(function (err) {
                console.warn('Polling error:', err);
            });
        }

        function playNotifSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.3);
            } catch (e) {}
        }

        // Jalankan polling pertama kali dan set interval
        poll();
        setInterval(poll, POLL_INTERVAL);
    })();
    @endauth
    </script>

    @stack('scripts')

    @stack('scripts')
</body>
</html>