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
    .login-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      position: relative;
    }
    .login-card {
      width: 100%;
      max-width: 440px;
      background: var(--card-bg, rgba(255,255,255,0.04));
      border: 1px solid var(--card-border, rgba(255,255,255,0.08));
      border-radius: 20px;
      padding: 2.5rem 2rem;
      backdrop-filter: blur(16px);
      position: relative;
      z-index: 2;
    }
    [data-theme="light"] .login-card {
      background: rgba(255,255,255,0.92);
      border-color: rgba(0,0,0,0.08);
      box-shadow: 0 8px 40px rgba(0,0,0,0.08);
    }
    .login-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary, #f1f5f9);
      margin-bottom: 0.25rem;
    }
    .login-subtitle {
      font-size: 0.9rem;
      color: var(--text-muted, #94a3b8);
      margin-bottom: 0;
    }
    .form-label-custom {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-secondary, #cbd5e1);
      margin-bottom: 0.4rem;
    }
    .form-control-custom {
      background: var(--input-bg, rgba(255,255,255,0.06));
      border: 1px solid var(--input-border, rgba(255,255,255,0.1));
      border-radius: 10px;
      color: var(--text-primary, #f1f5f9);
      padding: 0.65rem 1rem;
      font-size: 0.9rem;
      transition: border-color 0.2s, box-shadow 0.2s;
      width: 100%;
    }
    [data-theme="light"] .form-control-custom {
      background: #f8fafc;
      border-color: #e2e8f0;
      color: #1e293b;
    }
    .form-control-custom:focus {
      outline: none;
      border-color: var(--lime, #a3e635);
      box-shadow: 0 0 0 3px rgba(163, 230, 53, 0.15);
      background: var(--input-bg-focus, rgba(255,255,255,0.08));
      color: var(--text-primary, #f1f5f9);
    }
    [data-theme="light"] .form-control-custom:focus {
      background: #fff;
      color: #1e293b;
    }
    .form-control-custom::placeholder {
      color: var(--text-muted, #64748b);
    }
    .form-control-custom.is-invalid {
      border-color: rgba(239, 68, 68, 0.6) !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    .input-wrapper {
      position: relative;
    }
    .input-icon {
      position: absolute;
      left: 0.85rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted, #64748b);
      font-size: 1rem;
      pointer-events: none;
    }
    .input-wrapper .form-control-custom {
      padding-left: 2.6rem;
    }
    .input-wrapper .toggle-password {
      position: absolute;
      right: 0.85rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-muted, #64748b);
      cursor: pointer;
      padding: 0;
      font-size: 1rem;
      transition: color 0.2s;
    }
    .input-wrapper .toggle-password:hover {
      color: var(--lime, #a3e635);
    }
    .field-error {
      font-size: 0.78rem;
      color: #fca5a5;
      margin-top: 0.35rem;
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }
    [data-theme="light"] .field-error {
      color: #be123c;
    }
    .btn-lime-login {
      background: var(--lime, #a3e635);
      color: #0f172a;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      font-size: 0.95rem;
      padding: 0.7rem 1.5rem;
      width: 100%;
      transition: background 0.2s, transform 0.15s;
      letter-spacing: 0.01em;
    }
    .btn-lime-login:hover {
      background: #bef264;
      color: #0f172a;
      transform: translateY(-1px);
    }
    .btn-lime-login:active { transform: translateY(0); }
    .btn-lime-login:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }
    .password-strength {
      margin-top: 0.5rem;
    }
    .strength-bar {
      height: 3px;
      border-radius: 99px;
      background: rgba(255,255,255,0.08);
      overflow: hidden;
      margin-bottom: 0.3rem;
    }
    [data-theme="light"] .strength-bar {
      background: #e2e8f0;
    }
    .strength-fill {
      height: 100%;
      border-radius: 99px;
      transition: width 0.3s ease, background 0.3s ease;
      width: 0%;
    }
    .strength-label {
      font-size: 0.75rem;
      color: var(--text-muted, #64748b);
    }
    .alert-custom {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      border-radius: 10px;
      color: #fca5a5;
      font-size: 0.85rem;
      padding: 0.65rem 1rem;
    }
    [data-theme="light"] .alert-custom {
      background: #fff1f2;
      border-color: #fecdd3;
      color: #be123c;
    }
    .login-text {
      text-align: center;
      font-size: 0.875rem;
      color: var(--text-muted, #94a3b8);
      margin-top: 1.25rem;
      margin-bottom: 0;
    }
    .login-text a {
      color: var(--lime, #a3e635);
      font-weight: 600;
      text-decoration: none;
    }
    .login-text a:hover { text-decoration: underline; }
    .back-home {
      font-size: 0.82rem;
      color: var(--text-muted, #64748b);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: color 0.2s;
      margin-bottom: 1.5rem;
    }
    .back-home:hover { color: var(--lime, #a3e635); }
    .terms-text {
      font-size: 0.78rem;
      color: var(--text-muted, #64748b);
      text-align: center;
      margin-top: 1rem;
      margin-bottom: 0;
    }
    .terms-text a {
      color: var(--lime, #a3e635);
      text-decoration: none;
    }
    .terms-text a:hover { text-decoration: underline; }
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