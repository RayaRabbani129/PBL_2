<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MATCHGO - Platform matchmaking futsal terbaik.">
    <title>@yield('title', 'MATCHGO — Futsal Matchmaking Platform')</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Landing Page Style (variabel, navbar, footer, dll) -->
    <link href="{{ asset('css/landing_page/style.css') }}" rel="stylesheet">

    <style>
        /* ── Layout utama ── */
        .main-content {
            min-height: 100vh;
            padding-top: 72px; /* offset navbar fixed */
            padding-bottom: 3rem;
            position: relative;
            z-index: 1;
        }

        /* ── Alert styling sesuai tema ── */
        .alert-matchgo-danger {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            color: #fca5a5;
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .alert-matchgo-danger ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .alert-matchgo-danger li {
            margin-bottom: 0.2rem;
        }

        .alert-matchgo-danger li:last-child {
            margin-bottom: 0;
        }

        [data-theme="light"] .alert-matchgo-danger {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #be123c;
        }

        .alert-matchgo-success {
            background: rgba(163, 230, 53, 0.08);
            border: 1px solid rgba(163, 230, 53, 0.2);
            border-radius: 12px;
            color: #bef264;
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        [data-theme="light"] .alert-matchgo-success {
            background: #f7fee7;
            border-color: #d9f99d;
            color: #3f6212;
        }

        .alert-matchgo-info {
            background: rgba(34, 211, 238, 0.08);
            border: 1px solid rgba(34, 211, 238, 0.2);
            border-radius: 12px;
            color: #67e8f9;
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        [data-theme="light"] .alert-matchgo-info {
            background: #ecfeff;
            border-color: #a5f3fc;
            color: #164e63;
        }

        .alert-matchgo-warning {
            background: rgba(251, 191, 36, 0.08);
            border: 1px solid rgba(251, 191, 36, 0.2);
            border-radius: 12px;
            color: #fcd34d;
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        [data-theme="light"] .alert-matchgo-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--card-border, rgba(255,255,255,0.08));
        }

        [data-theme="light"] .page-header {
            border-color: rgba(0,0,0,0.08);
        }

        .page-header h1,
        .page-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary, #f1f5f9);
            margin-bottom: 0.25rem;
        }

        [data-theme="light"] .page-header h1,
        [data-theme="light"] .page-header h2 {
            color: #0f172a;
        }

        .page-header p {
            color: var(--text-muted, #94a3b8);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* ── Card konten ── */
        .card-matchgo {
            background: var(--card-bg, rgba(255,255,255,0.04));
            border: 1px solid var(--card-border, rgba(255,255,255,0.08));
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(12px);
        }

        [data-theme="light"] .card-matchgo {
            background: rgba(255,255,255,0.9);
            border-color: rgba(0,0,0,0.07);
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        }

        /* ── Breadcrumb ── */
        .breadcrumb-matchgo {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            color: var(--text-muted, #64748b);
            margin-bottom: 1.5rem;
            padding: 0;
            list-style: none;
        }

        .breadcrumb-matchgo a {
            color: var(--text-muted, #64748b);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-matchgo a:hover {
            color: var(--lime, #a3e635);
        }

        .breadcrumb-matchgo .active {
            color: var(--lime, #a3e635);
        }

        .breadcrumb-matchgo .separator {
            color: var(--text-muted, #475569);
            font-size: 0.7rem;
        }

        /* ── Sidebar player ── */
        .sidebar-fixed {
            position: fixed;
            top: 72px;
            left: 0;
            bottom: 0;
            width: 280px;
            z-index: 1055;
            padding: 0;
        }

        .sidebar-matchgo {
            background: #09101f;
            color: var(--text-secondary);
            border-right: 1px solid rgba(163, 230, 53, 0.14);
            border-radius: 0;
            padding: 1.6rem 1.3rem;
            min-height: calc(100vh - 72px);
            box-shadow: none;
            backdrop-filter: none;
            overflow: hidden;
        }

        [data-theme="light"] .sidebar-matchgo {
            background: #ffffff;
            color: var(--text-secondary);
            border-color: rgba(15, 23, 42, 0.08);
        }

        .content-with-sidebar {
            margin-left: 280px;
            padding-top: 1.5rem;
        }

        @media (max-width: 1199.98px) {
            .sidebar-fixed {
                position: static;
                width: auto;
                top: auto;
                left: auto;
                bottom: auto;
                padding: 0;
            }

            .sidebar-matchgo {
                border-radius: 24px;
                min-height: auto;
                height: auto;
                box-shadow: none;
                overflow: visible;
            }

            .content-with-sidebar {
                margin-left: 0;
                padding-top: 0;
            }
        }

        .sidebar-matchgo .sidebar-title {
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--lime, #a3e635);
            margin-bottom: 1rem;
        }

        .sidebar-matchgo .sidebar-description {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
            margin-bottom: 1.6rem;
        }

        .sidebar-matchgo .nav-link {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.95rem 1.15rem;
            border-radius: 16px;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.02);
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .sidebar-matchgo .nav-link:hover,
        .sidebar-matchgo .nav-link.active {
            background: rgba(163, 230, 53, 0.18);
            color: var(--text-primary);
            text-decoration: none;
            transform: translateX(1px);
        }

        [data-theme="light"] .sidebar-matchgo .nav-link:hover,
        [data-theme="light"] .sidebar-matchgo .nav-link.active {
            background: rgba(163, 230, 53, 0.16);
            color: var(--text-primary);
        }

        .sidebar-matchgo .nav-link i {
            font-size: 1.05rem;
            width: 22px;
            text-align: center;
            color: var(--lime);
        }

        .sidebar-matchgo .sidebar-cta {
            margin-top: 1.5rem;
            padding: 1rem 1rem 0;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-matchgo .sidebar-cta p {
            color: var(--text-muted, #94a3b8);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .sidebar-matchgo .sidebar-cta .btn {
            width: 100%;
            border-radius: 14px;
            font-weight: 600;
            padding: 0.85rem 1rem;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Background effects (sama dengan landing page) -->
    <div class="bg-grid"></div>
    <div class="bg-radial-top"></div>

    <!-- ============ NAVBAR ============ -->
    @include('user.layouts.navbar')

    <!-- ============ MAIN CONTENT ============ -->
    <main class="main-content">
        <div class="container-fluid px-0">
            <div class="row gy-4">
                <aside class="col-12 col-lg-3">
                    <div class="sidebar-fixed">
                        @include('user.layouts.sidebar')
                    </div>
                </aside>

                <section class="col-12 col-lg-9 content-with-sidebar px-4 px-lg-5">
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

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert-matchgo-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Terdapat beberapa kesalahan:</strong>
                            <ul class="mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Page Content --}}
                    @yield('content')
                </section>
            </div>
        </div>
    </main>

    <!-- ============ FOOTER ============ -->
    @include('user.layouts.footer')

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Pakai JS yang sama dengan landing page (sudah ada theme toggle dll) -->
    <script src="{{ asset('js/landing_page/main.js') }}"></script>

    @stack('scripts')
</body>
</html>