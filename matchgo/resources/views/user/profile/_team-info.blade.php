<div class="mg-card">

    <p style="font-size:11px; color:var(--txt-faint); text-transform:uppercase;
              letter-spacing:0.08em; margin-bottom:20px;">TIM INFO</p>

    @if($team)
        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:var(--txt-muted); font-size:14px;">Nama Tim</span>
            <span style="color:var(--txt-primary); font-weight:600; font-size:14px;">{{ $team->team_name }}</span>
        </div>

        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:var(--txt-muted); font-size:14px;">Jenis</span>
            <span style="color:var(--txt-primary); font-weight:600; font-size:14px;">{{ $team->position }}</span>
        </div>

        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:var(--txt-muted); font-size:14px;">Pemain</span>
            <span style="color:var(--txt-primary); font-weight:600; font-size:14px;">{{ $team->member_count }} orang</span>
        </div>

        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:var(--txt-muted); font-size:14px;">Kota</span>
            <span style="color:var(--txt-primary); font-weight:600; font-size:14px;">{{ $team->city }}</span>
        </div>

        <div style="display:flex; justify-content:space-between;">
            <span style="color:var(--txt-muted); font-size:14px;">Rating</span>
            <span style="color:var(--txt-primary); font-weight:600; font-size:14px;">⭐ {{ $team->rating ?? '0.0' }}</span>
        </div>
    @else
        <p style="color:var(--txt-muted); font-size:14px;">Belum ada tim</p>
    @endif

</div>