{{-- resources/views/user/matches/_match-row.blade.php --}}
{{--
    $match   — Matches model (with homeTeam, awayTeam, venue, verification)
    $myTeam  — Team model (tim saya)
    $type    — 'upcoming' | 'completed'
--}}

@push('styles')
<style>
    .match-row {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: border-color 0.2s, background 0.15s, transform 0.15s;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .match-row::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px;
        border-radius: 3px 0 0 3px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .match-row:hover {
        border-color: rgba(163,177,75,0.25);
        background: var(--surface-3);
        transform: translateY(-1px);
        text-decoration: none;
    }

    .match-row:hover::before { opacity: 1; }
    .match-row::before { background: var(--accent); }

    /* Status variants */
    .match-row.status-completed { border-color: rgba(134,239,172,0.15); }
    .match-row.status-completed::before { background: #86efac; opacity: 1; }
    .match-row.status-cancelled { opacity: 0.55; }

    /* VS block */
    .match-vs-block {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .match-team-side {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        min-width: 0;
        flex: 1;
    }

    .match-team-avatar {
        width: 40px; height: 40px;
        border-radius: 11px;
        background: var(--accent-dim);
        border: 1.5px solid rgba(163,177,75,0.22);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 800; font-size: 0.9rem;
        color: var(--accent); flex-shrink: 0;
    }

    .match-team-avatar.opp {
        background: var(--surface-4);
        border-color: var(--border-medium);
        color: var(--txt-muted);
    }

    .match-team-name {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--txt-primary);
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 90px;
    }

    .match-team-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--txt-faint);
    }

    /* VS / Score centre */
    .match-centre {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        flex-shrink: 0;
    }

    .match-vs {
        font-family: 'Manrope', sans-serif;
        font-size: 0.7rem;
        font-weight: 800;
        color: var(--txt-faint);
        letter-spacing: 0.08em;
    }

    .match-score {
        font-family: 'Manrope', sans-serif;
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--txt-primary);
        letter-spacing: 0.04em;
        line-height: 1;
    }

    .match-score-sep { color: var(--txt-faint); margin: 0 3px; font-size: 0.9rem; }

    /* Meta info */
    .match-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 160px;
        flex-shrink: 0;
    }

    .match-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.73rem;
        color: var(--txt-muted);
    }

    .match-meta-item i { color: var(--accent); font-size: 0.75rem; width: 12px; }

    /* Status badge */
    .match-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
        white-space: nowrap;
    }

    .status-pending   { background: rgba(251,191,36,0.10);  color: #fcd34d; border: 1px solid rgba(251,191,36,0.20); }
    .status-accepted  { background: var(--accent-dim);      color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .status-completed { background: rgba(134,239,172,0.12); color: #86efac; border: 1px solid rgba(134,239,172,0.20); }
    .status-cancelled { background: var(--surface-4);       color: var(--txt-muted); border: 1px solid var(--border-subtle); }
    .status-verifying { background: rgba(34,211,238,0.10);  color: #67e8f9; border: 1px solid rgba(34,211,238,0.20); }

    /* Actions */
    .match-actions {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex-shrink: 0;
    }

    @media (max-width: 767px) {
        .match-row { flex-wrap: wrap; }
        .match-meta { min-width: 0; width: 100%; }
    }
</style>
@endpush

@php
    $isHome  = $match->home_team_id === $myTeam->id;
    $myTeamR = $isHome ? $match->homeTeam  : $match->awayTeam;
    $oppTeam = $isHome ? $match->awayTeam  : $match->homeTeam;
    $myScore = $isHome ? $match->home_score : $match->away_score;
    $opScore = $isHome ? $match->away_score : $match->home_score;

    $statusMap = [
        'pending'   => ['label' => 'Menunggu',  'class' => 'status-pending',   'icon' => 'bi-hourglass'],
        'accepted'  => ['label' => 'Terjadwal', 'class' => 'status-accepted',  'icon' => 'bi-calendar-check'],
        'completed' => ['label' => 'Selesai',   'class' => 'status-completed', 'icon' => 'bi-trophy'],
        'cancelled' => ['label' => 'Dibatalkan','class' => 'status-cancelled', 'icon' => 'bi-x-circle'],
    ];

    // Jika completed tapi verification masih pending → verifying
    $displayStatus = $match->status;
    if ($match->status === 'completed' && optional($match->verification)->status === 'pending') {
        $displayStatus = 'verifying';
        $statusMap['verifying'] = ['label' => 'Verifikasi', 'class' => 'status-verifying', 'icon' => 'bi-shield-check'];
    }

    $st = $statusMap[$displayStatus] ?? $statusMap['pending'];
@endphp

<a href="{{ route('matches.show', $match) }}" class="match-row status-{{ $match->status }}">

    {{-- Match code --}}
    <div style="flex-shrink:0; min-width:70px;">
        <div style="font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--txt-faint); margin-bottom:2px;">Match</div>
        <div style="font-family:'Manrope',sans-serif; font-size:0.72rem; font-weight:700; color:var(--txt-secondary);">{{ $match->match_code }}</div>
    </div>

    {{-- VS block --}}
    <div class="match-vs-block">
        {{-- My team --}}
        <div class="match-team-side">
            <div class="match-team-avatar">{{ strtoupper(substr($myTeamR->name ?? '?', 0, 2)) }}</div>
            <div class="match-team-name">{{ $myTeamR->name ?? '—' }}</div>
            <span class="match-team-label">Saya</span>
        </div>

        {{-- Centre: VS or Score --}}
        <div class="match-centre">
            @if ($match->status === 'completed' && !is_null($myScore))
                <div class="match-score">
                    <span style="{{ ($myScore > $opScore) ? 'color:var(--accent)' : '' }}">{{ $myScore }}</span>
                    <span class="match-score-sep">—</span>
                    <span style="{{ ($opScore > $myScore) ? 'color:#f87171' : '' }}">{{ $opScore }}</span>
                </div>
                @if ($myScore > $opScore)
                    <span style="font-size:0.62rem; font-weight:700; color:var(--accent); margin-top:1px;">MENANG</span>
                @elseif ($myScore < $opScore)
                    <span style="font-size:0.62rem; font-weight:700; color:#f87171; margin-top:1px;">KALAH</span>
                @else
                    <span style="font-size:0.62rem; font-weight:700; color:var(--txt-muted); margin-top:1px;">SERI</span>
                @endif
            @else
                <div class="match-vs">VS</div>
            @endif
        </div>

        {{-- Opponent --}}
        <div class="match-team-side">
            <div class="match-team-avatar opp">{{ strtoupper(substr($oppTeam->name ?? '?', 0, 2)) }}</div>
            <div class="match-team-name" style="color:var(--txt-secondary);">{{ $oppTeam->name ?? '—' }}</div>
            <span class="match-team-label">Lawan</span>
        </div>
    </div>

    {{-- Match meta --}}
    <div class="match-meta">
        @if ($match->match_datetime)
            <div class="match-meta-item">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::parse($match->match_datetime)->translatedFormat('D, d M Y') }}
            </div>
            <div class="match-meta-item">
                <i class="bi bi-clock"></i>
                {{ \Carbon\Carbon::parse($match->match_datetime)->format('H:i') }}
                @if ($match->duration_minutes)
                    <span style="color:var(--txt-faint);">({{ $match->duration_minutes }} mnt)</span>
                @endif
            </div>
        @endif
        @if ($match->venue)
            <div class="match-meta-item">
                <i class="bi bi-geo-alt"></i>
                {{ Str::limit($match->venue->name, 22) }}
            </div>
        @endif
        <div style="margin-top:2px;">
            <span class="match-status-badge {{ $st['class'] }}">
                <i class="bi {{ $st['icon'] }}"></i> {{ $st['label'] }}
            </span>
        </div>
    </div>

    {{-- Actions --}}
    <div class="match-actions" onclick="event.preventDefault();">
        @if (in_array($match->status, ['pending', 'accepted']))
            <form action="{{ route('matches.cancel', $match) }}" method="POST"
                  onsubmit="return confirm('Batalkan match ini?')">
                @csrf
                <button type="submit" class="btn-matchgo-danger btn-sm" style="width:100%;">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
            </form>
        @endif

        @if ($match->status === 'accepted' && \Carbon\Carbon::parse($match->match_datetime)->isPast() && is_null($match->home_score))
            <a href="{{ route('matches.show', $match) }}#input-score"
               class="btn-lime btn-sm" style="width:100%; justify-content:center;">
                <i class="bi bi-pencil-square"></i> Input Skor
            </a>
        @endif

        <a href="{{ route('matches.show', $match) }}"
           class="btn-outline-lime btn-sm" style="width:100%; justify-content:center;">
            <i class="bi bi-eye"></i> Detail
        </a>
    </div>

</a>