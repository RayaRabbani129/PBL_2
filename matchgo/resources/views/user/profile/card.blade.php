{{-- resources/views/user/profile/card.blade.php --}}
<div class="mg-section mb-0">

    <div class="mg-section-body" style="display:flex; flex-direction:column; align-items:center; gap:1rem;">

        {{-- Avatar --}}
        <div style="position:relative;">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}"
                     id="avatarPreview"
                     style="width:90px; height:90px; border-radius:18px; object-fit:cover;
                            border:2px solid rgba(163,177,75,0.30);">
            @else
                <div id="avatarPreview"
                     style="width:90px; height:90px; border-radius:18px;
                            background:var(--accent-dim); border:2px solid rgba(163,177,75,0.30);
                            display:flex; align-items:center; justify-content:center;
                            font-family:'Manrope',sans-serif; font-weight:800;
                            font-size:1.6rem; color:var(--accent);">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            @endif

            {{-- Kamera badge --}}
            <form id="photoForm" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="file" name="photo" id="photoInputAvatar" accept="image/*" hidden>
                <button type="button"
                        onclick="document.getElementById('photoInputAvatar').click()"
                        title="Ubah foto"
                        style="position:absolute; bottom:-6px; right:-6px;
                               width:28px; height:28px; border-radius:8px;
                               background:var(--accent); color:var(--btn-primary-txt);
                               border:2px solid var(--surface-2);
                               display:flex; align-items:center; justify-content:center;
                               font-size:0.75rem; cursor:pointer; transition:background 0.15s;">
                    <i class="bi bi-camera-fill"></i>
                </button>
            </form>
        </div>

        {{-- Nama & email --}}
        <div style="text-align:center;">
            <div style="font-family:'Manrope',sans-serif; font-size:1rem; font-weight:800;
                        color:var(--txt-primary); line-height:1.2; margin-bottom:3px;">
                {{ $user->name }}
            </div>
            <div style="font-size:0.775rem; color:var(--txt-muted);">
                {{ $user->email }}
            </div>
        </div>

        <div style="height:1px; background:var(--border-subtle); width:100%;"></div>

        {{-- Meta pills --}}
        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:6px; width:100%;">
            @if($user->city)
                <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; font-weight:600;
                             padding:4px 10px; border-radius:99px;
                             background:var(--surface-4); color:var(--txt-secondary);
                             border:1px solid var(--border-medium);">
                    <i class="bi bi-geo-alt" style="color:var(--accent);"></i> {{ $user->city }}
                </span>
            @endif
            @if($user->phone)
                <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; font-weight:600;
                             padding:4px 10px; border-radius:99px;
                             background:var(--surface-4); color:var(--txt-secondary);
                             border:1px solid var(--border-medium);">
                    <i class="bi bi-telephone" style="color:var(--accent);"></i> {{ $user->phone }}
                </span>
            @endif
            @if($user->bio)
                <p style="font-size:0.78rem; color:var(--txt-muted); text-align:center;
                          margin:6px 0 0; line-height:1.55; width:100%;">
                    {{ Str::limit($user->bio, 80) }}
                </p>
            @endif
        </div>

    </div>
</div>