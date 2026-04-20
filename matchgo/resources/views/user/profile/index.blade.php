@extends('user.layouts.app')

@section('title', 'Profile — MATCHGO')
@section('page-title', 'Profile')

@push('styles')
<style>
.mg-profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 32px;
}

.mg-left {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.mg-card {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 20px;
    padding: 32px;
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
    transition: border-color 0.2s;
}

.form-control-mg:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
}

textarea.form-control-mg {
    min-height: 120px;
    resize: vertical;
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
}

.btn-primary-mg:hover { background: var(--accent-hover); }

.btn-outline-mg {
    border: 1px solid var(--border-strong);
    padding: 13px 28px;
    border-radius: 14px;
    color: var(--txt-primary);
    background: transparent;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.15s;
}

.btn-outline-mg:hover {
    border-color: var(--accent);
    color: var(--accent);
    text-decoration: none;
}

@media (max-width: 900px) {
    .mg-profile-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- BREADCRUMB --}}
<ul class="breadcrumb-matchgo">
    <li><a href="#">Akun</a></li>
    <li class="separator">›</li>
    <li class="active">Profile</li>
</ul>

<div class="mg-profile-grid">

    {{-- LEFT --}}
    <div class="mg-left">
        @include('user.profile._profile-card')
        @include('user.profile._team-info')
    </div>

    {{-- RIGHT --}}
    <div class="mg-card">

        <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:28px; color:var(--txt-primary);">Edit Profile</h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

                <div>
                    <label class="mg-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control-mg">
                </div>

                <div>
                    <label class="mg-label">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control-mg">
                </div>

                <div>
                    <label class="mg-label">No. Telepon</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control-mg">
                </div>

                <div>
                    <label class="mg-label">Kota</label>
                    <input type="text" name="city" value="{{ $user->city }}" class="form-control-mg">
                </div>

            </div>

            <div style="margin-top:24px;">
                <label class="mg-label">Bio</label>
                <textarea name="bio" class="form-control-mg">{{ $user->bio }}</textarea>
            </div>

            <div style="display:flex; gap:16px; margin-top:28px;">
                <button type="submit" class="btn-primary-mg">✓ Simpan</button>
                <a href="{{ route('dashboard') }}" class="btn-outline-mg">Batal</a>
            </div>

        </form>

    </div>

</div>

{{-- JS FOTO --}}
<script>
document.getElementById('photoInputAvatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('avatarPreview');
            if (avatar.tagName === 'IMG') {
                avatar.src = e.target.result;
            } else {
                avatar.innerHTML = '';
                avatar.style.backgroundImage = `url(${e.target.result})`;
                avatar.style.backgroundSize = 'cover';
                avatar.style.backgroundPosition = 'center';
            }
        }
        reader.readAsDataURL(file);
        document.getElementById('photoForm').submit();
    }
});
</script>

@endsection