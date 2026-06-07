<!DOCTYPE html>
@php
  $isAdminLogin = $isAdminLogin ?? false;
@endphp
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — MATCHGO</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/landing_page/style.css') }}" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo/logo.png') }}">

<style>

    /* ══════════════════════════════════════════
      DARK MODE VARIABLES
    ══════════════════════════════════════════ */

    :root,
    [data-theme="dark"] {

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

      --shadow-sm: 0 1px 3px rgba(0,0,0,0.5);
      --shadow-md: 0 4px 16px rgba(0,0,0,0.6);

      --btn-primary-txt: #0C0C0C;

      --alert-success-bg: rgba(163,177,75,0.08);
      --alert-success-bdr: rgba(163,177,75,0.20);
      --alert-success-txt: #d4e170;

      --alert-danger-bg: rgba(239,68,68,0.08);
      --alert-danger-bdr: rgba(239,68,68,0.18);
      --alert-danger-txt: #fca5a5;

      --sidebar-width: 260px;
      --topbar-h: 64px;
    }


    /* ══════════════════════════════════════════
      LIGHT MODE VARIABLES
    ══════════════════════════════════════════ */

    [data-theme="light"] {

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

      --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
      --shadow-md: 0 4px 16px rgba(0,0,0,0.10);

      --btn-primary-txt: #FFFFFF;

      --alert-success-bg: rgba(122,140,46,0.08);
      --alert-success-bdr: rgba(122,140,46,0.22);
      --alert-success-txt: #4D6010;

      --alert-danger-bg: rgba(220,38,38,0.07);
      --alert-danger-bdr: rgba(220,38,38,0.18);
      --alert-danger-txt: #991b1b;
    }


    /* ══════════════════════════════════════════
      GLOBAL
    ══════════════════════════════════════════ */

    body {
      background: var(--surface-0);
      color: var(--txt-primary);
    }

    .login-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      position: relative;
    }


    /* ══════════════════════════════════════════
      CARD
    ══════════════════════════════════════════ */

    .login-card {
      width: 100%;
      max-width: 440px;

      background: var(--surface-2);

      border: 1px solid var(--border-subtle);

      border-radius: 24px;

      padding: 2.5rem 2rem;

      backdrop-filter: blur(16px);

      position: relative;
      z-index: 2;

      box-shadow: var(--shadow-md);
    }


    /* ══════════════════════════════════════════
      TEXT
    ══════════════════════════════════════════ */

    .login-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--txt-primary);
      margin-bottom: 0.25rem;
    }

    .login-subtitle {
      font-size: 0.92rem;
      color: var(--txt-muted);
    }

    .form-label-custom {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--txt-secondary);
      margin-bottom: 0.4rem;
    }


    /* ══════════════════════════════════════════
      INPUT
    ══════════════════════════════════════════ */

    .form-control-custom {
      width: 100%;

      background: var(--surface-3);

      border: 1px solid var(--border-medium);

      border-radius: 12px;

      color: var(--txt-primary);

      padding: 0.7rem 1rem;

      font-size: 0.92rem;

      transition: all 0.2s ease;
    }

    .form-control-custom:focus {
      outline: none;

      border-color: var(--accent);

      background: var(--surface-4);

      box-shadow: 0 0 0 4px var(--accent-dim);

      color: var(--txt-primary);
    }

    .form-control-custom::placeholder {
      color: var(--txt-muted);
    }


    /* ══════════════════════════════════════════
      INPUT ICON
    ══════════════════════════════════════════ */

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;

      left: 0.95rem;
      top: 50%;

      transform: translateY(-50%);

      color: var(--txt-muted);

      font-size: 1rem;

      pointer-events: none;
    }

    .input-wrapper .form-control-custom {
      padding-left: 2.8rem;
    }


    /* ══════════════════════════════════════════
      TOGGLE PASSWORD
    ══════════════════════════════════════════ */

    .input-wrapper .toggle-password {
      position: absolute;

      right: 0.9rem;
      top: 50%;

      transform: translateY(-50%);

      background: none;
      border: none;

      color: var(--txt-muted);

      cursor: pointer;

      transition: color 0.2s;
    }

    .input-wrapper .toggle-password:hover {
      color: var(--accent);
    }


    /* ══════════════════════════════════════════
      BUTTON
    ══════════════════════════════════════════ */

    .btn-lime-login {
      width: 100%;

      background: var(--accent);

      color: var(--btn-primary-txt);

      border: none;

      border-radius: 12px;

      font-weight: 700;

      font-size: 0.95rem;

      padding: 0.8rem 1.5rem;

      transition: all 0.2s ease;
    }

    .btn-lime-login:hover {
      background: var(--accent-hover);

      transform: translateY(-1px);
    }

    .btn-lime-login:active {
      transform: translateY(0);
    }


    /* ══════════════════════════════════════════
      ALERT
    ══════════════════════════════════════════ */

    .alert-custom {

      background: var(--alert-danger-bg);

      border: 1px solid var(--alert-danger-bdr);

      border-radius: 12px;

      color: var(--alert-danger-txt);

      font-size: 0.85rem;

      padding: 0.75rem 1rem;
    }


    /* ══════════════════════════════════════════
      DIVIDER
    ══════════════════════════════════════════ */

    .divider-text {
      text-align: center;
      position: relative;
      margin: 1.5rem 0;
    }

    .divider-text::before {
      content: '';

      position: absolute;

      top: 50%;
      left: 0;
      right: 0;

      height: 1px;

      background: var(--border-subtle);
    }

    .divider-text span {
      position: relative;

      background: var(--surface-2);

      padding: 0 0.8rem;

      color: var(--txt-muted);

      font-size: 0.8rem;
    }


    /* ══════════════════════════════════════════
      ADMIN LINK
    ══════════════════════════════════════════ */

    .admin-link-box {

      background: var(--accent-dim);

      border: 1px solid var(--border-subtle);

      border-radius: 14px;

      padding: 0.9rem 1rem;

      display: flex;
      align-items: center;
      gap: 0.8rem;

      text-decoration: none;

      transition: all 0.2s ease;
    }

    .admin-link-box:hover {

      background: var(--accent-dim-hover);

      border-color: var(--accent);
    }

    .admin-link-icon {

      width: 40px;
      height: 40px;

      border-radius: 12px;

      background: rgba(163,177,75,0.18);

      display: flex;
      align-items: center;
      justify-content: center;

      color: var(--accent);

      font-size: 1rem;
    }

    .admin-link-text small {
      display: block;

      font-size: 0.72rem;

      color: var(--txt-muted);
    }

    .admin-link-text span {

      font-size: 0.88rem;

      font-weight: 600;

      color: var(--accent);
    }


    /* ══════════════════════════════════════════
      LINKS
    ══════════════════════════════════════════ */

    .forgot-link,
    .register-text a,
    .back-home {
      color: var(--accent);
      text-decoration: none;
      transition: opacity 0.2s;
    }

    .forgot-link:hover,
    .register-text a:hover,
    .back-home:hover {
      opacity: 0.75;
    }

    .register-text,
    .remember-label {
      color: var(--txt-muted);
    }


    /* ══════════════════════════════════════════
      CHECKBOX
    ══════════════════════════════════════════ */

    .remember-check {
      accent-color: var(--accent);

      width: 15px;
      height: 15px;
    }

  </style>
</head>
<body>

  <div class="bg-grid"></div>
  <div class="bg-radial-top"></div>

  {{-- Navbar minimal --}}
  <nav class="navbar navbar-matchgo fixed-top" style="padding: 0.75rem 0;">
    <div class="container">
      <a class="navbar-brand-custom" href="{{ url('/') }}">
        <img src="{{ asset('img/logo/logo.png') }}" alt="MatchGo Logo" style="width:10%;height:10%;object-fit:contain;">
        {{-- <span class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
        MATCH<span class="brand-accent">GO</span> --}}
      </a>
      <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
        <i class="bi bi-moon-fill icon-moon"></i>
        <i class="bi bi-sun-fill icon-sun"></i>
      </button>
    </div>
  </nav>

  <div class="login-page" style="padding-top: 5rem;">
    <div style="width: 100%; max-width: 440px; position: relative; z-index: 2;">

      {{-- Back to home --}}
      <a href="{{ $isAdminLogin ? route('login') : url('/') }}" class="back-home">
        <i class="bi bi-arrow-left"></i> Kembali ke {{ $isAdminLogin ? 'Login Player' : 'Beranda' }}
      </a>

      <div class="login-card">

        {{-- Header --}}
        <div class="mb-4">
          <h1 class="login-title">{{ $isAdminLogin ? 'Admin MATCHGO' : 'Selamat Datang Kembali' }}</h1>
          <p class="login-subtitle">
            {{ $isAdminLogin ? 'Masuk sebagai Super Admin, Admin Lapangan, atau Auditor' : 'Masuk untuk melanjutkan ke MATCHGO' }}
          </p>
        </div>

        {{-- Error global --}}
        @if ($errors->any())
          <div class="alert-custom mb-3">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
          </div>
        @endif

        {{-- Session status (e.g. setelah logout) --}}
        @if (session('status'))
          <div class="mb-3" style="background: rgba(163,230,53,0.08); border: 1px solid rgba(163,230,53,0.2); border-radius: 10px; padding: 0.65rem 1rem; font-size: 0.85rem; color: var(--lime, #a3e635);">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('status') }}
          </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ $isAdminLogin ? route('admin.login.submit') : route('login') }}" id="loginForm">
          @csrf

          {{-- Email --}}
          <div class="mb-3">
            <label for="email" class="form-label-custom">Email</label>
            <div class="input-wrapper">
              <i class="bi bi-envelope input-icon"></i>
              <input
                type="email"
                name="email"
                id="email"
                class="form-control-custom"
                placeholder="nama@email.com"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
              >
            </div>
          </div>

          {{-- Password --}}
          <div class="mb-3">
            <label for="password" class="form-label-custom">Password</label>
            <div class="input-wrapper">
              <i class="bi bi-lock input-icon"></i>
              <input
                type="password"
                name="password"
                id="password"
                class="form-control-custom"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
              >
              <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                <i class="bi bi-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>

          {{-- Remember & Forgot --}}
          <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-2">
              <input type="checkbox" name="remember" id="remember" class="remember-check" {{ old('remember') ? 'checked' : '' }}>
              <label for="remember" class="remember-label">Ingat Saya</label>
            </div>
            <a href="#" class="forgot-link">Lupa Password?</a>
          </div>

          {{-- Submit --}}
          <button type="submit" class="btn-lime-login" id="submitBtn">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
          </button>
        </form>

        @if (! $isAdminLogin)
          {{-- Divider --}}
          <div class="divider-text">
            <span>atau</span>
          </div>

          {{-- Admin link --}}
          <a href="{{ url('/admin/login') }}" class="admin-link-box">
            <div class="admin-link-icon">
              <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="admin-link-text">
              <small>Login sebagai</small>
              <span>Admin MATCHGO <i class="bi bi-arrow-right ms-1" style="font-size: 0.75rem;"></i></span>
            </div>
          </a>

          {{-- Register --}}
          <p class="register-text">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Gratis</a>
          </p>
        @else
          <p class="register-text">
            Login sebagai pemain? <a href="{{ route('login') }}">Masuk Player</a>
          </p>
        @endif

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/landing_page/main.js') }}"></script>
  <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput  = document.getElementById('password');
    const toggleIcon     = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', function () {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
    });
  </script>
</body>
</html>
