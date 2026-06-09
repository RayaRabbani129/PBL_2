@extends('user.layouts.app')

@section('title', 'Buat Split Bill — MATCHGO')
@section('page-title', 'Buat Split Bill')

@push('styles')
<style>
    /* ── Live preview card ── */
    .preview-card {
        background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,0.25);
        border-radius: 16px;
        padding: 1.5rem;
        position: sticky;
        top: calc(var(--topbar-h) + 24px);
    }

    .preview-title {
        font-family: 'Manrope', sans-serif;
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--accent);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .preview-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(163,177,75,0.12);
    }

    .preview-row:last-child { border-bottom: none; padding-bottom: 0; }

    .preview-label {
        font-size: .8rem;
        color: var(--txt-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .preview-amount {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 1rem;
        color: var(--txt-primary);
    }

    .preview-amount.highlight {
        font-size: 1.35rem;
        color: var(--accent);
    }

    .preview-sub {
        font-size: .7rem;
        color: var(--txt-faint);
        margin-top: 2px;
        text-align: right;
    }

    /* ── Player counter ── */
    .player-counter {
        display: flex;
        align-items: center;
        gap: 0;
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        border-radius: 10px;
        overflow: hidden;
    }

    .counter-btn {
        width: 40px;
        height: 42px;
        background: none;
        border: none;
        color: var(--txt-muted);
        font-size: 1.1rem;
        cursor: pointer;
        transition: background .15s, color .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
    }

    .counter-btn:hover { background: var(--accent-dim); color: var(--accent); }

    .counter-input {
        flex: 1;
        background: none;
        border: none;
        border-left: 1px solid var(--border-subtle);
        border-right: 1px solid var(--border-subtle);
        text-align: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        color: var(--txt-primary);
        outline: none;
        height: 42px;
        width: 52px;
        min-width: 0;
    }

    /* ── Form section header ── */
    .form-section-head {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--txt-faint);
        margin-bottom: 16px;
        margin-top: 28px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border-subtle);
    }

    .form-section-head:first-of-type { margin-top: 0; }

    /* ── Venue cost big input ── */
    .cost-input-wrap {
        position: relative;
    }

    .cost-input-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        font-size: .9rem;
        color: var(--txt-muted);
        pointer-events: none;
    }

    .cost-input {
        padding-left: 42px !important;
        font-family: 'Manrope', sans-serif !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('match-cost.index') }}">Split Bill</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li class="active">Buat Baru</li>
</ul>

{{-- Page header --}}
<div class="page-header">
    <h1><i class="bi bi-plus-circle me-2 text-accent"></i>Buat Split Bill</h1>
    <p>Hitung dan bagikan biaya lapangan futsal secara adil</p>
</div>

<div class="row g-4">
    {{-- Form --}}
    <div class="col-lg-8">
        <div class="card-matchgo">
            <form id="splitBillForm" method="POST" action="{{ route('match-cost.store') }}">
                @csrf

                {{-- Pilih pertandingan --}}
                <div class="form-section-head">
                    <i class="bi bi-calendar-event"></i> Pertandingan
                </div>

                <div class="form-group-mg">
                    <label class="form-label-mg" for="match_id">Pilih Pertandingan</label>
                    <select name="match_id" id="match_id" class="form-control-mg" required>
                        <option value="" disabled selected>— Pilih pertandingan —</option>
                        @foreach($matches as $match)
                            <option value="{{ $match->id }}"
                                {{ old('match_id') == $match->id ? 'selected' : '' }}>
                                {{ $match->title ?? 'Match #' . $match->id }}
                                @if($match->scheduled_at)
                                    — {{ $match->scheduled_at->format('d M Y') }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('match_id')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Biaya lapangan --}}
                <div class="form-section-head">
                    <i class="bi bi-building"></i> Biaya Lapangan
                </div>

                <div class="form-group-mg">
                    <label class="form-label-mg" for="total_venue_cost">Total Biaya Sewa Lapangan (Rp)</label>
                    <div class="cost-input-wrap">
                        <span class="cost-input-prefix">Rp</span>
                        <input
                            type="number"
                            name="total_venue_cost"
                            id="total_venue_cost"
                            class="form-control-mg cost-input"
                            placeholder="0"
                            min="0"
                            step="1000"
                            value="{{ old('total_venue_cost', '') }}"
                            required
                        >
                    </div>
                    @error('total_venue_cost')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jumlah pemain --}}
                <div class="form-section-head">
                    <i class="bi bi-people"></i> Jumlah Pemain
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-mg">🏠 Tim Kandang</label>
                        <div class="player-counter">
                            <button type="button" class="counter-btn" onclick="changeCount('home', -1)">−</button>
                            <input type="number" name="home_team_players" id="home_team_players"
                                   class="counter-input" value="{{ old('home_team_players', 5) }}"
                                   min="1" max="20" readonly>
                            <button type="button" class="counter-btn" onclick="changeCount('home', 1)">+</button>
                        </div>
                        @error('home_team_players')
                            <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-mg">✈️ Tim Tamu</label>
                        <div class="player-counter">
                            <button type="button" class="counter-btn" onclick="changeCount('away', -1)">−</button>
                            <input type="number" name="away_team_players" id="away_team_players"
                                   class="counter-input" value="{{ old('away_team_players', 5) }}"
                                   min="1" max="20" readonly>
                            <button type="button" class="counter-btn" onclick="changeCount('away', 1)">+</button>
                        </div>
                        @error('away_team_players')
                            <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="form-section-head" style="margin-top:28px;">
                    <i class="bi bi-chat-left-text"></i> Catatan (Opsional)
                </div>

                <div class="form-group-mg">
                    <textarea name="notes" id="notes" class="form-control-mg"
                              placeholder="Catatan tambahan tentang pembayaran..."
                              style="min-height:80px;">{{ old('notes') }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="mg-divider"></div>
                <div class="d-flex gap-3 justify-content-end">
                    <a href="{{ route('match-cost.index') }}" class="btn-outline-lime">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn-lime">
                        <i class="bi bi-check-lg"></i> Simpan Split Bill
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Live Preview --}}
    <div class="col-lg-4">
        <div class="preview-card">
            <div class="preview-title">
                <i class="bi bi-lightning-charge-fill"></i>
                Preview Kalkulasi
            </div>

            <div class="preview-row">
                <div>
                    <div class="preview-label"><i class="bi bi-building"></i> Total Biaya</div>
                </div>
                <div>
                    <div class="preview-amount highlight" id="prev-total">Rp 0</div>
                </div>
            </div>

            <div class="preview-row">
                <div>
                    <div class="preview-label"><i class="bi bi-shield-fill"></i> Tim Kandang</div>
                    <div class="preview-sub" id="prev-home-players">5 pemain</div>
                </div>
                <div style="text-align:right;">
                    <div class="preview-amount" id="prev-home-team">Rp 0</div>
                    <div class="preview-sub" id="prev-home-per">Rp 0 / orang</div>
                </div>
            </div>

            <div class="preview-row">
                <div>
                    <div class="preview-label"><i class="bi bi-shield"></i> Tim Tamu</div>
                    <div class="preview-sub" id="prev-away-players">5 pemain</div>
                </div>
                <div style="text-align:right;">
                    <div class="preview-amount" id="prev-away-team">Rp 0</div>
                    <div class="preview-sub" id="prev-away-per">Rp 0 / orang</div>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 12px; background: var(--surface-3); border-radius: 10px; font-size:.78rem; color:var(--txt-muted); line-height:1.6;">
                <i class="bi bi-info-circle me-1 text-accent"></i>
                Biaya dibagi <strong style="color:var(--txt-primary);">50/50</strong> antara kedua tim. Biaya per pemain dihitung berdasarkan jumlah pemain masing-masing tim.
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

    function recalc() {
        const total = parseFloat(document.getElementById('total_venue_cost').value) || 0;
        const home  = parseInt(document.getElementById('home_team_players').value)  || 1;
        const away  = parseInt(document.getElementById('away_team_players').value)  || 1;

        const teamCost = total / 2;
        const homePer  = teamCost / home;
        const awayPer  = teamCost / away;

        document.getElementById('prev-total').textContent       = fmt(total);
        document.getElementById('prev-home-team').textContent   = fmt(teamCost);
        document.getElementById('prev-away-team').textContent   = fmt(teamCost);
        document.getElementById('prev-home-per').textContent    = fmt(homePer) + ' / orang';
        document.getElementById('prev-away-per').textContent    = fmt(awayPer) + ' / orang';
        document.getElementById('prev-home-players').textContent = home + ' pemain';
        document.getElementById('prev-away-players').textContent = away + ' pemain';
    }

    function changeCount(team, delta) {
        const el = document.getElementById(team + '_team_players');
        const val = parseInt(el.value) + delta;
        if (val >= 1 && val <= 20) { el.value = val; recalc(); }
    }

    document.getElementById('total_venue_cost').addEventListener('input', recalc);
    recalc();
</script>
@endpush