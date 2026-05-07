{{-- resources/views/user/profile/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Profile — MATCHGO')
@section('page-title', 'Profile')

@push('styles')
<style>

/* ═══════════════════════════════════════════════════════
   RESET & BASE
═══════════════════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }

/* ═══════════════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════════════ */
.mg-hero {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
}
.mg-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
    pointer-events: none;
}
.mg-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    opacity: 0.35;
}
.mg-hero-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.mg-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--accent);
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    border-radius: 99px;
    padding: 3px 11px;
    margin-bottom: 10px;
}
.mg-hero h2 {
    font-family: 'Manrope', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1.25;
    margin-bottom: 6px;
}
.mg-hero h2 span { color: var(--accent); }
.mg-hero p {
    font-size: 0.83rem;
    color: var(--txt-muted);
    margin: 0;
    max-width: 420px;
}
.mg-hero-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    align-self: flex-start;
    margin-top: 4px;
}
.mg-hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s;
}
.mg-hero-btn-accent {
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    color: var(--accent);
}
.mg-hero-btn-muted {
    background: var(--surface-3);
    border: 1px solid var(--border-medium);
    color: var(--txt-secondary);
}

/* ═══════════════════════════════════════════════════════
   PROFILE GRID  ─  desktop: sidebar kiri + konten kanan
                 ─  mobile : kolom tunggal
═══════════════════════════════════════════════════════ */
.mg-profile-grid {
    display: grid;
    grid-template-columns: 272px 1fr;
    grid-template-rows: auto;
    gap: 1.5rem;
    align-items: start;
}

/* Kolom kiri — sticky saat scroll di desktop */
.mg-left {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    position: sticky;
    top: 1.25rem;
}

/* Kolom kanan */
.mg-right {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    min-width: 0; /* cegah overflow grid */
}

/* ── Mobile card profil horizontal (avatar + nama side-by-side) ── */
.mg-card-mobile-header {
    display: none; /* hanya muncul di mobile */
}

/* ═══════════════════════════════════════════════════════
   SECTION CARD
═══════════════════════════════════════════════════════ */
.mg-section {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 20px;
    overflow: hidden;
    transition: border-color .25s;
}
.mg-section-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--surface-3);
}
.mg-section-num {
    font-size: 1.35rem;
    font-family: 'Manrope', sans-serif;
    font-weight: 900;
    color: var(--accent);
    opacity: .30;
    line-height: 1;
    flex-shrink: 0;
    margin-top: 2px;
    min-width: 2ch;
}
.mg-section-title {
    font-family: 'Manrope', sans-serif;
    font-size: .82rem;
    font-weight: 800;
    color: var(--txt-primary);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 2px;
}
.mg-section-sub {
    font-size: .75rem;
    color: var(--txt-faint);
    margin: 0;
    line-height: 1.5;
}
.mg-section-header-meta {
    flex: 1;
    min-width: 0;
}
.mg-section-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.mg-section-body {
    padding: 1.5rem;
}

/* ═══════════════════════════════════════════════════════
   FORM ELEMENTS
═══════════════════════════════════════════════════════ */
.form-group-mg { margin-bottom: 0; }

.form-label-mg {
    display: block;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--txt-faint);
    margin-bottom: 6px;
}

.form-control-mg {
    width: 100%;
    padding: 10px 13px;
    border-radius: 12px;
    background: var(--surface-3);
    border: 1px solid var(--border-medium);
    color: var(--txt-primary);
    font-size: 0.875rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
    appearance: none;
    -webkit-appearance: none;
    height: 46px;
}
.form-control-mg:focus {
    border-color: var(--accent);
    background: var(--surface-4);
    box-shadow: 0 0 0 3px rgba(163,177,75,0.10);
}
textarea.form-control-mg {
    height: auto;
    min-height: 100px;
    resize: vertical;
    line-height: 1.6;
    padding-top: 11px;
}

/* icon kiri */
.input-icon-wrap { position: relative; }
.input-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--txt-faint);
    font-size: 13px;
    pointer-events: none;
    z-index: 2;
}
.form-control-mg.with-icon  { padding-left:  38px !important; }
.form-control-mg.with-eye   { padding-right: 44px !important; }

/* tombol mata */
.pwd-eye-btn {
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 0 12px 12px 0;
    cursor: pointer;
    color: var(--txt-faint);
    font-size: 15px;
    transition: color 0.15s, background 0.15s;
    z-index: 3;
}
.pwd-eye-btn:hover {
    color: var(--accent);
    background: rgba(163,177,75,0.07);
}

/* required star */
.required-star { color: #f87171; margin-left: 2px; }

/* char count */
.char-count-wrap {
    margin-top: 5px;
    font-size: .7rem;
    color: var(--txt-faint);
    text-align: right;
}

/* invalid */
.is-invalid-mg {
    border-color: rgba(239,68,68,.55) !important;
    background-color: rgba(239,68,68,.04) !important;
}
.is-invalid-mg:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important; }
.field-error-mg {
    margin-top: 5px;
    font-size: 0.75rem;
    color: #f87171;
    display: flex;
    align-items: center;
    gap: 5px;
}
.field-error-mg::before {
    content: '\F33A';
    font-family: 'Bootstrap-icons';
    font-size: .78rem;
}

/* ═══════════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════════ */
.btn-matchgo-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 22px;
    border-radius: 12px;
    background: var(--accent);
    color: var(--btn-primary-txt);
    font-weight: 700;
    font-size: 0.875rem;
    font-family: 'Manrope', sans-serif;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s, transform 0.15s;
    white-space: nowrap;
}
.btn-matchgo-primary:hover {
    background: var(--accent-hover);
    transform: translateY(-1px);
    color: var(--btn-primary-txt);
    text-decoration: none;
}
.btn-matchgo-primary:active { transform: scale(0.98); }

.btn-matchgo-outline {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 22px;
    border-radius: 12px;
    background: var(--surface-3);
    color: var(--txt-secondary);
    border: 1px solid var(--border-medium);
    font-weight: 600;
    font-size: 0.875rem;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-matchgo-outline:hover {
    background: var(--surface-4);
    border-color: var(--accent);
    color: var(--accent);
    text-decoration: none;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    padding-top: 1.25rem;
    border-top: 1px solid var(--border-subtle);
    margin-top: 1.5rem;
}

/* ═══════════════════════════════════════════════════════
   PASSWORD ACCORDION
═══════════════════════════════════════════════════════ */
.mg-pwd-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.775rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid var(--border-medium);
    background: var(--surface-3);
    color: var(--txt-secondary);
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: all 0.15s;
    flex-shrink: 0;
    align-self: center;
}
.mg-pwd-toggle:hover { border-color: var(--accent); color: var(--accent); background: var(--surface-4); }

.mg-pwd-fields {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height 0.35s ease, opacity 0.25s ease;
}
.mg-pwd-fields.is-open {
    max-height: 700px;
    opacity: 1;
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE — TABLET  ≤ 1024px
═══════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
    .mg-profile-grid {
        grid-template-columns: 240px 1fr;
        gap: 1.25rem;
    }
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE — MOBILE  ≤ 768px
   Sidebar kiri disembunyikan, diganti dengan
   card horizontal compact di atas form
═══════════════════════════════════════════════════════ */
@media (max-width: 768px) {

    /* Grid jadi satu kolom, sidebar tersembunyi */
    .mg-profile-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .mg-left {
        display: none; /* sembunyikan sidebar vertikal */
        position: static;
    }

    /* Card profil horizontal di atas form — hanya mobile */
    .mg-card-mobile-header {
        display: flex;
        align-items: center;
        gap: 14px;
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 18px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }
    .mg-card-mobile-avatar {
        flex-shrink: 0;
        position: relative;
    }
    .mg-card-mobile-avatar img,
    .mg-card-mobile-avatar .mg-avatar-placeholder {
        width: 62px;
        height: 62px;
        border-radius: 14px;
        object-fit: cover;
        border: 2px solid rgba(163,177,75,0.25);
    }
    .mg-avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-dim);
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 1.2rem;
        color: var(--accent);
    }
    .mg-card-mobile-camera {
        position: absolute;
        bottom: -5px; right: -5px;
        width: 24px; height: 24px;
        border-radius: 7px;
        background: var(--accent);
        color: var(--btn-primary-txt);
        border: 2px solid var(--surface-2);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem;
        cursor: pointer;
    }
    .mg-card-mobile-info {
        flex: 1;
        min-width: 0;
    }
    .mg-card-mobile-name {
        font-family: 'Manrope', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--txt-primary);
        line-height: 1.2;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .mg-card-mobile-email {
        font-size: 0.72rem;
        color: var(--txt-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .mg-card-mobile-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 6px;
    }
    .mg-card-mobile-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 99px;
        background: var(--surface-4);
        color: var(--txt-secondary);
        border: 1px solid var(--border-medium);
    }

    /* Hero lebih kompak di mobile */
    .mg-hero { padding: 1.25rem; margin-bottom: 1rem; }
    .mg-hero h2 { font-size: 1.2rem; }
    .mg-hero p  { font-size: 0.8rem; }
    .mg-hero-actions { margin-top: 0.75rem; width: 100%; }
    .mg-hero-btn { font-size: 0.7rem; padding: 5px 10px; }

    /* Section padding lebih kecil */
    .mg-section-header { padding: 1rem 1.25rem; }
    .mg-section-body   { padding: 1.25rem; }
    .mg-section-header-row { flex-direction: column; gap: 0.5rem; }
    .mg-pwd-toggle { align-self: flex-start !important; }

    /* Form actions full-width di mobile */
    .form-actions {
        flex-direction: column-reverse;
        gap: 8px;
    }
    .form-actions .btn-matchgo-primary,
    .form-actions .btn-matchgo-outline {
        width: 100%;
        justify-content: center;
    }
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE — SMALL MOBILE  ≤ 480px
═══════════════════════════════════════════════════════ */
@media (max-width: 480px) {
    .mg-hero { border-radius: 14px; }
    .mg-section { border-radius: 14px; }
    .mg-card-mobile-header { border-radius: 14px; }
    .mg-section-body { padding: 1rem; }
    /* Row grid jadi 1 kolom */
    .row.g-4 > [class*='col-lg'] { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
}

</style>
@endpush

@section('content')

{{-- ── Breadcrumb ── --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Profile</span></li>
</ul>

{{-- ── Hero ── --}}
<div class="mg-hero">
    <div class="mg-hero-grid"></div>
    <div class="mg-hero-content">
        <div>
            <div class="mg-hero-eyebrow"><i class="bi bi-person-circle"></i> Akun Saya</div>
            <h2>Kelola <span>Profil</span> & Informasimu</h2>
            <p>Perbarui data pribadi, foto, dan informasi tim yang tampil ke lawan tanding.</p>
        </div>
        <div class="mg-hero-actions">
            <a href="{{ route('matchmaking.index') }}" class="mg-hero-btn mg-hero-btn-accent">
                <i class="bi bi-search-heart"></i> Cari Lawan
            </a>
            <a href="{{ route('matches.index') }}" class="mg-hero-btn mg-hero-btn-muted">
                <i class="bi bi-calendar-event"></i> Pertandingan
            </a>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════
     CARD PROFIL HORIZONTAL — hanya tampil di mobile
     (sidebar .mg-left disembunyikan di mobile)
════════════════════════════════════════════════ --}}
<div class="mg-card-mobile-header">

    <div class="mg-card-mobile-avatar">
        @if($user->photo)
            <img src="{{ asset('storage/' . $user->photo) }}" id="avatarPreviewMobile"
                 alt="{{ $user->name }}">
        @else
            <div class="mg-avatar-placeholder" id="avatarPreviewMobile">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
        @endif
        {{-- tombol kamera — submit form yang sama dengan sidebar --}}
        <button type="button"
                class="mg-card-mobile-camera"
                onclick="document.getElementById('photoInputAvatar').click()"
                title="Ubah foto">
            <i class="bi bi-camera-fill"></i>
        </button>
    </div>

    <div class="mg-card-mobile-info">
        <div class="mg-card-mobile-name">{{ $user->name }}</div>
        <div class="mg-card-mobile-email">{{ $user->email }}</div>
        <div class="mg-card-mobile-pills">
            @if($user->city)
                <span class="mg-card-mobile-pill">
                    <i class="bi bi-geo-alt" style="color:var(--accent);"></i>
                    {{ $user->city }}
                </span>
            @endif
            @if($user->phone)
                <span class="mg-card-mobile-pill">
                    <i class="bi bi-telephone" style="color:var(--accent);"></i>
                    {{ $user->phone }}
                </span>
            @endif
            @if($team)
                <span class="mg-card-mobile-pill">
                    <i class="bi bi-people-fill" style="color:var(--accent);"></i>
                    {{ $team->name }}
                </span>
            @endif
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════
     MAIN GRID
════════════════════════════════════════════════ --}}
<div class="mg-profile-grid">

    {{-- ─── KIRI: Avatar card + Info tim ─── --}}
    <div class="mg-left">
        @include('user.profile.card')
        @include('user.profile.info')
    </div>

    {{-- ─── KANAN: Form edit ─── --}}
    <div class="mg-right">

        {{-- ══ Section 01: Data Akun ══ --}}
        <div class="mg-section">

            <div class="mg-section-header">
                <div class="mg-section-num">01</div>
                <div class="mg-section-header-meta">
                    <h6 class="mg-section-title">Data Akun</h6>
                    <p class="mg-section-sub">Informasi pribadi yang terdaftar pada akun Anda.</p>
                </div>
            </div>

            <div class="mg-section-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Nama Lengkap --}}
                        <div class="col-lg-6">
                            <div class="form-group-mg">
                                <label for="prof-name" class="form-label-mg">
                                    Nama Lengkap <span class="required-star">*</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="bi bi-person-fill input-icon"></i>
                                    <input type="text" id="prof-name" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="form-control-mg with-icon @error('name') is-invalid-mg @enderror"
                                           placeholder="Nama lengkap kamu" required>
                                </div>
                                @error('name')<div class="field-error-mg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-lg-6">
                            <div class="form-group-mg">
                                <label for="prof-email" class="form-label-mg">
                                    Email <span class="required-star">*</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="bi bi-envelope-fill input-icon"></i>
                                    <input type="email" id="prof-email" name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="form-control-mg with-icon @error('email') is-invalid-mg @enderror"
                                           placeholder="email@kamu.com" required>
                                </div>
                                @error('email')<div class="field-error-mg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- No. Telepon --}}
                        <div class="col-lg-6">
                            <div class="form-group-mg">
                                <label for="prof-phone" class="form-label-mg">No. Telepon</label>
                                <div class="input-icon-wrap">
                                    <i class="bi bi-telephone-fill input-icon"></i>
                                    <input type="text" id="prof-phone" name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           class="form-control-mg with-icon @error('phone') is-invalid-mg @enderror"
                                           placeholder="08xxxxxxxxxx">
                                </div>
                                @error('phone')<div class="field-error-mg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Kota --}}
                        <div class="col-lg-6">
                            <div class="form-group-mg">
                                <label for="prof-city" class="form-label-mg">Kota</label>
                                <div class="input-icon-wrap">
                                    <i class="bi bi-building input-icon"></i>
                                    <input type="text" id="prof-city" name="city"
                                           value="{{ old('city', $user->city) }}"
                                           class="form-control-mg with-icon @error('city') is-invalid-mg @enderror"
                                           placeholder="Jakarta, Bandung, …">
                                </div>
                                @error('city')<div class="field-error-mg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Bio --}}
                        <div class="col-12">
                            <div class="form-group-mg">
                                <label for="prof-bio" class="form-label-mg">Bio</label>
                                <textarea id="prof-bio" name="bio" rows="3"
                                          class="form-control-mg @error('bio') is-invalid-mg @enderror"
                                          placeholder="Ceritakan sedikit tentang dirimu…">{{ old('bio', $user->bio) }}</textarea>
                                <div class="char-count-wrap">
                                    <span id="bioCharCount">0</span> / 255 karakter
                                </div>
                                @error('bio')<div class="field-error-mg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>

                    <div class="form-actions">
                        <a href="{{ route('dashboard') }}" class="btn-matchgo-outline">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                        <button type="submit" class="btn-matchgo-primary">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>{{-- /section 01 --}}

        {{-- ══ Section 02: Keamanan ══ --}}
        <div class="mg-section">

            <div class="mg-section-header">
                <div class="mg-section-num">02</div>
                <div class="mg-section-header-meta">
                    <div class="mg-section-header-row">
                        <div>
                            <h6 class="mg-section-title">Keamanan Akun</h6>
                            <p class="mg-section-sub">Perbarui kata sandi untuk menjaga keamanan akun Anda.</p>
                        </div>
                        <button type="button" class="mg-pwd-toggle" id="mg-pwd-toggle-btn">
                            <i class="bi bi-chevron-down" id="mg-pwd-chevron"></i>
                            <span>Ubah Password</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mg-pwd-fields" id="mg-pwd-fields">
                <div class="mg-section-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Password Saat Ini (full width) --}}
                            <div class="col-12">
                                <div class="form-group-mg">
                                    <label for="pwd-current" class="form-label-mg">
                                        Password Saat Ini <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrap">
                                        <i class="bi bi-lock-fill input-icon"></i>
                                        <input type="password" id="pwd-current" name="current_password"
                                               class="form-control-mg with-icon with-eye"
                                               placeholder="••••••••"
                                               autocomplete="current-password">
                                        <button type="button" class="pwd-eye-btn"
                                                data-target="pwd-current"
                                                aria-label="Lihat password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Password Baru --}}
                            <div class="col-lg-6">
                                <div class="form-group-mg">
                                    <label for="pwd-new" class="form-label-mg">
                                        Password Baru <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrap">
                                        <i class="bi bi-shield-lock-fill input-icon"></i>
                                        <input type="password" id="pwd-new" name="password"
                                               class="form-control-mg with-icon with-eye"
                                               placeholder="Min. 8 karakter"
                                               autocomplete="new-password">
                                        <button type="button" class="pwd-eye-btn"
                                                data-target="pwd-new"
                                                aria-label="Lihat password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-lg-6">
                                <div class="form-group-mg">
                                    <label for="pwd-confirm" class="form-label-mg">
                                        Konfirmasi Password <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrap">
                                        <i class="bi bi-shield-check input-icon"></i>
                                        <input type="password" id="pwd-confirm" name="password_confirmation"
                                               class="form-control-mg with-icon with-eye"
                                               placeholder="Ulangi password baru"
                                               autocomplete="new-password">
                                        <button type="button" class="pwd-eye-btn"
                                                data-target="pwd-confirm"
                                                aria-label="Lihat password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-matchgo-primary">
                                <i class="bi bi-shield-check"></i> Perbarui Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>{{-- /section 02 --}}

    </div>{{-- /mg-right --}}

</div>{{-- /mg-profile-grid --}}

@push('scripts')
<script>
/* ─────────────────────────────────────
   Avatar preview + auto-submit
   (berlaku untuk input di sidebar & mobile header)
───────────────────────────────────── */
document.getElementById('photoInputAvatar')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (ev) {
        /* update sidebar (desktop) */
        const av = document.getElementById('avatarPreview');
        if (av) {
            if (av.tagName === 'IMG') {
                av.src = ev.target.result;
            } else {
                Object.assign(av.style, {
                    backgroundImage: `url(${ev.target.result})`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                });
                av.textContent = '';
            }
        }
        /* update mobile header */
        const avm = document.getElementById('avatarPreviewMobile');
        if (avm) {
            if (avm.tagName === 'IMG') {
                avm.src = ev.target.result;
            } else {
                avm.style.backgroundImage = `url(${ev.target.result})`;
                avm.style.backgroundSize  = 'cover';
                avm.style.backgroundPosition = 'center';
                avm.textContent = '';
            }
        }
    };
    reader.readAsDataURL(file);
    document.getElementById('photoForm')?.submit();
});

/* ─────────────────────────────────────
   Toggle section password
───────────────────────────────────── */
const pwdToggle  = document.getElementById('mg-pwd-toggle-btn');
const pwdFields  = document.getElementById('mg-pwd-fields');
const pwdChevron = document.getElementById('mg-pwd-chevron');

pwdToggle?.addEventListener('click', function () {
    const open = pwdFields.classList.toggle('is-open');
    pwdChevron.className = open ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    this.style.borderColor = open ? 'var(--accent)' : '';
    this.style.color       = open ? 'var(--accent)' : '';
});

/* ─────────────────────────────────────
   Eye toggle (show / hide password)
───────────────────────────────────── */
document.querySelectorAll('.pwd-eye-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        if (!input) return;
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        this.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        this.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Lihat password');
    });
});

/* ─────────────────────────────────────
   Bio char count
───────────────────────────────────── */
const bioTA = document.getElementById('prof-bio');
const bioCC = document.getElementById('bioCharCount');
function updateBioChar() {
    if (!bioCC || !bioTA) return;
    const len = bioTA.value.length;
    bioCC.textContent = len;
    bioCC.style.color = len > 240 ? '#f87171' : '';
}
bioTA?.addEventListener('input', updateBioChar);
updateBioChar();
</script>
@endpush

@endsection