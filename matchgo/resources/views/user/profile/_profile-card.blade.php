<div class="mg-card" style="text-align:center;">

    {{-- AVATAR --}}
    <div style="display:flex; justify-content:center; margin-bottom:24px;">
        @if($user->photo)
            <img src="{{ asset('storage/' . $user->photo) }}"
                 id="avatarPreview"
                 style="width:100px; height:100px; border-radius:20px; object-fit:cover;">
        @else
            <div id="avatarPreview"
                 style="width:100px; height:100px; border-radius:20px; background:#A3B14B;
                        display:flex; align-items:center; justify-content:center;
                        font-weight:bold; color:black; font-size:26px;">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>
        @endif
    </div>

    {{-- NAMA --}}
    <h2 style="font-size:1.1rem; font-weight:600; color:var(--txt-primary); margin-bottom:4px;">
        {{ $user->name }}
    </h2>

    {{-- EMAIL --}}
    <p style="font-size:0.85rem; color:var(--txt-muted); margin-bottom:0;">
        {{ $user->email }}
    </p>

    {{-- BUTTON --}}
    <form id="photoForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="file" name="photo" id="photoInputAvatar" hidden>
        <button type="button"
            onclick="document.getElementById('photoInputAvatar').click()"
            class="btn-outline-mg"
            style="margin-top:20px; width:100%; display:block; text-align:center;">
            Ubah Foto
        </button>
    </form>

</div>