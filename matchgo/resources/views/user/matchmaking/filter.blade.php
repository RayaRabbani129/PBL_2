{{-- resources/views/user/matchmaking/_filter.blade.php --}}
{{--
    Variables:
    $filters     — array filter aktif
    $mySchedules — Collection jadwal saya (is_available = true)
--}}

@push('styles')
<style>
    .mm-filter-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 18px;
        overflow: hidden;
        position: sticky;
        top: calc(var(--topbar-h) + 16px);
    }

    .mm-filter-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mm-filter-title {
        font-family: 'Manrope', sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--txt-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mm-filter-title i { color: var(--accent); }

    .mm-filter-reset {
        font-size: 0.73rem;
        color: var(--txt-muted);
        text-decoration: none;
        transition: color 0.15s;
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .mm-filter-reset:hover { color: var(--accent); }

    .mm-filter-body {
        padding: 1.1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .mm-filter-section-label {
        font-size: 0.63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.10em;
        color: var(--txt-faint);
        margin-bottom: 8px;
    }

    /* ── My Schedule Toggle ── */
    .mm-sched-toggle-wrap {
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        border-radius: 12px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
    }

    .mm-sched-toggle-wrap:has(input:checked) {
        border-color: var(--accent);
        background: var(--accent-dim);
    }

    .mm-sched-toggle-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: var(--accent-dim);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        color: var(--accent);
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .mm-sched-toggle-wrap:has(input:checked) .mm-sched-toggle-icon {
        background: rgba(163,177,75,0.25);
    }

    .mm-sched-toggle-text { flex: 1; min-width: 0; }

    .mm-sched-toggle-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--txt-primary);
        line-height: 1.2;
    }

    .mm-sched-toggle-sub {
        font-size: 0.68rem;
        color: var(--txt-muted);
        margin-top: 1px;
    }

    /* toggle switch */
    .mm-sched-toggle-switch {
        position: relative;
        width: 34px; height: 20px;
        flex-shrink: 0;
    }

    .mm-sched-toggle-switch input { position: absolute; opacity: 0; width: 0; height: 0; }

    .mm-sched-switch-track {
        display: block;
        width: 34px; height: 20px;
        border-radius: 99px;
        background: var(--surface-5);
        border: 1px solid var(--border-medium);
        transition: background 0.2s, border-color 0.2s;
        position: relative;
    }

    .mm-sched-switch-track::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: var(--txt-faint);
        transition: transform 0.2s, background 0.2s;
    }

    .mm-sched-toggle-switch input:checked + .mm-sched-switch-track {
        background: var(--accent-dim);
        border-color: var(--accent);
    }

    .mm-sched-toggle-switch input:checked + .mm-sched-switch-track::after {
        transform: translateX(14px);
        background: var(--accent);
    }

    /* ── My Schedule Days Preview (di dalam filter) ── */
    .mm-my-sched-preview {
        background: var(--surface-3);
        border: 1px solid var(--border-subtle);
        border-radius: 11px;
        padding: 10px 12px;
    }

    .mm-my-sched-preview-title {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--txt-faint);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mm-my-sched-preview-title a {
        color: var(--accent);
        text-decoration: none;
        font-size: 0.68rem;
        font-weight: 600;
    }

    .mm-my-sched-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
    }

    .mm-sched-day-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 5px 2px;
        border-radius: 7px;
        border: 1px solid transparent;
    }

    .mm-sched-day-cell.has-sched {
        background: var(--accent-dim);
        border-color: rgba(163,177,75,0.20);
    }

    .mm-sched-day-cell.no-sched {
        background: var(--surface-4);
        border-color: var(--border-subtle);
        opacity: 0.5;
    }

    .mm-sched-day-name {
        font-size: 0.6rem;
        font-weight: 700;
        color: var(--txt-faint);
        line-height: 1.2;
    }

    .mm-sched-day-cell.has-sched .mm-sched-day-name { color: var(--accent); }

    .mm-sched-day-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: var(--surface-5);
        margin-top: 3px;
    }

    .mm-sched-day-cell.has-sched .mm-sched-day-dot { background: var(--accent); }

    /* ── Level Chips ── */
    .mm-level-chips {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .mm-level-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 11px;
        border-radius: 10px;
        border: 1px solid var(--border-medium);
        background: var(--surface-3);
        cursor: pointer;
        transition: all 0.15s;
        position: relative;
    }

    .mm-level-chip:hover {
        border-color: rgba(163,177,75,0.30);
        background: var(--accent-dim);
    }

    .mm-level-chip input[type="radio"] {
        position: absolute; opacity: 0; width: 0; height: 0;
    }

    .mm-level-chip.selected {
        border-color: var(--accent);
        background: var(--accent-dim);
    }

    .mm-level-chip-dot {
        width: 14px; height: 14px;
        border-radius: 50%;
        border: 2px solid var(--border-strong);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: border-color 0.15s;
    }

    .mm-level-chip.selected .mm-level-chip-dot {
        border-color: var(--accent);
        background: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-dim);
    }

    .mm-level-chip.selected .mm-level-chip-dot::after {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        background: var(--btn-primary-txt);
    }

    .mm-level-chip-info { flex: 1; min-width: 0; }

    .mm-level-chip-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--txt-primary);
        line-height: 1.2;
    }

    .mm-level-chip-desc {
        font-size: 0.68rem;
        color: var(--txt-muted);
    }

    .mm-level-chip-badge {
        font-size: 0.63rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 99px;
    }

    .mm-badge-casual { background: rgba(34,211,238,0.12); color: #67e8f9; border: 1px solid rgba(34,211,238,0.20); }
    .mm-badge-semi   { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .mm-badge-pro    { background: rgba(251,191,36,0.12); color: #fcd34d; border: 1px solid rgba(251,191,36,0.20); }

    /* ── Day Pills (manual) ── */
    .mm-day-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .mm-day-pill {
        position: relative;
        padding: 5px 9px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid var(--border-medium);
        background: var(--surface-3);
        color: var(--txt-muted);
        cursor: pointer;
        transition: all 0.15s;
        user-select: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px;
    }

    .mm-day-pill.my-day {
        border-color: rgba(163,177,75,0.25);
        color: var(--txt-secondary);
    }

    .mm-day-pill:hover {
        border-color: rgba(163,177,75,0.35);
        color: var(--txt-primary);
    }

    .mm-day-pill.selected {
        background: var(--accent-dim);
        border-color: var(--accent);
        color: var(--accent);
    }

    .mm-day-pill input[type="radio"] {
        position: absolute; opacity: 0; width: 0; height: 0;
    }

    .mm-day-my-dot {
        width: 4px; height: 4px;
        border-radius: 50%;
        background: var(--accent);
        opacity: 0.6;
    }

    /* ── Range Slider ── */
    .mm-range-wrap { position: relative; }

    .mm-range-value {
        position: absolute;
        right: 0; top: -2px;
        font-size: 0.775rem;
        font-weight: 700;
        color: var(--accent);
        font-family: 'Manrope', sans-serif;
    }

    input[type="range"].mm-range {
        -webkit-appearance: none;
        width: 100%;
        height: 4px;
        border-radius: 99px;
        background: var(--surface-5);
        outline: none;
        margin-top: 20px;
        cursor: pointer;
    }

    input[type="range"].mm-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 18px; height: 18px;
        border-radius: 50%;
        background: var(--accent);
        border: 3px solid var(--surface-1);
        box-shadow: 0 0 0 2px var(--accent);
        cursor: pointer;
        transition: box-shadow 0.15s;
    }

    input[type="range"].mm-range::-webkit-slider-thumb:hover {
        box-shadow: 0 0 0 5px var(--accent-dim);
    }

    .mm-range-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.63rem;
        color: var(--txt-faint);
        margin-top: 5px;
    }

    /* ── Search button ── */
    .mm-search-btn {
        width: 100%;
        padding: 11px;
        border-radius: 11px;
        background: var(--accent);
        color: var(--btn-primary-txt);
        font-weight: 700;
        font-size: 0.875rem;
        font-family: 'Manrope', sans-serif;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.15s, transform 0.15s;
        letter-spacing: 0.01em;
    }

    .mm-search-btn:hover  { background: var(--accent-hover); transform: translateY(-1px); }
    .mm-search-btn:active { transform: scale(0.98); }
</style>
@endpush

@php
    $dayNamesShort = [0 => 'Min', 1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab'];
    $myAvailDays   = $mySchedules->pluck('day_of_week')->toArray();
@endphp

<div class="mm-filter-card">

    {{-- Header --}}
    <div class="mm-filter-header">
        <span class="mm-filter-title">
            <i class="bi bi-sliders2-vertical"></i> Filter
        </span>
        <a href="{{ route('matchmaking.index') }}" class="mm-filter-reset">
            Reset <i class="bi bi-arrow-counterclockwise"></i>
        </a>
    </div>

    <form action="{{ route('matchmaking.index') }}" method="POST" id="matchmakingForm">
        @csrf
        <input type="hidden" name="search" value="1">

        <div class="mm-filter-body">

            {{-- ① Jadwal Saya (preview + toggle) ── --}}
            <div>
                <div class="mm-filter-section-label">Jadwal Saya</div>

                {{-- Mini weekly grid --}}
                <div class="mm-my-sched-preview" style="margin-bottom:8px;">
                    <div class="mm-my-sched-preview-title">
                        <span>Ketersediaan minggu ini</span>
                        <a href="{{ route('schedule.index') }}">Edit <i class="bi bi-pencil"></i></a>
                    </div>
                    <div class="mm-my-sched-grid">
                        @foreach ($dayNamesShort as $dayVal => $dayShort)
                            @php $hasSched = in_array($dayVal, $myAvailDays); @endphp
                            <div class="mm-sched-day-cell {{ $hasSched ? 'has-sched' : 'no-sched' }}" title="{{ $hasSched ? 'Tersedia' : 'Tidak tersedia' }}">
                                <span class="mm-sched-day-name">{{ $dayShort }}</span>
                                <span class="mm-sched-day-dot"></span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Toggle: filter berdasarkan jadwalku --}}
                <label class="mm-sched-toggle-wrap">
                    <div class="mm-sched-toggle-icon"><i class="bi bi-calendar-check-fill"></i></div>
                    <div class="mm-sched-toggle-text">
                        <div class="mm-sched-toggle-title">Filter by Jadwalku</div>
                        <div class="mm-sched-toggle-sub">Hanya tampilkan tim yang cocok harinya</div>
                    </div>
                    <div class="mm-sched-toggle-switch">
                        <input
                            type="checkbox"
                            name="use_my_schedule"
                            value="1"
                            {{ ($filters['use_my_schedule'] ?? false) ? 'checked' : '' }}
                            @if($mySchedules->isEmpty()) disabled @endif
                        >
                        <span class="mm-sched-switch-track"></span>
                    </div>
                </label>

                @if($mySchedules->isEmpty())
                    <div style="font-size:0.72rem; color:var(--txt-faint); margin-top:6px; display:flex; align-items:center; gap:5px;">
                        <i class="bi bi-info-circle"></i>
                        <span>Tambahkan jadwal dulu untuk menggunakan fitur ini. <a href="{{ route('schedule.create') }}" style="color:var(--accent);">Tambah</a></span>
                    </div>
                @endif
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- ② Level --}}
            <div>
                <div class="mm-filter-section-label">Level Tim</div>
                <div class="mm-level-chips">
                    @foreach ([
                        ['value' => '',        'name' => 'Semua Level', 'desc' => 'Tampilkan semua tim',            'badge' => null,       'badge_class' => ''],
                        ['value' => 'casual',  'name' => 'Casual',      'desc' => 'Bermain untuk bersenang-senang', 'badge' => 'Casual',   'badge_class' => 'mm-badge-casual'],
                        ['value' => 'semi_pro','name' => 'Semi Pro',    'desc' => 'Kompetitif tapi santai',         'badge' => 'Semi Pro', 'badge_class' => 'mm-badge-semi'],
                        ['value' => 'pro',     'name' => 'Pro',         'desc' => 'Kompetisi serius',               'badge' => 'Pro',      'badge_class' => 'mm-badge-pro'],
                    ] as $opt)
                        @php $isSelected = ($filters['level'] ?? '') === $opt['value']; @endphp
                        <label class="mm-level-chip {{ $isSelected ? 'selected' : '' }}">
                            <input
                                type="radio"
                                name="level"
                                value="{{ $opt['value'] }}"
                                {{ $isSelected ? 'checked' : '' }}
                                onchange="
                                    this.closest('form').querySelectorAll('.mm-level-chip').forEach(el => el.classList.remove('selected'));
                                    this.closest('.mm-level-chip').classList.add('selected');
                                "
                            >
                            <span class="mm-level-chip-dot"></span>
                            <span class="mm-level-chip-info">
                                <span class="mm-level-chip-name">{{ $opt['name'] }}</span>
                                <span class="mm-level-chip-desc">{{ $opt['desc'] }}</span>
                            </span>
                            @if ($opt['badge'])
                                <span class="mm-level-chip-badge {{ $opt['badge_class'] }}">{{ $opt['badge'] }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- ③ Hari spesifik — tandai hari yang saya punya jadwal --}}
            <div>
                <div class="mm-filter-section-label" style="display:flex; align-items:center; justify-content:space-between;">
                    <span>Hari Tertentu</span>
                    @if (!empty($myAvailDays))
                        <span style="font-size:0.6rem; color:var(--accent); font-weight:600;">
                            <i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> = hari jadwalku
                        </span>
                    @endif
                </div>
                <div class="mm-day-pills">
                    @foreach (['' => 'Semua'] + $dayNamesShort as $val => $label)
                        @php
                            $isSelected = (string)($filters['day_of_week'] ?? '') === (string)$val;
                            if ($val === '' && !isset($filters['day_of_week'])) $isSelected = true;
                            $isMyDay = $val !== '' && in_array((int)$val, $myAvailDays);
                        @endphp
                        <label class="mm-day-pill {{ $isSelected ? 'selected' : '' }} {{ $isMyDay ? 'my-day' : '' }}" style="position:relative;">
                            <input
                                type="radio"
                                name="day_of_week"
                                value="{{ $val }}"
                                {{ $isSelected ? 'checked' : '' }}
                                onchange="
                                    this.closest('.mm-filter-body').querySelectorAll('.mm-day-pill').forEach(el => el.classList.remove('selected'));
                                    this.closest('.mm-day-pill').classList.add('selected');
                                "
                            >
                            {{ $label }}
                            @if ($isMyDay)
                                <span class="mm-day-my-dot"></span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- ④ Jarak --}}
            <div>
                <div class="mm-filter-section-label">Jarak Maksimal</div>
                <div class="mm-range-wrap">
                    <span class="mm-range-value" id="distanceLabel">{{ $filters['max_distance'] ?? 25 }} km</span>
                    <input
                        type="range"
                        name="max_distance"
                        id="distanceRange"
                        class="mm-range"
                        min="1" max="100"
                        value="{{ $filters['max_distance'] ?? 25 }}"
                        oninput="document.getElementById('distanceLabel').textContent = this.value + ' km'"
                    >
                    <div class="mm-range-labels">
                        <span>1 km</span><span>50 km</span><span>100 km</span>
                    </div>
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- Submit --}}
            <button type="submit" class="mm-search-btn">
                <i class="bi bi-search"></i> Cari Lawan Sekarang
            </button>

        </div>
    </form>
</div>