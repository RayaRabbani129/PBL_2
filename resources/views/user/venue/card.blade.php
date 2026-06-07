{{-- resources/views/user/venue/_venue-card.blade.php --}}
{{--
    $venue           — Venue model
    $score           — int 0–100
    $score_label     — string
    $score_color     — string
    $distance_km     — float|null
    $available_slots — Collection of VenueSchedule
    $rank            — int
--}}

@push('styles')
<style>
    .venue-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.2s, transform 0.15s, background 0.15s;
    }

    .venue-card:hover {
        border-color: rgba(163,177,75,0.28);
        transform: translateY(-1px);
    }

    .venue-card.rank-1 { border-color: rgba(163,177,75,0.28); }

    .venue-card-body {
        padding: 1.1rem 1.25rem;
        display: flex; align-items: flex-start; gap: 12px;
    }

    /* Rank */
    .vc-rank {
        width: 26px; height: 26px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 800; font-size: 0.75rem; flex-shrink: 0; margin-top: 2px;
    }

    .vc-rank-1 { background: rgba(251,191,36,0.15); color: #fcd34d; border: 1px solid rgba(251,191,36,0.25); }
    .vc-rank-2 { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.20); }
    .vc-rank-3 { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .vc-rank-n { background: var(--surface-4); color: var(--txt-faint); border: 1px solid var(--border-subtle); }

    /* Venue icon */
    .vc-icon {
        width: 46px; height: 46px; border-radius: 12px;
        background: var(--accent-dim); border: 1.5px solid rgba(163,177,75,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: var(--accent); flex-shrink: 0;
    }

    /* Info */
    .vc-body { flex: 1; min-width: 0; }

    .vc-name {
        font-family: 'Manrope', sans-serif;
        font-size: 0.925rem; font-weight: 700;
        color: var(--txt-primary); margin-bottom: 3px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .vc-meta {
        display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;
    }

    .vc-meta-item {
        font-size: 0.71rem; color: var(--txt-muted);
        display: flex; align-items: center; gap: 3px;
    }

    .vc-meta-item i { font-size: 0.72rem; color: var(--accent); }

    .vc-meta-item.price {
        font-weight: 700; color: var(--txt-secondary);
    }

    /* Available slots strip */
    .vc-slots {
        display: flex; flex-wrap: wrap; gap: 4px;
    }

    .vc-slot-tag {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.66rem; font-weight: 600;
        padding: 2px 8px; border-radius: 99px;
        background: rgba(163,177,75,0.10); color: var(--accent);
        border: 1px solid rgba(163,177,75,0.20);
    }

    .vc-slot-more {
        font-size: 0.66rem; font-weight: 600; color: var(--txt-faint);
        padding: 2px 8px; border-radius: 99px;
        background: var(--surface-4); border: 1px solid var(--border-subtle);
    }

    /* Score block */
    .vc-score-block {
        display: flex; flex-direction: column;
        align-items: flex-end; gap: 5px; flex-shrink: 0;
    }

    .vc-score-ring { position: relative; width: 52px; height: 52px; }
    .vc-score-ring svg { transform: rotate(-90deg); width: 52px; height: 52px; }
    .vc-score-ring-bg   { fill: none; stroke: var(--surface-4); stroke-width: 4; }
    .vc-score-ring-fill { fill: none; stroke-width: 4; stroke-linecap: round; }

    .vc-score-success { stroke: #86efac; }
    .vc-score-accent  { stroke: var(--accent); }
    .vc-score-warning { stroke: #fcd34d; }
    .vc-score-muted   { stroke: var(--txt-faint); }

    .vc-score-num {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif; font-weight: 800;
        font-size: 0.825rem; color: var(--txt-primary);
    }

    .vc-score-label {
        font-size: 0.63rem; font-weight: 700;
        padding: 2px 7px; border-radius: 99px;
        text-align: right; white-space: nowrap;
    }

    .vc-label-success { background: rgba(134,239,172,0.12); color: #86efac; border: 1px solid rgba(134,239,172,0.20); }
    .vc-label-accent  { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .vc-label-warning { background: rgba(251,191,36,0.10); color: #fcd34d; border: 1px solid rgba(251,191,36,0.20); }
    .vc-label-muted   { background: var(--surface-4); color: var(--txt-muted); border: 1px solid var(--border-subtle); }

    /* Card footer */
    .venue-card-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--border-subtle);
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; background: var(--surface-3);
    }

    .vc-distance-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.72rem; font-weight: 600;
        color: var(--txt-secondary);
    }

    .vc-distance-badge i { color: var(--accent); }
</style>
@endpush

@php
    $rankClass = match ($rank) {
        1 => 'vc-rank-1', 2 => 'vc-rank-2', 3 => 'vc-rank-3', default => 'vc-rank-n',
    };

    $circ      = 2 * M_PI * 20;
    $dashOff   = $circ - ($score / 100) * $circ;
    $ringClass = 'vc-score-' . $score_color;
    $lblClass  = 'vc-label-'  . $score_color;

    // Show up to 3 slots
    $shownSlots = $available_slots->take(3);
    $moreCount  = max(0, $available_slots->count() - 3);
@endphp

<div class="venue-card {{ $rank === 1 ? 'rank-1' : '' }}" id="venue-{{ $venue->id }}">

    <div class="venue-card-body">

        {{-- Rank --}}
        <div class="vc-rank {{ $rankClass }}">#{{ $rank }}</div>

        {{-- Icon --}}
        <div class="vc-icon"><i class="bi bi-building"></i></div>

        {{-- Info --}}
        <div class="vc-body">
            <div class="vc-name">{{ $venue->name }}</div>

            <div class="vc-meta">
                @if ($venue->city)
                    <span class="vc-meta-item">
                        <i class="bi bi-geo-alt"></i>
                        {{ $venue->city }}@if($venue->province), {{ $venue->province }}@endif
                    </span>
                @endif
                @if ($venue->price_per_hour)
                    <span class="vc-meta-item price">
                        <i class="bi bi-cash-coin"></i>
                        Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam
                    </span>
                @endif
                @if ($venue->capacity)
                    <span class="vc-meta-item">
                        <i class="bi bi-people"></i>
                        Maks {{ $venue->capacity }} orang
                    </span>
                @endif
                @if ($venue->phone)
                    <span class="vc-meta-item">
                        <i class="bi bi-telephone"></i>
                        {{ $venue->phone }}
                    </span>
                @endif
            </div>

            {{-- Available slots --}}
            @if ($available_slots->count() > 0)
                <div class="vc-slots">
                    @foreach ($shownSlots as $slot)
                        <span class="vc-slot-tag">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($slot->date)->format('d/m') }}
                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                        </span>
                    @endforeach
                    @if ($moreCount > 0)
                        <span class="vc-slot-more">+{{ $moreCount }} slot lagi</span>
                    @endif
                </div>
            @else
                <span style="font-size:0.7rem; color:var(--txt-faint); display:flex; align-items:center; gap:4px;">
                    <i class="bi bi-calendar-x"></i> Jadwal tidak tersedia di tanggal ini
                </span>
            @endif
        </div>

        {{-- Score --}}
        <div class="vc-score-block">
            <div class="vc-score-ring">
                <svg viewBox="0 0 52 52">
                    <circle class="vc-score-ring-bg"   cx="26" cy="26" r="20"/>
                    <circle class="vc-score-ring-fill {{ $ringClass }}"
                        cx="26" cy="26" r="20"
                        stroke-dasharray="{{ $circ }}"
                        stroke-dashoffset="{{ $dashOff }}"
                    />
                </svg>
                <div class="vc-score-num">{{ $score }}</div>
            </div>
            <span class="vc-score-label {{ $lblClass }}">{{ $score_label }}</span>
        </div>

    </div>

    {{-- Footer --}}
    <div class="venue-card-footer">
        <div class="vc-distance-badge">
            @if ($distance_km !== null)
                <i class="bi bi-pin-map"></i> {{ $distance_km }} km dari titik tengah
            @else
                <i class="bi bi-question-circle"></i> Jarak tidak diketahui
            @endif
        </div>
        <div style="display:flex; gap:6px;">
            <a href="{{ route('venues.show', $venue) }}" class="btn-outline-lime btn-sm">
                <i class="bi bi-eye"></i> Detail
            </a>
            @if ($available_slots->count() > 0)
                <a href="{{ route('venues.show', $venue) }}" class="btn-lime btn-sm">
                    <i class="bi bi-calendar-check"></i> Lihat Jadwal
                </a>
            @endif
        </div>
    </div>

</div>