{{-- resources/views/user/matches/_challenge-row.blade.php --}}
{{--
    $matchRequest — MatchRequest model (with team, matchedTeam)
    $type         — 'incoming' | 'outgoing'
--}}

@push('styles')
<style>
    .challenge-row {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: border-color 0.15s, background 0.15s;
        position: relative;
        overflow: hidden;
    }

    .challenge-row.incoming {
        border-color: rgba(239,68,68,0.18);
    }

    .challenge-row.incoming::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; border-radius: 3px 0 0 3px;
        background: #f87171;
    }

    .challenge-row.outgoing::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; border-radius: 3px 0 0 3px;
        background: #fcd34d;
    }

    .challenge-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 99px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .challenge-badge.incoming { background: rgba(239,68,68,0.10); color: #f87171; border: 1px solid rgba(239,68,68,0.20); }
    .challenge-badge.outgoing { background: rgba(251,191,36,0.10); color: #fcd34d; border: 1px solid rgba(251,191,36,0.20); }
    .challenge-badge.accepted { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .challenge-badge.rejected { background: var(--surface-4); color: var(--txt-muted); border: 1px solid var(--border-subtle); }

    .challenge-team-avatar {
        width: 42px; height: 42px;
        border-radius: 11px;
        background: var(--surface-4);
        border: 1.5px solid var(--border-medium);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 800; font-size: 0.9rem;
        color: var(--txt-muted); flex-shrink: 0;
    }

    .challenge-team-name {
        font-family: 'Manrope', sans-serif;
        font-size: 0.9rem; font-weight: 700;
        color: var(--txt-primary); margin-bottom: 2px;
    }

    .challenge-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 4px;
    }

    .challenge-meta-item {
        font-size: 0.72rem; color: var(--txt-muted);
        display: flex; align-items: center; gap: 4px;
    }

    .challenge-meta-item i { color: var(--accent); font-size: 0.72rem; }

    .challenge-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
        margin-left: auto;
    }

    @media (max-width: 767px) {
        .challenge-row { flex-wrap: wrap; }
        .challenge-actions { width: 100%; }
        .challenge-actions .btn-lime,
        .challenge-actions .btn-matchgo-danger,
        .challenge-actions .btn-outline-lime { flex: 1; justify-content: center; }
    }
</style>
@endpush

@php
    $challengerTeam = $type === 'incoming' ? $matchRequest->team : $matchRequest->matchedTeam;
    $dirLabel       = $type === 'incoming' ? 'Tantangan dari' : 'Tantangan ke';
    $statusClass    = match ($matchRequest->status) {
        'accepted' => 'accepted',
        'rejected' => 'rejected',
        default    => $type,
    };

    $statusLabel = match ($matchRequest->status) {
        'pending'  => $type === 'incoming' ? 'Menunggu Responmu' : 'Menunggu Konfirmasi',
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
        default    => ucfirst($matchRequest->status),
    };
@endphp

<div class="challenge-row {{ $type }}">

    {{-- Avatar --}}
    <div class="challenge-team-avatar">
        {{ strtoupper(substr($challengerTeam->name ?? '?', 0, 2)) }}
    </div>

    {{-- Info --}}
    <div class="flex-1" style="min-width:0;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
            <span style="font-size:0.68rem; color:var(--txt-faint); font-weight:600;">{{ $dirLabel }}</span>
            <span class="challenge-badge {{ $statusClass }}">
                @if ($matchRequest->status === 'pending')
                    <i class="bi {{ $type === 'incoming' ? 'bi-lightning-charge' : 'bi-hourglass' }}"></i>
                @elseif ($matchRequest->status === 'accepted')
                    <i class="bi bi-check-circle"></i>
                @else
                    <i class="bi bi-x-circle"></i>
                @endif
                {{ $statusLabel }}
            </span>
        </div>

        <div class="challenge-team-name">{{ $challengerTeam->name ?? '—' }}</div>

        <div class="challenge-meta">
            @if ($matchRequest->preferred_date)
                <span class="challenge-meta-item">
                    <i class="bi bi-calendar3"></i>
                    {{ \Carbon\Carbon::parse($matchRequest->preferred_date)->translatedFormat('D, d M Y') }}
                </span>
            @endif
            @if ($matchRequest->start_time)
                <span class="challenge-meta-item">
                    <i class="bi bi-clock"></i>
                    {{ \Carbon\Carbon::parse($matchRequest->start_time)->format('H:i') }}
                    @if ($matchRequest->end_time)
                        – {{ \Carbon\Carbon::parse($matchRequest->end_time)->format('H:i') }}
                    @endif
                </span>
            @endif
            <span class="challenge-meta-item">
                <i class="bi bi-clock-history"></i>
                {{ $matchRequest->created_at->diffForHumans() }}
            </span>
        </div>
    </div>

    {{-- Actions --}}
    @if ($matchRequest->status === 'pending')
        <div class="challenge-actions">
            @if ($type === 'incoming')
                <form action="{{ route('matches.challenge.accept', $matchRequest) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-lime btn-sm">
                        <i class="bi bi-check-lg"></i> Terima
                    </button>
                </form>
                <form action="{{ route('matches.challenge.reject', $matchRequest) }}" method="POST"
                      onsubmit="return confirm('Tolak tantangan ini?')">
                    @csrf
                    <button type="submit" class="btn-matchgo-danger btn-sm">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </form>
            @else
                <span style="font-size:0.75rem; color:var(--txt-faint); display:flex; align-items:center; gap:5px;">
                    <i class="bi bi-hourglass-split" style="color:var(--accent);"></i> Menunggu respons lawan
                </span>
            @endif
        </div>
    @endif

</div>