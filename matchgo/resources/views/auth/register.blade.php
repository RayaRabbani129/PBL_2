<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — MATCHGO</title>

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

      --txt-primary: #F5F5F0;
      --txt-secondary: #A8A29E;
      --txt-muted: #78716C;

      --border-subtle: rgba(255,255,255,0.06);
      --border-medium: rgba(255,255,255,0.10);

      --shadow-sm: 0 1px 3px rgba(0,0,0,0.5);
      --shadow-md: 0 4px 16px rgba(0,0,0,0.6);

      --btn-primary-txt: #0C0C0C;

      --alert-danger-bg: rgba(239,68,68,0.08);
      --alert-danger-bdr: rgba(239,68,68,0.18);
      --alert-danger-txt: #fca5a5;
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

      --txt-primary: #1A1A17;
      --txt-secondary: #4A4A42;
      --txt-muted: #6E6E64;

      --border-subtle: rgba(0,0,0,0.07);
      --border-medium: rgba(0,0,0,0.11);

      --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
      --shadow-md: 0 4px 16px rgba(0,0,0,0.10);

      --btn-primary-txt: #FFFFFF;

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

      padding: 0.72rem 1rem;

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

    .form-control-custom.is-invalid {
      border-color: rgba(239,68,68,0.45) !important;

      box-shadow: 0 0 0 4px rgba(239,68,68,0.08) !important;
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
      FIELD ERROR
    ══════════════════════════════════════════ */

    .field-error {

      font-size: 0.78rem;

      color: var(--alert-danger-txt);

      margin-top: 0.4rem;

      display: flex;
      align-items: center;
      gap: 0.35rem;
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

    .btn-lime-login:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }


    /* ══════════════════════════════════════════
      PASSWORD STRENGTH
    ══════════════════════════════════════════ */

    .password-strength {
      margin-top: 0.6rem;
    }

    .strength-bar {

      height: 4px;

      border-radius: 999px;

      background: var(--surface-4);

      overflow: hidden;

      margin-bottom: 0.35rem;
    }

    .strength-fill {
      height: 100%;
      border-radius: 999px;
      transition: all 0.3s ease;
      width: 0%;
    }

    .strength-label {
      font-size: 0.76rem;
      color: var(--txt-muted);
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
      LINKS
    ══════════════════════════════════════════ */

    .login-text,
    .terms-text {
      text-align: center;
      color: var(--txt-muted);
    }

    .login-text {
      font-size: 0.875rem;
      margin-top: 1.3rem;
    }

    .terms-text {
      font-size: 0.78rem;
      margin-top: 1rem;
    }

    .login-text a,
    .terms-text a,
    .back-home {

      color: var(--accent);

      text-decoration: none;

      transition: opacity 0.2s;
    }

    .login-text a:hover,
    .terms-text a:hover,
    .back-home:hover {
      opacity: 0.75;
    }


    /* ══════════════════════════════════════════
      BACK BUTTON
    ══════════════════════════════════════════ */

    .back-home {

      font-size: 0.82rem;

      display: inline-flex;
      align-items: center;
      gap: 0.35rem;

      margin-bottom: 1.5rem;
    }

  </style>
</head>
<body>

  <div class="bg-grid"></div>
  <div class="bg-radial-top"></div>

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

      <a href="{{ url('/') }}" class="back-home">
        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
      </a>

      <div class="login-card">

        <div class="mb-4">
          <h1 class="login-title">Buat Akun Baru</h1>
          <p class="login-subtitle">Bergabung dan mulai bermain bersama MATCHGO</p>
        </div>

        @if ($errors->any())
          <div class="alert-custom mb-3">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
          @csrf

          {{-- Nama --}}
          <div class="mb-3">
            <label for="name" class="form-label-custom">Nama Lengkap</label>
            <div class="input-wrapper">
              <i class="bi bi-person input-icon"></i>
              <input
                type="text"
                name="name"
                id="name"
                class="form-control-custom @error('name') is-invalid @enderror"
                placeholder="Nama kamu"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
              >
            </div>
            @error('name')
              <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          {{-- Email --}}
          <div class="mb-3">
            <label for="email" class="form-label-custom">Email</label>
            <div class="input-wrapper">
              <i class="bi bi-envelope input-icon"></i>
              <input
                type="email"
                name="email"
                id="email"
                class="form-control-custom @error('email') is-invalid @enderror"
                placeholder="nama@email.com"
                value="{{ old('email') }}"
                required
                autocomplete="email"
              >
            </div>
            @error('email')
              <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
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
                class="form-control-custom @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter"
                required
                autocomplete="new-password"
              >
              <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                <i class="bi bi-eye" id="toggleIcon"></i>
              </button>
            </div>
            <div class="password-strength" id="strengthWrapper" style="display:none;">
              <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
              <span class="strength-label" id="strengthLabel"></span>
            </div>
            @error('password')
              <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          {{-- Konfirmasi Password --}}
          <div class="mb-4">
            <label for="password_confirmation" class="form-label-custom">Konfirmasi Password</label>
            <div class="input-wrapper">
              <i class="bi bi-lock-fill input-icon"></i>
              <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="form-control-custom"
                placeholder="Ulangi password kamu"
                required
                autocomplete="new-password"
              >
              <button type="button" class="toggle-password" id="toggleConfirm" aria-label="Tampilkan konfirmasi">
                <i class="bi bi-eye" id="toggleIconConfirm"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn-lime-login" id="submitBtn">
            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
          </button>
        </form>

        <p class="terms-text mt-3">
          Dengan mendaftar, kamu menyetujui <a href="#">Syarat & Ketentuan</a> kami.
        </p>

        <p class="login-text">
          Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/landing_page/main.js') }}"></script>
  <script>
    // Toggle password
    document.getElementById('togglePassword').addEventListener('click', function () {
      const input = document.getElementById('password');
      const icon  = document.getElementById('toggleIcon');
      const show  = input.type === 'password';
      input.type  = show ? 'text' : 'password';
      icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    // Toggle konfirmasi
    document.getElementById('toggleConfirm').addEventListener('click', function () {
      const input = document.getElementById('password_confirmation');
      const icon  = document.getElementById('toggleIconConfirm');
      const show  = input.type === 'password';
      input.type  = show ? 'text' : 'password';
      icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    // Password strength
    const passwordInput   = document.getElementById('password');
    const strengthWrapper = document.getElementById('strengthWrapper');
    const strengthFill    = document.getElementById('strengthFill');
    const strengthLabel   = document.getElementById('strengthLabel');

    passwordInput.addEventListener('input', function () {
      const val = this.value;
      if (!val) { strengthWrapper.style.display = 'none'; return; }
      strengthWrapper.style.display = 'block';

      let score = 0;
      if (val.length >= 8)          score++;
      if (/[A-Z]/.test(val))        score++;
      if (/[0-9]/.test(val))        score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;

      const levels = [
        { label: 'Sangat Lemah', color: '#ef4444', width: '15%' },
        { label: 'Lemah',        color: '#f97316', width: '35%' },
        { label: 'Cukup',        color: '#eab308', width: '60%' },
        { label: 'Kuat',         color: '#84cc16', width: '80%' },
        { label: 'Sangat Kuat',  color: '#a3e635', width: '100%' },
      ];

      const lvl = levels[score] ?? levels[0];
      strengthFill.style.width      = lvl.width;
      strengthFill.style.background = lvl.color;
      strengthLabel.textContent     = lvl.label;
      strengthLabel.style.color     = lvl.color;
    });

    // Loading state
    document.getElementById('registerForm').addEventListener('submit', function () {
      const btn = document.getElementById('submitBtn');
      btn.disabled  = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
    });
  </script>
</body>
</html>