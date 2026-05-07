{{-- resources/views/user/profile/info.blade.php --}}
<div class="mg-section mb-0">

    {{-- Section header --}}
    <div class="mg-section-header">
        <div style="flex:1; display:flex; align-items:flex-end; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
            <h6 class="mg-section-title">Info Tim</h6>
            @if($team)
                <a href="{{ route('team.index') }}"
                   class="btn-matchgo-outline"
                   style="padding:5px 12px; border-radius:9px; font-size:0.72rem;
                          display:inline-flex; align-items:center; gap:5px; white-space:nowrap; align-self:center;">
                    <i class="bi bi-pencil"></i> Edit Tim
                </a>
            @endif
        </div>
    </div>

    <div class="mg-section-body">

        @if($team)

            {{-- Team avatar + nama --}}
            <div style="display:flex; align-items:center; gap:12px; padding:12px 14px;
                        background:var(--surface-3); border:1px solid var(--border-subtle);
                        border-radius:12px; margin-bottom:1rem;">
                @if(!empty($team->logo_path))
                    <img src="{{ asset('storage/' . $team->logo_path) }}" alt="{{ $team->name }}"
                         style="width:44px; height:44px; border-radius:11px; object-fit:cover;
                                border:1.5px solid rgba(163,177,75,0.25); flex-shrink:0;">
                @else
                    <div style="width:44px; height:44px; border-radius:11px;
                                background:var(--accent-dim); border:1.5px solid rgba(163,177,75,0.25);
                                display:flex; align-items:center; justify-content:center;
                                font-family:'Manrope',sans-serif; font-weight:800;
                                font-size:0.95rem; color:var(--accent); flex-shrink:0;">
                        {{ strtoupper(substr($team->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <div style="font-family:'Manrope',sans-serif; font-size:0.875rem; font-weight:700;
                                color:var(--txt-primary); line-height:1.2;">
                        {{ $team->name }}
                    </div>
                    <div style="font-size:0.7rem; color:var(--txt-muted); margin-top:2px;">
                        {{ ucfirst(str_replace('_', ' ', $team->level ?? '-')) }}
                    </div>
                </div>
            </div>

            {{-- Stats row --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:1rem;">
                <div style="padding:10px 12px; background:var(--surface-3);
                            border:1px solid var(--border-subtle); border-radius:11px;">
                    <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase;
                                letter-spacing:0.09em; color:var(--txt-faint); margin-bottom:4px;">
                        Pemain
                    </div>
                    <div style="font-family:'Manrope',sans-serif; font-size:1.1rem; font-weight:800;
                                color:var(--txt-primary); display:flex; align-items:center; gap:5px;">
                        <i class="bi bi-person" style="font-size:0.85rem; color:var(--accent);"></i>
                        {{ $team->members_count ?? 0 }}
                    </div>
                </div>
                <div style="padding:10px 12px; background:var(--surface-3);
                            border:1px solid var(--border-subtle); border-radius:11px;">
                    <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase;
                                letter-spacing:0.09em; color:var(--txt-faint); margin-bottom:4px;">
                        Rating
                    </div>
                    <div style="font-family:'Manrope',sans-serif; font-size:1.1rem; font-weight:800;
                                color:var(--txt-primary); display:flex; align-items:center; gap:5px;">
                        <i class="bi bi-star-fill" style="font-size:0.8rem; color:#fcd34d;"></i>
                        {{ $rating }}
                    </div>
                </div>
            </div>

            {{-- Kota --}}
            @if($team->city)
                <div style="display:flex; align-items:center; gap:7px;
                            font-size:0.78rem; color:var(--txt-muted);
                            padding:9px 12px; border-radius:10px;
                            background:var(--surface-3); border:1px solid var(--border-subtle);">
                    <i class="bi bi-geo-alt-fill" style="color:var(--accent); font-size:0.8rem;"></i>
                    {{ $team->city }}
                </div>
            @endif

        @else

            {{-- No team state --}}
            <div style="text-align:center; padding:1.5rem 0.5rem;">
                <div style="width:50px; height:50px; border-radius:14px;
                            background:var(--surface-4); border:1px solid var(--border-medium);
                            display:flex; align-items:center; justify-content:center;
                            font-size:1.3rem; color:var(--txt-faint); margin:0 auto 12px;">
                    <i class="bi bi-people"></i>
                </div>
                <div style="font-family:'Manrope',sans-serif; font-size:0.875rem; font-weight:700;
                            color:var(--txt-secondary); margin-bottom:5px;">
                    Belum Punya Tim
                </div>
                <p style="font-size:0.78rem; color:var(--txt-muted); margin-bottom:14px;
                           max-width:200px; margin-left:auto; margin-right:auto;">
                    Buat tim sekarang dan mulai ikut matchmaking.
                </p>
            </div>

        @endif

    </div>
</div>