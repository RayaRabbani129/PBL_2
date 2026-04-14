<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — MATCHGO</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/landing_page/style.css') }}" rel="stylesheet">

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

    .login-brand {
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: var(--text-primary, #fff);
      text-decoration: none;
    }

    .login-brand:hover { text-decoration: none; color: inherit; }

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

    .btn-lime-login:active {
      transform: translateY(0);
    }

    .btn-lime-login:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    .divider-text {
      text-align: center;
      position: relative;
      margin: 1.25rem 0;
    }

    .divider-text::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: var(--input-border, rgba(255,255,255,0.08));
    }

    [data-theme="light"] .divider-text::before {
      background: #e2e8f0;
    }

    .divider-text span {
      position: relative;
      background: var(--card-bg-solid, #0f1623);
      padding: 0 0.75rem;
      font-size: 0.8rem;
      color: var(--text-muted, #64748b);
    }

    [data-theme="light"] .divider-text span {
      background: #fff;
    }

    .admin-link-box {
      background: var(--admin-bg, rgba(163, 230, 53, 0.05));
      border: 1px solid var(--admin-border, rgba(163, 230, 53, 0.15));
      border-radius: 10px;
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      transition: border-color 0.2s, background 0.2s;
    }

    .admin-link-box:hover {
      border-color: rgba(163, 230, 53, 0.4);
      background: rgba(163, 230, 53, 0.08);
    }

    .admin-link-icon {
      width: 36px;
      height: 36px;
      background: rgba(163, 230, 53, 0.12);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--lime, #a3e635);
      font-size: 1rem;
      flex-shrink: 0;
    }

    .admin-link-text small {
      display: block;
      font-size: 0.72rem;
      color: var(--text-muted, #64748b);
    }

    .admin-link-text span {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--lime, #a3e635);
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

    .remember-check {
      accent-color: var(--lime, #a3e635);
      width: 15px;
      height: 15px;
    }

    .remember-label {
      font-size: 0.85rem;
      color: var(--text-muted, #94a3b8);
      cursor: pointer;
    }

    .forgot-link {
      font-size: 0.85rem;
      color: var(--lime, #a3e635);
      text-decoration: none;
      transition: opacity 0.2s;
    }

    .forgot-link:hover {
      opacity: 0.75;
      text-decoration: underline;
    }

    .register-text {
      text-align: center;
      font-size: 0.875rem;
      color: var(--text-muted, #94a3b8);
      margin-top: 1.25rem;
      margin-bottom: 0;
    }

    .register-text a {
      color: var(--lime, #a3e635);
      font-weight: 600;
      text-decoration: none;
    }

    .register-text a:hover {
      text-decoration: underline;
    }

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

    .back-home:hover {
      color: var(--lime, #a3e635);
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
        <span class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
        MATCH<span class="brand-accent">GO</span>
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
      <a href="{{ url('/') }}" class="back-home">
        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
      </a>

      <div class="login-card">

        {{-- Header --}}
        <div class="mb-4">
          <h1 class="login-title">Selamat Datang Kembali</h1>
          <p class="login-subtitle">Masuk untuk melanjutkan ke MATCHGO</p>
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
        <form method="POST" action="{{ route('login') }}" id="loginForm">
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

        {{-- Divider --}}
        <div class="divider-text">
          <span>atau</span>
        </div>

        {{-- Admin link --}}
        {{-- <a href="{{ url('/admin/login') }}" class="admin-link-box">
          <div class="admin-link-icon">
            <i class="bi bi-shield-lock-fill"></i>
          </div>
          <div class="admin-link-text">
            <small>Login sebagai</small>
            <span>Admin Panel <i class="bi bi-arrow-right ms-1" style="font-size: 0.75rem;"></i></span>
          </div>
        </a> --}}

        {{-- Register --}}
        <p class="register-text">
          Belum punya akun? <a href="#">Daftar Gratis</a>
        </p>

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