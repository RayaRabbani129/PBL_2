@extends('user.layouts.app')

@section('title', 'Profile — MATCHGO')
@section('page-title', 'Profile')

@push('styles')
<style>
.mg-profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 28px;
}

.mg-card {
    background: linear-gradient(145deg, #161616, #111111);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 20px;
    padding: 28px;
}

.mg-avatar {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    object-fit: cover;
    background: #A3B14B;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    margin: 0 auto;
}

.form-control-mg {
    width:100%;
    padding:14px;
    border-radius:14px;
    background:#0B0B0B;
    border:1px solid rgba(255,255,255,0.08);
    color:white;
}

.btn-primary-mg {
    background:#A3B14B;
    color:#000;
    padding:12px 20px;
    border-radius:12px;
}

.btn-outline-mg {
    border:1px solid rgba(255,255,255,0.1);
    padding:12px 20px;
    border-radius:12px;
}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="mb-4 text-green-400">
    {{ session('success') }}
</div>
@endif

<div class="mg-profile-grid">

    {{-- LEFT --}}
    <div class="space-y-6">
        @include('user.profile._profile-card')
        @include('user.profile._team-info')
    </div>

    {{-- RIGHT --}}
    <div class="mg-card">

        <h3 class="text-lg font-bold mb-6">Edit Profile</h3>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control-mg">
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control-mg">
                </div>

                <div>
                    <label>No. Telepon</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control-mg">
                </div>

                <div>
                    <label>Kota</label>
                    <input type="text" name="city" value="{{ $user->city }}" class="form-control-mg">
                </div>

            </div>

            <div class="mt-4">
                <label>Bio</label>
                <textarea name="bio" class="form-control-mg">{{ $user->bio }}</textarea>
            </div>

            <div class="mt-4">
                <label>Foto Profil</label>
                <input type="file" name="photo" id="photoInput" class="form-control-mg">
            </div>

            <div class="flex gap-3 mt-6">
                <button class="btn-primary-mg">✔ Simpan</button>
                <a href="{{ route('dashboard') }}" class="btn-outline-mg">Batal</a>
            </div>

        </form>

        <script>
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // preview
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

                // AUTO SUBMIT TANPA TOMBOL
                document.getElementById('photoForm').submit();
            }
        });
        </script>

    </div>

</div>

@endsection