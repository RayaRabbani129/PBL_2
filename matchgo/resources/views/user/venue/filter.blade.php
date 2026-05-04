{{-- resources/views/user/venue/_filter.blade.php --}}
{{--
    $filters      — array filter aktif
    $myTeam       — Team model saya
    $opponentTeam — Team|null lawan (dari matchmaking)
--}}

@push('styles')
<style>
    .venue-filter-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 18px;
        overflow: hidden;
        position: sticky;
        top: calc(var(--topbar-h) + 16px);
    }

    .venue-filter-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex; align-items: center; justify-content: space-between;
    }

    .venue-filter-title {
        font-family: 'Manrope', sans-serif;
        font-size: 0.875rem; font-weight: 700;
        color: var(--txt-primary);
        display: flex; align-items: center; gap: 8px;
    }

    .venue-filter-title i { color: var(--accent); }

    .venue-filter-reset {
        font-size: 0.73rem; color: var(--txt-muted);
        text-decoration: none; transition: color 0.15s;
        background: none; border: none; cursor: pointer;
        font-family: 'Inter', sans-serif; padding: 0;
        display: flex; align-items: center; gap: 4px;
    }

    .venue-filter-reset:hover { color: var(--accent); }

    .venue-filter-body {
        padding: 1.1rem 1.25rem;
        display: flex; flex-direction: column; gap: 1rem;
    }

    .venue-filter-label {
        font-size: 0.63rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.10em;
        color: var(--txt-faint); margin-bottom: 7px;
    }

    /* ── Opponent context ── */
    .venue-opponent-ctx {
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        border-radius: 11px;
        padding: 10px 13px;
    }

    .venue-opponent-ctx-label {
        font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--txt-faint); margin-bottom: 6px;
    }

    .venue-opponent-row {
        display: flex; align-items: center; gap: 8px;
    }

    .venue-opponent-av {
        width: 28px; height: 28px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif; font-weight: 800;
        font-size: 0.7rem; flex-shrink: 0;
    }

    .venue-opponent-av.mine { background: var(--accent-dim); color: var(--accent); }
    .venue-opponent-av.opp  { background: rgba(103,232,249,0.12); color: #67e8f9; }

    .venue-opp-name {
        font-size: 0.8rem; font-weight: 600; color: var(--txt-primary); flex: 1; min-width: 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .venue-opp-city { font-size: 0.68rem; color: var(--txt-muted); }

    .venue-vs-arrow {
        color: var(--txt-faint); font-size: 0.75rem; text-align: center; padding: 3px 0;
    }

    /* ── Sort pills ── */
    .venue-sort-pills {
        display: flex; gap: 5px;
    }

    .venue-sort-pill {
        position: relative;
        flex: 1; padding: 7px 5px; border-radius: 9px;
        font-size: 0.72rem; font-weight: 600;
        border: 1px solid var(--border-medium);
        background: var(--surface-3); color: var(--txt-muted);
        cursor: pointer; transition: all 0.15s; text-align: center;
        user-select: none;
    }

    .venue-sort-pill:hover { border-color: rgba(163,177,75,0.30); color: var(--txt-primary); }
    .venue-sort-pill.selected { background: var(--accent-dim); border-color: var(--accent); color: var(--accent); }
    .venue-sort-pill input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

    /* ── Range ── */
    .vf-range-wrap { position: relative; }
    .vf-range-val {
        position: absolute; right: 0; top: -2px;
        font-size: 0.775rem; font-weight: 700;
        color: var(--accent); font-family: 'Manrope', sans-serif;
    }
    input[type="range"].vf-range {
        -webkit-appearance: none; width: 100%; height: 4px;
        border-radius: 99px; background: var(--surface-5);
        outline: none; margin-top: 18px; cursor: pointer;
    }
    input[type="range"].vf-range::-webkit-slider-thumb {
        -webkit-appearance: none; width: 18px; height: 18px;
        border-radius: 50%; background: var(--accent);
        border: 3px solid var(--surface-1); box-shadow: 0 0 0 2px var(--accent);
        cursor: pointer;
    }
    .vf-range-labels {
        display: flex; justify-content: space-between;
        font-size: 0.63rem; color: var(--txt-faint); margin-top: 5px;
    }

    /* ── Price input ── */
    .vf-price-wrap { position: relative; }
    .vf-price-prefix {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        font-size: 0.75rem; color: var(--txt-muted); pointer-events: none;
        font-weight: 600;
    }
    .vf-price-input {
        width: 100%;
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        border-radius: 10px;
        padding: 9px 12px 9px 36px;
        font-size: 0.875rem; color: var(--txt-primary);
        outline: none; font-family: 'Inter', sans-serif;
        transition: border-color 0.2s;
    }
    .vf-price-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-dim); }
    .vf-price-input::placeholder { color: var(--txt-faint); }

    /* ── Search button ── */
    .venue-search-btn {
        width: 100%; padding: 11px; border-radius: 11px;
        background: var(--accent); color: var(--btn-primary-txt);
        font-weight: 700; font-size: 0.875rem;
        font-family: 'Manrope', sans-serif;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background 0.15s, transform 0.15s;
    }
    .venue-search-btn:hover  { background: var(--accent-hover); transform: translateY(-1px); }
    .venue-search-btn:active { transform: scale(0.98); }
</style>
@endpush

<div class="venue-filter-card">

    <div class="venue-filter-header">
        <span class="venue-filter-title">
            <i class="bi bi-sliders2-vertical"></i> Filter
        </span>
        <a href="{{ route('venues.index') }}" class="venue-filter-reset">
            Reset <i class="bi bi-arrow-counterclockwise"></i>
        </a>
    </div>

    <form action="{{ route('venues.index') }}" method="POST">
        @csrf
        <input type="hidden" name="search" value="1">
        @if ($opponentTeam)
            <input type="hidden" name="opponent_id" value="{{ $opponentTeam->id }}">
        @endif

        <div class="venue-filter-body">

            {{-- ① Konteks Tim ── --}}
            <div>
                <div class="venue-filter-label">Konteks Pencarian</div>
                <div class="venue-opponent-ctx">
                    <div class="venue-opponent-ctx-label">Titik tengah antara</div>

                    <div class="venue-opponent-row">
                        <div class="venue-opponent-av mine">{{ strtoupper(substr($myTeam->name, 0, 2)) }}</div>
                        <div>
                            <div class="venue-opp-name">{{ $myTeam->name }}</div>
                            <div class="venue-opp-city"><i class="bi bi-geo-alt" style="color:var(--accent);"></i> {{ $myTeam->city ?? 'Lokasi belum diset' }}</div>
                        </div>
                    </div>

                    @if ($opponentTeam)
                        <div class="venue-vs-arrow"><i class="bi bi-arrow-down-up"></i></div>
                        <div class="venue-opponent-row">
                            <div class="venue-opponent-av opp">{{ strtoupper(substr($opponentTeam->name, 0, 2)) }}</div>
                            <div>
                                <div class="venue-opp-name">{{ $opponentTeam->name }}</div>
                                <div class="venue-opp-city"><i class="bi bi-geo-alt" style="color:#67e8f9;"></i> {{ $opponentTeam->city ?? '—' }}</div>
                            </div>
                        </div>
                    @else
                        <div style="margin-top:8px; font-size:0.7rem; color:var(--txt-faint); display:flex; align-items:center; gap:5px;">
                            <i class="bi bi-info-circle"></i>
                            Tambahkan lawan via matchmaking untuk hitung titik tengah
                        </div>
                    @endif
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- ② Tanggal ── --}}
            <div>
                <div class="venue-filter-label">Tanggal Main</div>
                <input
                    type="date"
                    name="date"
                    class="form-control-mg"
                    value="{{ $filters['date'] ?? '' }}"
                    min="{{ today()->format('Y-m-d') }}"
                    style="color-scheme: dark;"
                >
                [data-theme="light"] & { style="color-scheme: light;" }
            </div>

            {{-- ③ Waktu ── --}}
            <div>
                <div class="venue-filter-label">Rentang Waktu</div>
                <div class="row g-3" style="margin:0;">
                    <div class="col-md-6" style="padding:0 4px 0 0;">
                        <label class="form-label-mg" style="font-size:0.65rem;">Mulai</label>
                        <input type="time" name="start_time" class="form-control-mg"
                               value="{{ $filters['start_time'] ?? '' }}"
                               style="color-scheme:dark;">
                    </div>
                    <div class="col-md-6" style="padding:0 0 0 4px;">
                        <label class="form-label-mg" style="font-size:0.65rem;">Selesai</label>
                        <input type="time" name="end_time" class="form-control-mg"
                               value="{{ $filters['end_time'] ?? '' }}"
                               style="color-scheme:dark;">
                    </div>
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- ④ Jarak ── --}}
            <div>
                <div class="venue-filter-label">Jarak Maksimal dari Titik Tengah</div>
                <div class="vf-range-wrap">
                    <span class="vf-range-val" id="venueDistLabel">{{ $filters['max_distance'] ?? 20 }} km</span>
                    <input type="range" name="max_distance" id="venueDistRange" class="vf-range"
                           min="1" max="100" value="{{ $filters['max_distance'] ?? 20 }}"
                           oninput="document.getElementById('venueDistLabel').textContent = this.value + ' km'">
                    <div class="vf-range-labels"><span>1 km</span><span>50 km</span><span>100 km</span></div>
                </div>
            </div>

            {{-- ⑤ Harga maks ── --}}
            <div>
                <div class="venue-filter-label">Harga Maks per Jam</div>
                <div class="vf-price-wrap">
                    <span class="vf-price-prefix">Rp</span>
                    <input
                        type="number"
                        name="max_price"
                        class="vf-price-input"
                        placeholder="Cth: 200000"
                        value="{{ $filters['max_price'] ?? '' }}"
                        min="0" step="10000"
                    >
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            {{-- ⑥ Sort ── --}}
            <div>
                <div class="venue-filter-label">Urutkan Berdasarkan</div>
                <div class="venue-sort-pills">
                    @foreach (['score' => '⭐ Skor', 'distance' => '📍 Terdekat', 'price' => '💰 Termurah'] as $val => $label)
                        @php $sel = ($filters['sort_by'] ?? 'score') === $val; @endphp
                        <label class="venue-sort-pill {{ $sel ? 'selected' : '' }}">
                            <input type="radio" name="sort_by" value="{{ $val }}"
                                   {{ $sel ? 'checked' : '' }}
                                   onchange="this.closest('.venue-sort-pills').querySelectorAll('.venue-sort-pill').forEach(e=>e.classList.remove('selected')); this.closest('.venue-sort-pill').classList.add('selected')">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mg-divider" style="margin:0;"></div>

            <button type="submit" class="venue-search-btn">
                <i class="bi bi-geo-alt-fill"></i> Cari Lapangan
            </button>

        </div>
    </form>

</div>