@extends('user.layouts.app')

@section('title', 'Profile — MATCHGO')
@section('page-title', 'Profile')

@push('styles')
<style>

/* =========================
   GRID LAYOUT
========================= */

.mg-profile-grid {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 32px;
}

.mg-left {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* =========================
   BASE CARD
========================= */

.mg-card {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 20px;
    padding: 32px;
}

/* =========================
   PROFILE CARD (kiri atas)
========================= */

.mg-profile-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 48px 32px;
}

.mg-avatar-wrap {
    width: 120px;
    height: 120px;
    border-radius: 28px;
    margin-bottom: 20px;
    background: rgba(183, 214, 58, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--accent);
    overflow: hidden;
    flex-shrink: 0;
}

.mg-avatar-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 28px;
}

.mg-user-name {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--txt-primary);
    margin: 0 0 6px 0;
}

.mg-user-email {
    font-size: 0.875rem;
    color: var(--txt-secondary);
    margin: 0 0 24px 0;
}

.btn-ubah-foto {
    border: 1px solid var(--border-strong);
    padding: 10px 24px;
    border-radius: 12px;
    color: var(--txt-primary);
    background: transparent;
    font-size: 0.875rem;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.15s, color 0.15s;
}

.btn-ubah-foto:hover {
    border-color: var(--accent);
    color: var(--accent);
}

/* =========================
   TEAM INFO CARD (kiri bawah)
========================= */

.mg-team-card {
    padding: 28px 32px;
}

.mg-team-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: var(--txt-secondary);
    text-transform: uppercase;
    margin-bottom: 20px;
    display: block;
}

.mg-team-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-subtle);
    font-size: 0.875rem;
}

.mg-team-row:last-child {
    border-bottom: none;
}

.mg-team-key {
    color: var(--txt-secondary);
}

.mg-team-val {
    color: var(--txt-primary);
    font-weight: 600;
}

.mg-rating-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--txt-primary);
    font-weight: 600;
}

.mg-rating-wrap .star {
    color: #f5c518;
    font-size: 1rem;
}

/* =========================
   EDIT PROFILE FORM (kanan)
========================= */

.mg-form-card {
    padding: 40px;
}

.mg-form-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 28px 0;
    color: var(--txt-primary);
}

.mg-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.mg-field {
    display: flex;
    flex-direction: column;
}

.mg-label {
    font-size: 13px;
    color: var(--txt-secondary);
    margin-bottom: 8px;
    display: block;
}

.form-control-mg {
    width: 100%;
    padding: 14px 16px;
    border-radius: 14px;
    background: var(--surface-3);
    border: 1px solid var(--border-medium);
    color: var(--txt-primary);
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control-mg:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
}

textarea.form-control-mg {
    min-height: 120px;
    resize: vertical;
}

.mg-form-actions {
    display: flex;
    gap: 16px;
    margin-top: 28px;
}

.btn-primary-mg {
    background: var(--accent);
    color: var(--btn-primary-txt);
    padding: 13px 28px;
    border-radius: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    transition: background 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary-mg:hover {
    background: var(--accent-hover);
}

.btn-outline-mg {
    border: 1px solid var(--border-strong);
    padding: 13px 28px;
    border-radius: 14px;
    color: var(--txt-primary);
    background: transparent;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.15s, color 0.15s;
}

.btn-outline-mg:hover {
    border-color: var(--accent);
    color: var(--accent);
    text-decoration: none;
}

/* =========================
   BREADCRUMB
========================= */

.breadcrumb-matchgo {
    list-style: none;
    margin: 0 0 12px 0;
    padding: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
}

.breadcrumb-matchgo li a {
    color: var(--txt-secondary);
    text-decoration: none;
    transition: color 0.15s;
}

.breadcrumb-matchgo li a:hover {
    color: var(--accent);
}

.breadcrumb-matchgo li.separator {
    color: var(--txt-secondary);
    opacity: 0.5;
}

.breadcrumb-matchgo li.active {
    color: var(--txt-primary);
    font-weight: 500;
}

/* =========================
   PAGE TITLE
========================= */

.mg-page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--txt-primary);
    margin: 0 0 28px 0;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 1100px) {
    .mg-profile-grid {
        grid-template-columns: 320px 1fr;
    }
}

@media (max-width: 900px) {
    .mg-profile-grid {
        grid-template-columns: 1fr;
    }

    .mg-form-grid {
        grid-template-columns: 1fr;
    }

    .mg-form-card {
        padding: 28px;
    }
}

@media (max-width: 480px) {
    .mg-profile-card {
        padding: 36px 24px;
    }

    .mg-page-title {
        font-size: 1.5rem;
    }

    .mg-form-actions {
        flex-direction: column;
    }

    .btn-primary-mg,
    .btn-outline-mg {
        width: 100%;
        justify-content: center;
    }
}

</style>
@endpush

@section('content')

{{-- BREADCRUMB --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="separator">›</li>
    <li class="active">Profile</li>
</ul>

{{-- PAGE TITLE --}}
<h1 class="mg-page-title">Profile</h1>

<div class="mg-profile-grid">

    {{-- =====================
        LEFT COLUMN
    ====================== --}}
    <div class="mg-left">

        {{-- PROFILE CARD --}}
        <div class="mg-card mg-profile-card">

            {{-- Avatar --}}
            <div class="mg-avatar-wrap" id="avatarPreview">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>

            {{-- Nama & Email --}}
            <p class="mg-user-name">{{ $user->name }}</p>
            <p class="mg-user-email">{{ $user->email }}</p>

            {{-- Tombol Ubah Foto --}}
            <form id="photoForm"
                  action="{{ route('profile.photo') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <input type="file"
                       id="photoInputAvatar"
                       name="photo"
                       accept="image/*"
                       style="display:none">

                <button type="button"
                        class="btn-ubah-foto"
                        onclick="document.getElementById('photoInputAvatar').click()">
                    Ubah Foto
                </button>

            </form>

        </div>

        {{-- TEAM INFO CARD --}}
        @if(isset($team) && $team)
        <div class="mg-card mg-team-card">

            <span class="mg-team-label">Tim Info</span>

            <div class="mg-team-row">
                <span class="mg-team-key">Nama Tim</span>
                <span class="mg-team-val">{{ $team->name }}</span>
            </div>

            <div class="mg-team-row">
                <span class="mg-team-key">Pemain</span>
                <span class="mg-team-val">{{ $team->members_count ?? $team->members->count() }} orang</span>
            </div>

            <div class="mg-team-row">
                <span class="mg-team-key">Kota</span>
                <span class="mg-team-val">{{ $team->city ?? '-' }}</span>
            </div>

            <div class="mg-team-row">
                <span class="mg-team-key">Rating</span>
                <span class="mg-rating-wrap">
                    <span class="star">★</span>
                    {{ number_format($team->rating ?? 0, 1) }}
                </span>
            </div>

        </div>
        @endif

    </div>

    {{-- =====================
        RIGHT COLUMN
    ====================== --}}
    <div class="mg-card mg-form-card">

        <h3 class="mg-form-title">Edit Profile</h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ROW 1: Nama & Email --}}
            <div class="mg-form-grid">

                <div class="mg-field">
                    <label class="mg-label">Nama Lengkap</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-control-mg"
                           placeholder="Nama lengkap">
                </div>

                <div class="mg-field">
                    <label class="mg-label">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-control-mg"
                           placeholder="email@contoh.com">
                </div>

                <div class="mg-field">
                    <label class="mg-label">No. Telepon</label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="form-control-mg"
                           placeholder="08xx-xxxx-xxxx">
                </div>

                <div class="mg-field">
                    <label class="mg-label">Kota</label>
                    <input type="text"
                           name="city"
                           value="{{ old('city', $user->city) }}"
                           class="form-control-mg"
                           placeholder="Kota kamu">
                </div>

            </div>

            {{-- Bio --}}
            <div class="mg-field" style="margin-top: 24px;">
                <label class="mg-label">Bio</label>
                <textarea name="bio"
                          class="form-control-mg"
                          placeholder="Ceritakan sedikit tentang kamu...">{{ old('bio', $user->bio) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="mg-form-actions">
                <button type="submit" class="btn-primary-mg">
                    ✓ Simpan
                </button>
                <a href="{{ route('dashboard') }}" class="btn-outline-mg">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

{{-- JS: Preview foto sebelum upload --}}
<script>
document.getElementById('photoInputAvatar').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const wrap = document.getElementById('avatarPreview');
        const existing = wrap.querySelector('img');
        if (existing) {
            existing.src = e.target.result;
        } else {
            wrap.innerHTML = '';
            const img = document.createElement('img');
            img.src = e.target.result;
            wrap.appendChild(img);
        }
    };
    reader.readAsDataURL(file);
    document.getElementById('photoForm').submit();
});
</script>

@endsection