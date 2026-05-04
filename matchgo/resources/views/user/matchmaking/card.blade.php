{{-- resources/views/user/matchmaking/_team-card.blade.php --}}
{{--
    Variables:
    $team, $score, $score_label, $score_color,
    $match_reasons, $overlap_slots, $rank
--}}

@php
    $rankClass = match ($rank) {
        1 => 'mm-rank-1', 2 => 'mm-rank-2', 3 => 'mm-rank-3', default => 'mm-rank-n'
    };

    $circumference = 2 * M_PI * 21;
    $dashOffset    = $circumference - ($score / 100) * $circumference;
    $ringClass     = 'score-' . $score_color;
    $labelClass    = 'mm-score-label-' . $score_color;

    $dayNames      = [0=>'Min',1=>'Sen',2=>'Sel',3=>'Rab',4=>'Kam',5=>'Jum',6=>'Sab'];
    $teamAvailDays = $team->schedules
        ->where('is_available', true)
        ->sortBy('day_of_week')
        ->map(fn($s) => $dayNames[$s->day_of_week] ?? '?')
        ->values()->join(', ');

    $teamMeta = implode(' • ', array_filter([
        $team->level ? ucfirst(str_replace('_',' ',$team->level)) : null,
        $team->city  ?? null,
    ]));
@endphp

<div class="mm-team-card {{ $rank === 1 ? 'rank-1' : '' }}">

    <div class="mm-rank {{ $rankClass }}">#{{ $rank }}</div>

    <div class="mm-card-avatar">{{ strtoupper(substr($team->name, 0, 2)) }}</div>

    <div class="mm-card-body">
        <div class="mm-card-team-name">{{ $team->name }}</div>

        <div class="mm-card-meta">
            @if ($team->level)
                <span class="mm-card-meta-item">
                    <i class="bi bi-trophy"></i>
                    {{ ucfirst(str_replace('_', ' ', $team->level)) }}
                </span>
            @endif
            @if ($team->city)
                <span class="mm-card-meta-item">
                    <i class="bi bi-geo-alt"></i>{{ $team->city }}
                </span>
            @endif
            @if ($teamAvailDays)
                <span class="mm-card-meta-item">
                    <i class="bi bi-calendar2"></i>{{ $teamAvailDays }}
                </span>
            @endif
        </div>

        @if (!empty($overlap_slots))
            <div class="mm-card-slots">
                @foreach (array_slice($overlap_slots, 0, 3) as $slot)
                    <span class="mm-slot-tag">
                        <i class="bi bi-clock-fill"></i> {{ $slot }}
                    </span>
                @endforeach
                @if (count($overlap_slots) > 3)
                    <span class="mm-slot-tag" style="opacity:0.6;">
                        +{{ count($overlap_slots) - 3 }} lagi
                    </span>
                @endif
            </div>
        @endif

        @if (!empty($match_reasons))
            <div class="mm-card-reasons">
                @foreach ($match_reasons as $reason)
                    <span class="mm-reason-tag">
                        <i class="bi {{ $reason['icon'] }}"></i>{{ $reason['text'] }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Score + tombol tantang --}}
    <div class="mm-score-block">
        <div class="mm-score-ring">
            <svg viewBox="0 0 54 54">
                <circle class="mm-score-ring-bg"   cx="27" cy="27" r="21"/>
                <circle class="mm-score-ring-fill {{ $ringClass }}"
                    cx="27" cy="27" r="21"
                    stroke-dasharray="{{ $circumference }}"
                    stroke-dashoffset="{{ $dashOffset }}"
                />
            </svg>
            <div class="mm-score-number">{{ $score }}</div>
        </div>

        <span class="mm-score-label {{ $labelClass }}">{{ $score_label }}</span>

        {{--
            Semua data lawan dikirim via data-* ke JS di index.blade.php
            Tidak ada form / modal di sini sama sekali
        --}}
        <button
            type="button"
            class="mm-challenge-btn js-open-challenge"
            data-action="{{ route('matchmaking.challenge', $team) }}"
            data-name="{{ $team->name }}"
            data-initials="{{ strtoupper(substr($team->name, 0, 2)) }}"
            data-meta="{{ $teamMeta }}"
        >
            <i class="bi bi-lightning-charge-fill"></i> Tantang
        </button>
    </div>
</div>