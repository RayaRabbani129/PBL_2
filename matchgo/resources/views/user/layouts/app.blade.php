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
            padding-top: 80px; /* offset navbar fixed */
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
        <div class="container">

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