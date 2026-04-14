<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="MATCHGO - Platform matchmaking futsal terbaik. Temukan lawan tanding, booking lapangan otomatis, dan split biaya cerdas.">
  <title>MATCHGO — Futsal Matchmaking Platform</title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="{{ asset('css/landing_page/style.css') }}" rel="stylesheet">

  <script data-design-ignore="true">
  (function() {
    if (window === window.parent || window.__DESIGN_NAV_REPORTER__) return;
    window.__DESIGN_NAV_REPORTER__ = true;
    function report() {
      try { window.parent.postMessage({ type: 'IFRAME_URL_CHANGE', payload: { url: location.origin + location.pathname + location.hash } }, '*'); } catch(e) {}
    }
    report();
    var ps = history.pushState, rs = history.replaceState;
    history.pushState = function() { ps.apply(this, arguments); report(); };
    history.replaceState = function() { rs.apply(this, arguments); report(); };
    window.addEventListener('popstate', report);
    window.addEventListener('hashchange', report);
    window.addEventListener('load', report);
  })();
  </script>
</head>
<body>

  <!-- Background effects -->
  <div class="bg-grid" data-design-id="bg-grid"></div>
  <div class="bg-radial-top" data-design-id="bg-radial-top"></div>

  <!-- ============ NAVBAR ============ -->
  <nav class="navbar navbar-expand-lg navbar-matchgo fixed-top" data-design-id="navbar">
    <div class="container" data-design-id="navbar-container">
      <a class="navbar-brand-custom" href="#" data-design-id="navbar-brand">
        <span class="brand-icon" data-design-id="navbar-brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
        MATCH<span class="brand-accent" data-design-id="navbar-brand-accent">GO</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" data-design-id="navbar-toggler">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav" data-design-id="navbar-collapse">
        <ul class="navbar-nav mx-auto" data-design-id="navbar-nav">
          <li class="nav-item" data-design-id="nav-item-features">
            <a class="nav-link" href="#features">Fitur</a>
          </li>
          <li class="nav-item" data-design-id="nav-item-preview">
            <a class="nav-link" href="#web-preview">Preview</a>
          </li>
          <li class="nav-item" data-design-id="nav-item-how">
            <a class="nav-link" href="#how-it-works">Cara Kerja</a>
          </li>
          <li class="nav-item" data-design-id="nav-item-testimonials">
            <a class="nav-link" href="#testimonials">Testimoni</a>
          </li>
        </ul>
        <div class="d-flex gap-2 align-items-center mt-3 mt-lg-0" data-design-id="navbar-actions">
          <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme" data-design-id="navbar-theme-toggle">
            <i class="bi bi-moon-fill icon-moon"></i>
            <i class="bi bi-sun-fill icon-sun"></i>
          </button>
          <a href="{{ route('login') }}" class="btn btn-outline-lime btn-sm" data-design-id="navbar-btn-login">Masuk</a>
          <a href="{{ route('register') }}" class="btn btn-lime btn-sm" data-design-id="navbar-btn-signup">Daftar Gratis</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ============ HERO ============ -->
  <section id="hero" data-design-id="hero-section">
    <!-- Floating orbs -->
    <div class="hero-orbs" data-design-id="hero-orbs">
      <div class="hero-orb hero-orb-1" data-design-id="hero-orb-1"></div>
      <div class="hero-orb hero-orb-2" data-design-id="hero-orb-2"></div>
      <div class="hero-orb hero-orb-3" data-design-id="hero-orb-3"></div>
    </div>

    <!-- Futsal field line decorations -->
    <div class="hero-field-lines" data-design-id="hero-field-lines">
      <div class="center-circle" data-design-id="hero-center-circle"></div>
      <div class="center-dot" data-design-id="hero-center-dot"></div>
      <div class="penalty-arc" data-design-id="hero-penalty-arc"></div>
    </div>

    <div class="container position-relative" style="z-index:2;" data-design-id="hero-container">
      <div class="row align-items-center" data-design-id="hero-row">
        <!-- Left content -->
        <div class="col-lg-6" data-design-id="hero-content">
          <div class="reveal" data-design-id="hero-badge-wrapper">
            <span class="hero-badge" data-design-id="hero-badge">
              <span class="pulse-dot" data-design-id="hero-pulse-dot"></span>
              Platform Futsal #1 di Indonesia
            </span>
          </div>
          <h1 class="hero-title reveal reveal-delay-1" data-design-id="hero-title">
            Temukan Lawan<br>Tanding <span class="gradient-text" data-design-id="hero-title-gradient">Impianmu</span>
          </h1>
          <p class="hero-subtitle reveal reveal-delay-2" data-design-id="hero-subtitle">
            Platform matchmaking futsal paling canggih. Cari lawan setara, booking lapangan otomatis, dan split biaya &mdash; semua dalam satu platform web.
          </p>
          <div class="d-flex gap-3 flex-wrap reveal reveal-delay-3" data-design-id="hero-cta-buttons">
            <a href="{{ route('register') }}" class="btn btn-lime btn-lg px-4" data-design-id="hero-btn-primary">
              <i class="bi bi-rocket-takeoff me-2"></i>Mulai Sekarang
            </a>
            <a href="#features" class="btn btn-outline-lime btn-lg px-4" data-design-id="hero-btn-secondary">
              <i class="bi bi-play-circle me-2"></i>Lihat Fitur
            </a>
          </div>
          <div class="hero-stats reveal reveal-delay-4" data-design-id="hero-stats">
            <div class="hero-stat-item" data-design-id="hero-stat-1">
              <h3 data-design-id="hero-stat-1-val">2,500+</h3>
              <p data-design-id="hero-stat-1-lbl">Tim Aktif</p>
            </div>
            <div class="hero-stat-item" data-design-id="hero-stat-2">
              <h3 data-design-id="hero-stat-2-val">12,000+</h3>
              <p data-design-id="hero-stat-2-lbl">Match Selesai</p>
            </div>
            <div class="hero-stat-item" data-design-id="hero-stat-3">
              <h3 data-design-id="hero-stat-3-val">350+</h3>
              <p data-design-id="hero-stat-3-lbl">Venue Partner</p>
            </div>
          </div>
        </div>

        <!-- Right: Browser mockup -->
        <div class="col-lg-6 d-flex justify-content-center" data-design-id="hero-mockup-col">
          <div class="browser-mockup reveal reveal-delay-2" data-design-id="hero-browser">
            <div class="browser-bar" data-design-id="hero-browser-bar">
              <div class="browser-dots" data-design-id="hero-browser-dots">
                <span data-design-id="hero-dot-red"></span>
                <span data-design-id="hero-dot-yellow"></span>
                <span data-design-id="hero-dot-green"></span>
              </div>
              <div class="browser-url" data-design-id="hero-browser-url">
                <i class="bi bi-lock-fill lock-icon"></i>
                matchgo.id/dashboard
              </div>
            </div>
            <div class="browser-screen" data-design-id="hero-browser-screen">
              <div class="browser-screen-header" data-design-id="hero-screen-header">
                <h5 data-design-id="hero-screen-title"><i class="bi bi-lightning-charge-fill text-lime"></i> Live Match</h5>
                <small data-design-id="hero-screen-subtitle">Matchmaking ditemukan!</small>
              </div>
              <div class="vs-display" data-design-id="hero-vs-display">
                <div class="vs-team" data-design-id="hero-team-a">
                  <div class="vs-team-avatar" style="background: var(--lime-dim);" data-design-id="hero-team-a-avatar">&#9917;</div>
                  <div class="vs-team-name" data-design-id="hero-team-a-name">Garuda FC</div>
                </div>
                <div class="vs-badge" data-design-id="hero-vs-badge">VS</div>
                <div class="vs-team" data-design-id="hero-team-b">
                  <div class="vs-team-avatar" style="background: var(--cyan-dim);" data-design-id="hero-team-b-avatar">&#9918;</div>
                  <div class="vs-team-name" data-design-id="hero-team-b-name">Phoenix FC</div>
                </div>
              </div>
              <div class="match-info-grid" data-design-id="hero-match-info">
                <div class="match-info-row" data-design-id="hero-info-venue">
                  <span class="label"><i class="bi bi-geo-alt me-1"></i>Venue</span>
                  <span class="value">SportArena Jakarta</span>
                </div>
                <div class="match-info-row" data-design-id="hero-info-time">
                  <span class="label"><i class="bi bi-clock me-1"></i>Waktu</span>
                  <span class="value">Sabtu, 20:00</span>
                </div>
                <div class="match-info-row" data-design-id="hero-info-cost">
                  <span class="label"><i class="bi bi-wallet2 me-1"></i>Biaya/Orang</span>
                  <span class="value text-lime">Rp 35.000</span>
                </div>
              </div>
              <div class="browser-action-btn" data-design-id="hero-browser-btn">Konfirmasi Match &#10003;</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ FIELD LINE DIVIDER ============ -->
  <div class="field-line-divider" data-design-id="divider-1">
    <div class="field-circle" data-design-id="divider-1-circle"></div>
  </div>

  <!-- ============ FEATURES ============ -->
  <section id="features" data-design-id="features-section">
    <div class="container" data-design-id="features-container">
      <div class="text-center mb-5 reveal" data-design-id="features-header">
        <div class="section-badge" data-design-id="features-badge">Fitur Unggulan</div>
        <h2 class="section-title" data-design-id="features-title">Semua yang Kamu Butuhkan<br>untuk <span class="text-lime" data-design-id="features-title-accent">Main Futsal</span></h2>
        <p class="section-subtitle" data-design-id="features-subtitle">Dari mencari lawan hingga split biaya, MATCHGO mengurus semuanya agar kamu fokus bermain.</p>
      </div>

      <div class="row g-4" data-design-id="features-grid">
        <div class="col-md-6 col-lg-4 reveal reveal-delay-1" data-design-id="feature-1-col">
          <div class="feature-card accent-lime" data-design-id="feature-1-card">
            <div class="feature-icon bg-lime" data-design-id="feature-1-icon"><i class="bi bi-bullseye"></i></div>
            <h4 data-design-id="feature-1-title">Smart Matchmaking</h4>
            <p data-design-id="feature-1-desc">Algoritma cerdas menemukan lawan dengan skill level setara. Pertandingan kompetitif dan seru dijamin!</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 reveal reveal-delay-2" data-design-id="feature-2-col">
          <div class="feature-card accent-cyan" data-design-id="feature-2-card">
            <div class="feature-icon bg-cyan" data-design-id="feature-2-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <h4 data-design-id="feature-2-title">Auto Venue Selection</h4>
            <p data-design-id="feature-2-desc">Sistem pilih lapangan otomatis berdasarkan lokasi, ketersediaan, dan budget. Tinggal datang dan main!</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 reveal reveal-delay-3" data-design-id="feature-3-col">
          <div class="feature-card accent-purple" data-design-id="feature-3-card">
            <div class="feature-icon bg-purple" data-design-id="feature-3-icon"><i class="bi bi-calculator"></i></div>
            <h4 data-design-id="feature-3-title">Smart Cost Split</h4>
            <p data-design-id="feature-3-desc">Pembagian biaya otomatis yang transparan. Tidak perlu lagi repot menghitung dan menagih satu per satu.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 reveal reveal-delay-4" data-design-id="feature-4-col">
          <div class="feature-card accent-rose" data-design-id="feature-4-card">
            <div class="feature-icon bg-rose" data-design-id="feature-4-icon"><i class="bi bi-people-fill"></i></div>
            <h4 data-design-id="feature-4-title">Team Profiles</h4>
            <p data-design-id="feature-4-desc">Buat profil tim lengkap dengan statistik, rating, dan riwayat pertandingan. Bangun reputasi tim kamu!</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 reveal reveal-delay-5" data-design-id="feature-5-col">
          <div class="feature-card accent-amber" data-design-id="feature-5-card">
            <div class="feature-icon bg-amber" data-design-id="feature-5-icon"><i class="bi bi-calendar-check"></i></div>
            <h4 data-design-id="feature-5-title">Schedule Management</h4>
            <p data-design-id="feature-5-desc">Kelola jadwal tim dengan mudah. Sinkronisasi otomatis dan reminder agar tidak ada yang ketinggalan.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 reveal reveal-delay-6" data-design-id="feature-6-col">
          <div class="feature-card accent-emerald" data-design-id="feature-6-card">
            <div class="feature-icon bg-emerald" data-design-id="feature-6-icon"><i class="bi bi-shield-check"></i></div>
            <h4 data-design-id="feature-6-title">Admin Dashboard</h4>
            <p data-design-id="feature-6-desc">Panel admin lengkap untuk monitor semua aktivitas, manajemen venue, dan analitik pertandingan.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ WEB PREVIEW ============ -->
  <section id="web-preview" data-design-id="preview-section">
    <div class="container" data-design-id="preview-container">
      <div class="text-center mb-5 reveal" data-design-id="preview-header">
        <div class="section-badge" data-design-id="preview-badge">Preview Platform</div>
        <h2 class="section-title" data-design-id="preview-title">Pengalaman <span class="text-lime" data-design-id="preview-title-accent">Terbaik</span> di Browser-mu</h2>
        <p class="section-subtitle" data-design-id="preview-subtitle">Antarmuka web yang intuitif, bisa diakses langsung dari laptop atau HP tanpa install apapun.</p>
      </div>

      <div class="preview-screens reveal" data-design-id="preview-screens">
        <!-- Screen 1: Matchmaking -->
        <div class="preview-screen-item" data-design-id="preview-screen-1">
          <div class="browser-mockup" data-design-id="preview-browser-1">
            <div class="browser-bar" data-design-id="preview-bar-1">
              <div class="browser-dots" data-design-id="preview-dots-1">
                <span data-design-id="preview-dot-1r"></span>
                <span data-design-id="preview-dot-1y"></span>
                <span data-design-id="preview-dot-1g"></span>
              </div>
              <div class="browser-url" data-design-id="preview-url-1">
                <i class="bi bi-lock-fill lock-icon"></i>
                matchgo.id/match
              </div>
            </div>
            <div class="browser-screen" data-design-id="preview-screen-1-content">
              <div class="browser-screen-header" data-design-id="preview-header-1">
                <h5 data-design-id="preview-title-1"><i class="bi bi-trophy-fill text-lime"></i> Matchmaking</h5>
                <small data-design-id="preview-subtitle-1">Lawan ditemukan</small>
              </div>
              <div class="vs-display" data-design-id="preview-vs">
                <div class="vs-team" data-design-id="preview-team-a">
                  <div class="vs-team-avatar" style="background: var(--lime-dim);" data-design-id="preview-team-a-avatar">&#9917;</div>
                  <div class="vs-team-name" data-design-id="preview-team-a-name">Elang FC</div>
                </div>
                <div class="vs-badge" data-design-id="preview-vs-badge">VS</div>
                <div class="vs-team" data-design-id="preview-team-b">
                  <div class="vs-team-avatar" style="background: var(--rose-dim);" data-design-id="preview-team-b-avatar">&#9918;</div>
                  <div class="vs-team-name" data-design-id="preview-team-b-name">Rajawali FC</div>
                </div>
              </div>
              <div class="match-info-grid" data-design-id="preview-info-grid-1">
                <div class="match-info-row" data-design-id="preview-info-1a">
                  <span class="label">Rating</span>
                  <span class="value">&#11088; 4.8 vs 4.6</span>
                </div>
                <div class="match-info-row" data-design-id="preview-info-1b">
                  <span class="label">Jarak</span>
                  <span class="value">2.4 km</span>
                </div>
                <div class="match-info-row" data-design-id="preview-info-1c">
                  <span class="label">Compatibility</span>
                  <span class="value text-lime">95%</span>
                </div>
              </div>
              <div class="browser-action-btn" data-design-id="preview-btn-1">Terima Challenge</div>
            </div>
          </div>
          <div class="screen-label" data-design-id="preview-label-1">Matchmaking</div>
        </div>

        <!-- Screen 2: Cost Split -->
        <div class="preview-screen-item" data-design-id="preview-screen-2">
          <div class="browser-mockup" data-design-id="preview-browser-2">
            <div class="browser-bar" data-design-id="preview-bar-2">
              <div class="browser-dots" data-design-id="preview-dots-2">
                <span data-design-id="preview-dot-2r"></span>
                <span data-design-id="preview-dot-2y"></span>
                <span data-design-id="preview-dot-2g"></span>
              </div>
              <div class="browser-url" data-design-id="preview-url-2">
                <i class="bi bi-lock-fill lock-icon"></i>
                matchgo.id/split
              </div>
            </div>
            <div class="browser-screen" data-design-id="preview-screen-2-content">
              <div class="browser-screen-header" data-design-id="preview-header-2">
                <h5 data-design-id="preview-title-2"><i class="bi bi-receipt-cutoff text-purple"></i> Split Biaya</h5>
                <small data-design-id="preview-subtitle-2">Detail pembagian</small>
              </div>
              <div style="flex:1; padding-top:8px;" data-design-id="preview-cost-list">
                <div class="cost-item" data-design-id="preview-cost-1">
                  <span class="cost-label">Sewa Lapangan</span>
                  <span class="cost-value">Rp 400.000</span>
                </div>
                <div class="cost-item" data-design-id="preview-cost-2">
                  <span class="cost-label">Air Mineral (20x)</span>
                  <span class="cost-value">Rp 60.000</span>
                </div>
                <div class="cost-item" data-design-id="preview-cost-3">
                  <span class="cost-label">Bola Cadangan</span>
                  <span class="cost-value">Rp 20.000</span>
                </div>
                <div class="cost-item" data-design-id="preview-cost-4">
                  <span class="cost-label">Rompi Tim</span>
                  <span class="cost-value">Rp 20.000</span>
                </div>
                <div class="cost-item" data-design-id="preview-cost-5">
                  <span class="cost-label">Jumlah Pemain</span>
                  <span class="cost-value">20 orang</span>
                </div>
                <div class="cost-total" data-design-id="preview-cost-total">
                  <span class="cost-label">Per Orang</span>
                  <span class="cost-value">Rp 25.000</span>
                </div>
              </div>
              <div class="browser-action-btn" data-design-id="preview-btn-2">Bayar Sekarang</div>
            </div>
          </div>
          <div class="screen-label" data-design-id="preview-label-2">Smart Cost Split</div>
        </div>

        <!-- Screen 3: Team Profile -->
        <div class="preview-screen-item" data-design-id="preview-screen-3">
          <div class="browser-mockup" data-design-id="preview-browser-3">
            <div class="browser-bar" data-design-id="preview-bar-3">
              <div class="browser-dots" data-design-id="preview-dots-3">
                <span data-design-id="preview-dot-3r"></span>
                <span data-design-id="preview-dot-3y"></span>
                <span data-design-id="preview-dot-3g"></span>
              </div>
              <div class="browser-url" data-design-id="preview-url-3">
                <i class="bi bi-lock-fill lock-icon"></i>
                matchgo.id/team/garuda
              </div>
            </div>
            <div class="browser-screen" data-design-id="preview-screen-3-content">
              <div class="browser-screen-header" data-design-id="preview-header-3">
                <h5 data-design-id="preview-title-3"><i class="bi bi-person-badge-fill text-cyan"></i> Profil Tim</h5>
              </div>
              <div class="text-center" data-design-id="preview-profile-info">
                <div class="profile-avatar" data-design-id="preview-profile-avatar">&#9917;</div>
                <h6 class="mb-0" data-design-id="preview-profile-name">Garuda FC</h6>
                <small style="color: var(--text-muted);" data-design-id="preview-profile-location">Jakarta Selatan</small>
              </div>
              <div class="profile-stats-row" data-design-id="preview-profile-stats">
                <div class="profile-stat" data-design-id="preview-stat-matches">
                  <div class="stat-num" data-design-id="preview-stat-matches-num">142</div>
                  <div class="stat-lbl" data-design-id="preview-stat-matches-lbl">Match</div>
                </div>
                <div class="profile-stat" data-design-id="preview-stat-wins">
                  <div class="stat-num" data-design-id="preview-stat-wins-num">98</div>
                  <div class="stat-lbl" data-design-id="preview-stat-wins-lbl">Menang</div>
                </div>
                <div class="profile-stat" data-design-id="preview-stat-rating">
                  <div class="stat-num" data-design-id="preview-stat-rating-num">4.8</div>
                  <div class="stat-lbl" data-design-id="preview-stat-rating-lbl">Rating</div>
                </div>
              </div>
              <div style="padding:0 4px;" data-design-id="preview-winrate-wrapper">
                <div class="d-flex justify-content-between" style="font-size:0.72rem;" data-design-id="preview-winrate-label">
                  <span style="color:var(--text-muted);" data-design-id="preview-winrate-text">Win Rate</span>
                  <span class="text-lime" data-design-id="preview-winrate-pct">69%</span>
                </div>
                <div class="winrate-bar" data-design-id="preview-winrate-bar">
                  <div class="winrate-fill" data-width="69%" style="width:0;" data-design-id="preview-winrate-fill"></div>
                </div>
              </div>
              <div class="match-info-grid mt-auto" data-design-id="preview-profile-matches">
                <div class="match-info-row" data-design-id="preview-profile-info-1">
                  <span class="label">Last Match</span>
                  <span class="value text-lime">W 5-3</span>
                </div>
                <div class="match-info-row" data-design-id="preview-profile-info-2">
                  <span class="label">Streak</span>
                  <span class="value">&#128293; 5 Win</span>
                </div>
              </div>
            </div>
          </div>
          <div class="screen-label" data-design-id="preview-label-3">Team Profile</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ FIELD LINE DIVIDER ============ -->
  <div class="field-line-divider" data-design-id="divider-2">
    <div class="field-circle" data-design-id="divider-2-circle"></div>
  </div>

  <!-- ============ HOW IT WORKS ============ -->
  <section id="how-it-works" data-design-id="how-section">
    <div class="container" data-design-id="how-container">
      <div class="text-center mb-5 reveal" data-design-id="how-header">
        <div class="section-badge" data-design-id="how-badge">Cara Kerja</div>
        <h2 class="section-title" data-design-id="how-title">Dari Daftar Sampai <span class="text-lime" data-design-id="how-title-accent">Kick-Off</span></h2>
        <p class="section-subtitle" data-design-id="how-subtitle">Hanya butuh beberapa langkah untuk mulai bermain bersama MATCHGO.</p>
      </div>

      <div class="row g-4" data-design-id="how-grid">
        <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-1" data-design-id="step-1-col">
          <div class="step-card" data-design-id="step-1-card">
            <div class="step-number" data-design-id="step-1-number">1</div>
            <h5 data-design-id="step-1-title">Daftar</h5>
            <p data-design-id="step-1-desc">Buat akun gratis dalam 30 detik</p>
            <span class="step-connector" data-design-id="step-1-connector"><i class="bi bi-chevron-right"></i></span>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-2" data-design-id="step-2-col">
          <div class="step-card" data-design-id="step-2-card">
            <div class="step-number" data-design-id="step-2-number">2</div>
            <h5 data-design-id="step-2-title">Buat Tim</h5>
            <p data-design-id="step-2-desc">Setup profil tim dan invite anggota</p>
            <span class="step-connector" data-design-id="step-2-connector"><i class="bi bi-chevron-right"></i></span>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-3" data-design-id="step-3-col">
          <div class="step-card" data-design-id="step-3-card">
            <div class="step-number" data-design-id="step-3-number">3</div>
            <h5 data-design-id="step-3-title">Cari Lawan</h5>
            <p data-design-id="step-3-desc">Matchmaking otomatis level setara</p>
            <span class="step-connector" data-design-id="step-3-connector"><i class="bi bi-chevron-right"></i></span>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-4" data-design-id="step-4-col">
          <div class="step-card" data-design-id="step-4-card">
            <div class="step-number" data-design-id="step-4-number">4</div>
            <h5 data-design-id="step-4-title">Booking</h5>
            <p data-design-id="step-4-desc">Lapangan terbaik dipilih otomatis</p>
            <span class="step-connector" data-design-id="step-4-connector"><i class="bi bi-chevron-right"></i></span>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-5" data-design-id="step-5-col">
          <div class="step-card" data-design-id="step-5-card">
            <div class="step-number" data-design-id="step-5-number">5</div>
            <h5 data-design-id="step-5-title">Bayar</h5>
            <p data-design-id="step-5-desc">Split biaya adil & transparan</p>
            <span class="step-connector" data-design-id="step-5-connector"><i class="bi bi-chevron-right"></i></span>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-6" data-design-id="step-6-col">
          <div class="step-card" data-design-id="step-6-card">
            <div class="step-number" data-design-id="step-6-number">6</div>
            <h5 data-design-id="step-6-title">Kick-Off!</h5>
            <p data-design-id="step-6-desc">Datang, main, dan menang!</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ STATS ============ -->
  <section id="stats" data-design-id="stats-section">
    <div class="container" data-design-id="stats-container">
      <div class="row g-4" data-design-id="stats-grid">
        <div class="col-6 col-lg-3 reveal reveal-delay-1" data-design-id="stat-1-col">
          <div class="stat-box" data-design-id="stat-1-box">
            <h2 data-counter="2500" data-suffix="+" data-design-id="stat-1-value">0</h2>
            <p data-design-id="stat-1-label">Tim Aktif</p>
          </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-delay-2" data-design-id="stat-2-col">
          <div class="stat-box" data-design-id="stat-2-box">
            <h2 data-counter="12000" data-suffix="+" data-design-id="stat-2-value">0</h2>
            <p data-design-id="stat-2-label">Match Selesai</p>
          </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-delay-3" data-design-id="stat-3-col">
          <div class="stat-box" data-design-id="stat-3-box">
            <h2 data-counter="350" data-suffix="+" data-design-id="stat-3-value">0</h2>
            <p data-design-id="stat-3-label">Venue Partner</p>
          </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-delay-4" data-design-id="stat-4-col">
          <div class="stat-box" data-design-id="stat-4-box">
            <h2 data-counter="98" data-suffix="%" data-design-id="stat-4-value">0</h2>
            <p data-design-id="stat-4-label">Kepuasan User</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ TESTIMONIALS ============ -->
  <section id="testimonials" data-design-id="testimonials-section">
    <div class="container" data-design-id="testimonials-container">
      <div class="text-center mb-5 reveal" data-design-id="testimonials-header">
        <div class="section-badge" data-design-id="testimonials-badge">Testimoni</div>
        <h2 class="section-title" data-design-id="testimonials-title">Apa Kata <span class="text-lime" data-design-id="testimonials-title-accent">Komunitas</span></h2>
        <p class="section-subtitle" data-design-id="testimonials-subtitle">Bergabung dengan ribuan pemain futsal yang sudah merasakan kemudahan MATCHGO.</p>
      </div>

      <div class="row g-4" data-design-id="testimonials-grid">
        <div class="col-md-4 reveal reveal-delay-1" data-design-id="testimonial-1-col">
          <div class="testimonial-card" data-design-id="testimonial-1-card">
            <div class="testimonial-stars" data-design-id="testimonial-1-stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="testimonial-text" data-design-id="testimonial-1-text">"MATCHGO benar-benar mengubah cara kami main futsal. Dulu susah banget cari lawan yang setara, sekarang tinggal buka web-nya aja. Mantap!"</p>
            <div class="testimonial-author" data-design-id="testimonial-1-author">
              <div class="testimonial-avatar" style="background: var(--lime);" data-design-id="testimonial-1-avatar">R</div>
              <div class="testimonial-author-info" data-design-id="testimonial-1-info">
                <h6 data-design-id="testimonial-1-name">Rizky Pratama</h6>
                <small data-design-id="testimonial-1-role">Kapten Garuda FC</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 reveal reveal-delay-2" data-design-id="testimonial-2-col">
          <div class="testimonial-card" data-design-id="testimonial-2-card">
            <div class="testimonial-stars" data-design-id="testimonial-2-stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="testimonial-text" data-design-id="testimonial-2-text">"Fitur split biaya-nya penyelamat banget. Gak perlu lagi ribut soal urusan duit. Semua transparan dan adil. Recommended!"</p>
            <div class="testimonial-author" data-design-id="testimonial-2-author">
              <div class="testimonial-avatar" style="background: var(--cyan);" data-design-id="testimonial-2-avatar">A</div>
              <div class="testimonial-author-info" data-design-id="testimonial-2-info">
                <h6 data-design-id="testimonial-2-name">Ahmad Fauzan</h6>
                <small data-design-id="testimonial-2-role">Manager Phoenix FC</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 reveal reveal-delay-3" data-design-id="testimonial-3-col">
          <div class="testimonial-card" data-design-id="testimonial-3-card">
            <div class="testimonial-stars" data-design-id="testimonial-3-stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <p class="testimonial-text" data-design-id="testimonial-3-text">"Sebagai venue owner, dashboard-nya sangat membantu. Booking dari MATCHGO selalu ramai dan terorganisir. Very recommended!"</p>
            <div class="testimonial-author" data-design-id="testimonial-3-author">
              <div class="testimonial-avatar" style="background: var(--purple);" data-design-id="testimonial-3-avatar">D</div>
              <div class="testimonial-author-info" data-design-id="testimonial-3-info">
                <h6 data-design-id="testimonial-3-name">Dinda Saputri</h6>
                <small data-design-id="testimonial-3-role">Owner SportArena</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ CTA ============ -->
  <section id="cta" data-design-id="cta-section">
    <div class="container" data-design-id="cta-container">
      <div class="cta-box reveal" data-design-id="cta-box">
        <h2 data-design-id="cta-title">Siap Jadi Juara <span class="text-lime" data-design-id="cta-title-accent">Futsal</span>?</h2>
        <p data-design-id="cta-subtitle">Bergabung dengan 2,500+ tim yang sudah mempercayakan pertandingan mereka di MATCHGO. Gratis, tanpa batasan fitur!</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap" data-design-id="cta-buttons">
          <a href="#" class="btn btn-lime btn-lg px-5" data-design-id="cta-btn-primary">
            <i class="bi bi-rocket-takeoff me-2"></i>Daftar Sekarang
          </a>
          <a href="#features" class="btn btn-outline-lime btn-lg px-4" data-design-id="cta-btn-secondary">
            <i class="bi bi-arrow-right-circle me-2"></i>Pelajari Fitur
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ FOOTER ============ -->
  <footer id="footer" data-design-id="footer-section">
    <div class="container" data-design-id="footer-container">
      <div class="row g-4" data-design-id="footer-grid">
        <div class="col-lg-4" data-design-id="footer-brand-col">
          <div class="footer-brand" data-design-id="footer-brand">MATCH<span class="brand-accent" data-design-id="footer-brand-accent">GO</span></div>
          <p class="footer-desc" data-design-id="footer-desc">Platform matchmaking futsal terbaik di Indonesia. Temukan lawan, booking venue, dan nikmati pertandingan terbaik &mdash; langsung dari browser.</p>
        </div>
        <div class="col-6 col-lg-2" data-design-id="footer-col-platform">
          <h6 class="footer-heading" data-design-id="footer-heading-platform">Platform</h6>
          <ul class="footer-links" data-design-id="footer-links-platform">
            <li data-design-id="footer-link-matchmaking"><a href="#">Matchmaking</a></li>
            <li data-design-id="footer-link-venues"><a href="#">Venues</a></li>
            <li data-design-id="footer-link-teams"><a href="#">Tim</a></li>
            <li data-design-id="footer-link-schedule"><a href="#">Jadwal</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-2" data-design-id="footer-col-community">
          <h6 class="footer-heading" data-design-id="footer-heading-community">Komunitas</h6>
          <ul class="footer-links" data-design-id="footer-links-community">
            <li data-design-id="footer-link-blog"><a href="#">Blog</a></li>
            <li data-design-id="footer-link-discord"><a href="#">Discord</a></li>
            <li data-design-id="footer-link-events"><a href="#">Events</a></li>
            <li data-design-id="footer-link-partners"><a href="#">Partners</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-2" data-design-id="footer-col-company">
          <h6 class="footer-heading" data-design-id="footer-heading-company">Perusahaan</h6>
          <ul class="footer-links" data-design-id="footer-links-company">
            <li data-design-id="footer-link-about"><a href="#">Tentang</a></li>
            <li data-design-id="footer-link-careers"><a href="#">Karir</a></li>
            <li data-design-id="footer-link-contact"><a href="#">Kontak</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-2" data-design-id="footer-col-legal">
          <h6 class="footer-heading" data-design-id="footer-heading-legal">Legal</h6>
          <ul class="footer-links" data-design-id="footer-links-legal">
            <li data-design-id="footer-link-privacy"><a href="#">Privacy</a></li>
            <li data-design-id="footer-link-terms"><a href="#">Terms</a></li>
            <li data-design-id="footer-link-security"><a href="#">Security</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom" data-design-id="footer-bottom">
        <p data-design-id="footer-copyright">&copy; 2026 MATCHGO. All rights reserved.</p>
        <div class="footer-socials" data-design-id="footer-socials">
          <a href="#" data-design-id="footer-social-instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" data-design-id="footer-social-twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" data-design-id="footer-social-youtube"><i class="bi bi-youtube"></i></a>
          <a href="#" data-design-id="footer-social-tiktok"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="{{ asset('js/landing_page/main.js') }}"></script>
</body>
</html>