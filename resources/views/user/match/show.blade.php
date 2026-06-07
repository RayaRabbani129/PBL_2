{{-- resources/views/user/matches/show.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Detail Match — MATCHGO')
@section('page-title', 'Detail Match')

@push('styles')
<style>
    /* ── Layout ── */
    .match-detail-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .match-detail-grid { grid-template-columns: 1fr; }
    }

    /* ── Match header card ── */
    .match-header-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .match-header-top {
        position: relative;
        padding: 2rem 2rem 1.5rem;
        border-bottom: 1px solid var(--border-subtle);
        overflow: hidden;
    }

    .match-header-top::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at top, var(--accent-dim) 0%, transparent 60%);
        pointer-events: none;
    }

    .match-header-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(var(--border-subtle) 1px, transparent 1px),
            linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
        background-size: 28px 28px;
        opacity: 0.3; pointer-events: none;
    }

    .match-header-content { position: relative; z-index: 1; }

    /* VS panel */
    .match-vs-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 1.25rem;
    }

    .match-team-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        min-width: 100px;
    }

    .match-team-big-avatar {
        width: 64px; height: 64px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 800; font-size: 1.35rem;
    }

    .match-team-big-avatar.mine {
        background: var(--accent-dim);
        border: 2px solid rgba(163,177,75,0.35);
        color: var(--accent);
    }

    .match-team-big-avatar.opp {
        background: var(--surface-4);
        border: 2px solid var(--border-medium);
        color: var(--txt-muted);
    }

    .match-team-big-name {
        font-family: 'Manrope', sans-serif;
        font-size: 0.9rem; font-weight: 700;
        color: var(--txt-primary); text-align: center;
        max-width: 120px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap;
    }

    .match-team-big-label {
        font-size: 0.62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--txt-faint);
    }

    /* VS / Score centre */
    .match-vs-centre {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .match-big-score {
        font-family: 'Manrope', sans-serif;
        font-size: 2.5rem; font-weight: 900;
        color: var(--txt-primary);
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .match-big-score .sep { color: var(--txt-faint); margin: 0 6px; font-size: 1.8rem; }
    .match-big-score .win  { color: var(--accent); }
    .match-big-score .lose { color: #f87171; }

    .match-vs-label {
        font-family: 'Manrope', sans-serif;
        font-size: 0.8rem; font-weight: 800;
        color: var(--txt-faint);
        letter-spacing: 0.12em;
    }

    .match-result-label {
        font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.06em;
    }

    /* Header bottom: meta row */
    .match-header-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
        padding: 1rem 2rem;
    }

    .match-header-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: var(--txt-secondary);
    }

    .match-header-meta-item i {
        width: 18px; height: 18px;
        border-radius: 6px;
        background: var(--accent-dim);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent); font-size: 0.65rem; flex-shrink: 0;
    }

    /* ── Info sections ── */
    .detail-section {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .detail-section-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex; align-items: center; gap: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 0.85rem; font-weight: 700;
        color: var(--txt-primary);
    }

    .detail-section-header i { color: var(--accent); }

    .detail-section-body { padding: 1.1rem 1.25rem; }

    .detail-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-subtle);
        font-size: 0.825rem;
    }

    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-row:first-child { padding-top: 0; }

    .detail-row-label { color: var(--txt-muted); font-weight: 500; flex-shrink: 0; }
    .detail-row-val   { color: var(--txt-primary); font-weight: 600; text-align: right; }

    .payment-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .payment-card-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .payment-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--txt-primary);
    }

    .payment-card-title i { color: var(--accent); }

    .payment-card-badge {
        padding: 5px 9px;
        border-radius: 999px;
        background: var(--surface-4);
        border: 1px solid var(--border-subtle);
        color: var(--txt-muted);
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
    }

    .payment-card-body { padding: 1.15rem 1.25rem 1.25rem; }

    .payment-total-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 14px;
        align-items: center;
        padding: 0 0 1rem;
        border-bottom: 1px solid var(--border-subtle);
        margin-bottom: 1rem;
    }

    .payment-total-label {
        color: var(--txt-muted);
        font-size: 0.78rem;
        font-weight: 700;
    }

    .payment-total-value {
        font-family: 'Manrope', sans-serif;
        color: var(--accent);
        font-size: 1.45rem;
        font-weight: 900;
        text-align: right;
    }

    .payment-breakdown,
    .payment-team-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .payment-breakdown { margin-bottom: 1rem; }

    .payment-mini-card,
    .payment-team-card {
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        padding: 0.85rem;
        min-width: 0;
    }

    .payment-mini-card { background: var(--surface-3); }
    .payment-team-card { background: var(--surface-2); }

    .payment-mini-label {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--txt-muted);
        font-size: 0.72rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .payment-mini-label i { color: var(--accent); }

    .payment-mini-value {
        color: var(--txt-primary);
        font-family: 'Manrope', sans-serif;
        font-size: 0.98rem;
        font-weight: 850;
    }

    .payment-team-name {
        color: var(--txt-primary);
        font-size: 0.78rem;
        font-weight: 800;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 8px;
    }

    .payment-team-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
        color: var(--txt-muted);
        font-size: 0.72rem;
    }

    .payment-team-meta strong {
        color: var(--txt-primary);
        font-weight: 800;
    }

    @media (max-width: 640px) {
        .payment-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .payment-total-row,
        .payment-breakdown,
        .payment-team-grid {
            grid-template-columns: 1fr;
        }

        .payment-total-value {
            text-align: left;
            font-size: 1.25rem;
        }
    }

    /* ── Status timeline ── */
    .status-timeline { display: flex; flex-direction: column; gap: 0; }

    .timeline-item {
        display: flex;
        gap: 12px;
        padding-bottom: 16px;
        position: relative;
    }

    .timeline-item:last-child { padding-bottom: 0; }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 11px; top: 22px; bottom: 0;
        width: 1px;
        background: var(--border-subtle);
    }

    .timeline-item:last-child::before { display: none; }

    .timeline-dot {
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem; flex-shrink: 0;
        position: relative; z-index: 1;
    }

    .timeline-dot.done { background: var(--accent-dim); color: var(--accent); border: 1.5px solid rgba(163,177,75,0.35); }
    .timeline-dot.current { background: rgba(251,191,36,0.15); color: #fcd34d; border: 1.5px solid rgba(251,191,36,0.30); }
    .timeline-dot.pending { background: var(--surface-4); color: var(--txt-faint); border: 1.5px solid var(--border-subtle); }

    .timeline-text { flex: 1; padding-top: 2px; }
    .timeline-title { font-size: 0.8rem; font-weight: 600; color: var(--txt-primary); line-height: 1.2; }
    .timeline-sub   { font-size: 0.7rem; color: var(--txt-muted); margin-top: 1px; }

    /* ── Input Score Form ── */
    .score-form-card {
        background: var(--surface-2);
        border: 1px solid rgba(163,177,75,0.25);
        border-radius: 16px;
        overflow: hidden;
    }

    .score-form-header {
        background: var(--accent-dim);
        padding: 0.85rem 1.25rem;
        display: flex; align-items: center; gap: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 0.875rem; font-weight: 700;
        color: var(--accent);
        border-bottom: 1px solid rgba(163,177,75,0.20);
    }

    .score-input-pair {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 10px;
        align-items: end;
        margin-bottom: 1rem;
    }

    .score-input-team-label {
        font-size: 0.72rem; font-weight: 700;
        color: var(--txt-muted); margin-bottom: 5px;
        text-align: center;
    }

    .score-input-vs {
        font-family: 'Manrope', sans-serif;
        font-size: 1.2rem; font-weight: 800;
        color: var(--txt-faint);
        padding-bottom: 10px;
        text-align: center;
    }

    .score-number-input {
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        border-radius: 10px;
        padding: 10px;
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: var(--txt-primary);
        text-align: center;
        outline: none;
        width: 100%;
        transition: border-color 0.2s;
        -moz-appearance: textfield;
    }

    .score-number-input::-webkit-outer-spin-button,
    .score-number-input::-webkit-inner-spin-button { -webkit-appearance: none; }

    .score-number-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-dim);
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('matches.index') }}">Pertandingan</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">{{ $match->match_code }}</span></li>
</ul>

@php
    $myScore  = $isHome ? $match->home_score : $match->away_score;
    $oppScore = $isHome ? $match->away_score : $match->home_score;
    $isCompleted = $match->status === 'completed';
    $canInputScore = $match->status === 'ongoing'
        && \Carbon\Carbon::parse($match->match_datetime)->isPast()
        && is_null($match->home_score);

    $statusSteps = [
        ['key' => 'created',   'label' => 'Tantangan Dikirim',  'sub' => $match->created_at->format('d M Y, H:i'), 'done' => true,         'icon' => 'bi-send'],
        ['key' => 'accepted',  'label' => 'Match Dijadwalkan',  'sub' => 'Status: ' . ucfirst(str_replace('_', ' ', $match->status)),    'done' => in_array($match->status, ['awaiting_payment','ongoing','completed']), 'current' => $match->status === 'awaiting_payment', 'icon' => 'bi-calendar-check'],
        ['key' => 'payment',   'label' => 'Pembayaran',         'sub' => $match->status === 'awaiting_payment' ? 'Menunggu pembayaran kedua tim' : 'Pembayaran lengkap', 'done' => in_array($match->status, ['ongoing','completed']), 'current' => $match->status === 'awaiting_payment', 'icon' => 'bi-credit-card'],
        ['key' => 'completed', 'label' => 'Match Selesai',      'sub' => $isCompleted ? 'Skor telah diinput' : 'Menunggu', 'done' => $isCompleted, 'current' => $canInputScore, 'icon' => 'bi-trophy'],
        ['key' => 'verified',  'label' => 'Terverifikasi',      'sub' => optional($match->verification)->status === 'verified' ? 'Diverifikasi admin' : 'Menunggu admin', 'done' => optional($match->verification)->status === 'verified', 'icon' => 'bi-shield-check'],
    ];
@endphp

{{-- ══ MATCH HEADER ══ --}}
<div class="match-header-card">
    <div class="match-header-top">
        <div class="match-header-grid"></div>
        <div class="match-header-content">

            {{-- Status + code --}}
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <span style="font-size:0.7rem; font-weight:700; color:var(--txt-faint); text-transform:uppercase; letter-spacing:0.08em; display:flex; align-items:center; gap:6px;">
                    <i class="bi bi-hash"></i> {{ $match->match_code }}
                </span>
                @php
                    $headerStatus = [
                        'pending'   => ['label'=>'Menunggu',   'class'=>'status-pending'],
                        'accepted'  => ['label'=>'Terjadwal',  'class'=>'status-accepted'],
                        'scheduled' => ['label'=>'Terjadwal',  'class'=>'status-accepted'],
                        'confirmed' => ['label'=>'Terkonfirmasi', 'class'=>'status-accepted'],
                        'awaiting_payment' => ['label'=>'Menunggu Pembayaran', 'class'=>'status-pending'],
                        'ongoing'   => ['label'=>'Berjalan', 'class'=>'status-accepted'],
                        'completed' => ['label'=>'Selesai',    'class'=>'status-completed'],
                        'cancelled' => ['label'=>'Dibatalkan', 'class'=>'status-cancelled'],
                    ][$match->status] ?? ['label'=>ucfirst($match->status), 'class'=>'status-pending'];
                @endphp
                <span class="match-status-badge {{ $headerStatus['class'] }}">
                    {{ $headerStatus['label'] }}
                </span>
            </div>

            {{-- VS --}}
            <div class="match-vs-panel">
                {{-- My Team --}}
                <div class="match-team-block">
                    @if ($myTeamInMatch && $myTeamInMatch->logo_path)
                        <img src="{{ asset('storage/' . $myTeamInMatch->logo_path) }}" alt="Logo {{ $myTeamInMatch->name }}" class="match-team-big-avatar mine" style="object-fit: cover;">
                    @else
                         <div class="match-team-big-avatar mine">
                            {{ strtoupper(substr($myTeamInMatch->name ?? '?', 0, 2)) }}
                        </div>
                    @endif
                    <div class="match-team-big-name">{{ $myTeamInMatch->name }}</div>
                    <span class="match-team-big-label">Tim Saya</span>
                </div>

                {{-- Centre --}}
                <div class="match-vs-centre">
                    @if ($isCompleted && !is_null($myScore))
                        <div class="match-big-score">
                            <span class="{{ $myScore > $oppScore ? 'win' : ($myScore < $oppScore ? 'lose' : '') }}">{{ $myScore }}</span>
                            <span class="sep">—</span>
                            <span class="{{ $oppScore > $myScore ? 'win' : ($oppScore < $myScore ? 'lose' : '') }}">{{ $oppScore }}</span>
                        </div>
                        @if ($myScore > $oppScore)
                            <span class="match-result-label" style="color:var(--accent);">🏆 MENANG</span>
                        @elseif ($myScore < $oppScore)
                            <span class="match-result-label" style="color:#f87171;">KALAH</span>
                        @else
                            <span class="match-result-label" style="color:var(--txt-muted);">SERI</span>
                        @endif
                    @else
                        <span class="match-vs-label">VS</span>
                        @if ($match->match_datetime && in_array($match->status, ['pending','accepted']))
                            <span style="font-size:0.72rem; color:var(--txt-muted); margin-top:4px;">
                                {{ \Carbon\Carbon::parse($match->match_datetime)->diffForHumans() }}
                            </span>
                        @endif
                    @endif
                </div>

                {{-- Opponent --}}
                <div class="match-team-block">
                    @if ($oppTeamInMatch && $oppTeamInMatch->logo_path)
                        <img src="{{ asset('storage/' . $oppTeamInMatch->logo_path) }}" alt="Logo {{ $oppTeamInMatch->name }}" class="match-team-big-avatar opp" style="object-fit: cover;">
                    @else
                         <div class="match-team-big-avatar opp">
                            {{ strtoupper(substr($oppTeamInMatch->name ?? '?', 0, 2)) }}
                        </div>
                    @endif
                    <div class="match-team-big-name" style="color:var(--txt-secondary);">{{ $oppTeamInMatch->name }}</div>
                    <span class="match-team-big-label">Lawan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Meta row --}}
    <div class="match-header-meta">
        @if ($match->match_datetime)
            <div class="match-header-meta-item">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::parse($match->match_datetime)->translatedFormat('l, d F Y') }}
            </div>
            <div class="match-header-meta-item">
                <i class="bi bi-clock"></i>
                {{ \Carbon\Carbon::parse($match->match_datetime)->format('H:i') }}
                @if ($match->duration_minutes)
                    <span style="color:var(--txt-faint);">&nbsp;({{ $match->duration_minutes }} mnt)</span>
                @endif
            </div>
        @endif
        @if ($match->venue)
            <div class="match-header-meta-item">
                <i class="bi bi-geo-alt"></i>
                {{ $match->venue->name }}
                @if ($match->venue->city)
                    <span style="color:var(--txt-faint);">, {{ $match->venue->city }}</span>
                @endif
            </div>
        @endif
        @if ($match->total_cost)
            <div class="match-header-meta-item">
                <i class="bi bi-cash"></i>
                Rp {{ number_format($match->total_cost, 0, ',', '.') }}
            </div>
        @endif
    </div>
</div>

{{-- ══ MAIN GRID ══ --}}
<div class="match-detail-grid">

    {{-- LEFT column --}}
    <div>

        {{-- Input Score (if applicable) --}}
        @if ($canInputScore)
            <div id="input-score" class="score-form-card" style="margin-bottom:1.5rem;">
                <div class="score-form-header">
                    <i class="bi bi-pencil-square"></i> Input Hasil Pertandingan
                </div>
                <div style="padding:1.25rem;">
                    <form action="{{ route('matches.score', $match) }}" method="POST">
                        @csrf
                        <div class="score-input-pair">
                            <div>
                                <div class="score-input-team-label">{{ $myTeamInMatch->name }}</div>
                                <input
                                    type="number"
                                    name="home_score"
                                    class="score-number-input"
                                    min="0" max="99"
                                    value="{{ old('home_score', 0) }}"
                                    placeholder="0"
                                >
                            </div>
                            <div class="score-input-vs">—</div>
                            <div>
                                <div class="score-input-team-label">{{ $oppTeamInMatch->name }}</div>
                                <input
                                    type="number"
                                    name="away_score"
                                    class="score-number-input"
                                    min="0" max="99"
                                    value="{{ old('away_score', 0) }}"
                                    placeholder="0"
                                >
                            </div>
                        </div>

                        <div class="form-group-mg">
                            <label class="form-label-mg"><i class="bi bi-chat-text me-1"></i> Catatan (opsional)</label>
                            <textarea name="notes" class="form-control-mg" rows="2" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn-lime" style="width:100%; justify-content:center;">
                            <i class="bi bi-check2-circle"></i> Simpan Hasil Pertandingan
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Verification status (if completed & awaiting) --}}
        @if ($isCompleted && optional($match->verification)->status === 'pending')
            <div class="alert-matchgo-info" style="margin-bottom:1.25rem;">
                <i class="bi bi-shield-check me-2"></i>
                <strong>Menunggu Verifikasi Admin</strong> — Skor telah diinput dan sedang ditinjau oleh admin MATCHGO.
            </div>
        @endif

        {{-- Match Detail --}}
        <div class="detail-section">
            <div class="detail-section-header">
                <i class="bi bi-info-circle"></i> Detail Pertandingan
            </div>
            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="detail-row-label">Kode Match</span>
                    <span class="detail-row-val" style="font-family:'Manrope',sans-serif;">{{ $match->match_code }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-row-label">Tim Kandang</span>
                    <span class="detail-row-val">{{ $match->homeTeam->name ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-row-label">Tim Tamu</span>
                    <span class="detail-row-val">{{ $match->awayTeam->name ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-row-label">Tanggal & Waktu</span>
                    <span class="detail-row-val">
                        @if ($match->match_datetime)
                            {{ \Carbon\Carbon::parse($match->match_datetime)->translatedFormat('d M Y, H:i') }}
                        @else —
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-row-label">Durasi</span>
                    <span class="detail-row-val">{{ $match->duration_minutes ? $match->duration_minutes . ' menit' : '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-row-label">Lokasi</span>
                    <span class="detail-row-val">{{ $match->venue->name ?? '—' }}</span>
                </div>
                @if (false && $match->total_cost)
                    <div class="detail-row">
                        <span class="detail-row-label">Total Biaya Pertandingan</span>
                        <span class="detail-row-val">Rp {{ number_format($match->total_cost, 0, ',', '.') }}</span>
                    </div>
                    @if ($match->refereeRental)
                        <div class="detail-row">
                            <span class="detail-row-label">Biaya Sewa Wasit</span>
                            <span class="detail-row-val">Rp {{ number_format($match->refereeRental->rental_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row-label">Wasit</span>
                            <span class="detail-row-val">{{ $match->refereeRental->referee->name ?? '—' }} @if($match->refereeRental->referee->certification_level) ({{ $match->refereeRental->referee->certification_level }}) @endif</span>
                        </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-row-label">Biaya per tim (50:50)</span>
                        <span class="detail-row-val">Rp {{ number_format($homeTeamShare, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-row-label">{{ $match->homeTeam->name ?? 'Tim Kandang' }} ({{ $homeTeamMembers }} anggota)</span>
                        <span class="detail-row-val">
                            {{ $homeCostPerMember ? 'Rp ' . number_format($homeCostPerMember, 0, ',', '.') . ' / anggota' : '—' }}
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-row-label">{{ $match->awayTeam->name ?? 'Tim Tamu' }} ({{ $awayTeamMembers }} anggota)</span>
                        <span class="detail-row-val">
                            {{ $awayCostPerMember ? 'Rp ' . number_format($awayCostPerMember, 0, ',', '.') . ' / anggota' : '—' }}
                        </span>
                    </div>
                @endif
                @if ($match->notes)
                    <div class="detail-row">
                        <span class="detail-row-label">Catatan</span>
                        <span class="detail-row-val" style="max-width:220px;">{{ $match->notes }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($match->total_cost)
            @php
                $refereeRental = $match->refereeRental;
                $refereeFee = $refereeRental ? (float) $refereeRental->rental_cost : 0;
                $venueFee = max(0, (float) $match->total_cost - $refereeFee);
                $refereeName = optional(optional($refereeRental)->referee)->name;
                $refereeLevel = optional(optional($refereeRental)->referee)->certification_level;
            @endphp
            <div class="payment-card">
                <div class="payment-card-header">
                    <div class="payment-card-title">
                        <i class="bi bi-receipt-cutoff"></i>
                        Detail Pembayaran
                    </div>
                    <span class="payment-card-badge">Split 50:50</span>
                </div>
                <div class="payment-card-body">
                    <div class="payment-total-row">
                        <div>
                            <div class="payment-total-label">Total biaya pertandingan</div>
                            <div style="font-size:0.72rem;color:var(--txt-faint);margin-top:3px;">
                                Termasuk venue dan biaya tambahan yang aktif.
                            </div>
                        </div>
                        <div class="payment-total-value">Rp {{ number_format($match->total_cost, 0, ',', '.') }}</div>
                    </div>

                    <div class="payment-breakdown">
                        <div class="payment-mini-card">
                            <div class="payment-mini-label">
                                <i class="bi bi-geo-alt"></i>
                                Biaya Venue
                            </div>
                            <div class="payment-mini-value">Rp {{ number_format($venueFee, 0, ',', '.') }}</div>
                        </div>
                        <div class="payment-mini-card">
                            <div class="payment-mini-label">
                                <i class="bi bi-person-badge"></i>
                                Wasit
                            </div>
                            <div class="payment-mini-value">
                                {{ $refereeRental ? 'Rp ' . number_format($refereeFee, 0, ',', '.') : 'Tidak dipakai' }}
                            </div>
                            @if ($refereeRental)
                                <div style="font-size:0.7rem;color:var(--txt-muted);margin-top:4px;">
                                    {{ $refereeName ?? 'Wasit' }}{{ $refereeLevel ? ' - ' . ucfirst($refereeLevel) : '' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="payment-team-grid">
                        <div class="payment-team-card">
                            <div class="payment-team-name">{{ $match->homeTeam->name ?? 'Tim Kandang' }}</div>
                            <div class="payment-team-meta">
                                <span>Bagian tim: <strong>Rp {{ number_format($homeTeamShare, 0, ',', '.') }}</strong></span>
                                <span>{{ $homeTeamMembers }} anggota: <strong>{{ $homeCostPerMember ? 'Rp ' . number_format($homeCostPerMember, 0, ',', '.') : '-' }}</strong> / anggota</span>
                            </div>
                        </div>
                        <div class="payment-team-card">
                            <div class="payment-team-name">{{ $match->awayTeam->name ?? 'Tim Tamu' }}</div>
                            <div class="payment-team-meta">
                                <span>Bagian tim: <strong>Rp {{ number_format($awayTeamShare, 0, ',', '.') }}</strong></span>
                                <span>{{ $awayTeamMembers }} anggota: <strong>{{ $awayCostPerMember ? 'Rp ' . number_format($awayCostPerMember, 0, ',', '.') : '-' }}</strong> / anggota</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border-subtle);">
                        @php
                            $paymentStatusText = function ($payment) {
                                return match (optional($payment)->status) {
                                    'paid' => 'Sudah bayar',
                                    'failed' => 'Gagal',
                                    'expired' => 'Kedaluwarsa',
                                    'cancelled' => 'Dibatalkan',
                                    default => 'Pending',
                                };
                            };
                        @endphp
                        <div class="payment-breakdown" style="margin-bottom:1rem;">
                            <div class="payment-mini-card">
                                <div class="payment-mini-label">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $match->homeTeam->name ?? 'Tim Kandang' }}
                                </div>
                                <div class="payment-mini-value">
                                    {{ $paymentStatusText($homePayment) }}
                                </div>
                                @if (optional($homePayment)->paid_at)
                                    <div style="font-size:0.7rem;color:var(--txt-muted);margin-top:4px;">
                                        {{ $homePayment->paid_at->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                            <div class="payment-mini-card">
                                <div class="payment-mini-label">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $match->awayTeam->name ?? 'Tim Tamu' }}
                                </div>
                                <div class="payment-mini-value">
                                    {{ $paymentStatusText($awayPayment) }}
                                </div>
                                @if (optional($awayPayment)->paid_at)
                                    <div style="font-size:0.7rem;color:var(--txt-muted);margin-top:4px;">
                                        {{ $awayPayment->paid_at->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($match->status === 'awaiting_payment' && optional($myPayment)->status !== 'paid')
                            <div class="payment-mini-card" style="margin-bottom:1rem;">
                                <div class="payment-mini-label">
                                    <i class="bi bi-wallet2"></i>
                                    Invoice Tim Kamu
                                </div>
                                <div class="payment-mini-value">
                                    Rp {{ number_format(optional($myPayment)->amount ?? ($isHome ? $homeTeamShare : $awayTeamShare), 0, ',', '.') }}
                                </div>
                                @if (optional($myPayment)->order_id)
                                    <div style="font-size:0.7rem;color:var(--txt-muted);margin-top:4px;">
                                        Order ID: {{ $myPayment->order_id }}
                                    </div>
                                @endif
                                @if (optional($myPayment)->expired_at)
                                    <div style="font-size:0.7rem;color:var(--txt-muted);margin-top:4px;">
                                        Berlaku sampai {{ $myPayment->expired_at->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>

                            <button
                                type="button"
                                class="btn-lime js-pay-midtrans"
                                data-create-url="{{ route('matches.payments.create', $match) }}"
                                style="width:100%;justify-content:center;"
                            >
                                <i class="bi bi-credit-card"></i> Bayar Sekarang
                            </button>
                        @elseif (optional($myPayment)->status === 'paid' && $match->status === 'awaiting_payment')
                            <div class="alert-matchgo-info" style="margin:0;">
                                <i class="bi bi-hourglass-split me-2"></i>
                                Pembayaran tim kamu sudah diterima. Menunggu pembayaran dari tim lawan.
                            </div>
                        @elseif ($match->status === 'ongoing')
                            <div class="alert-matchgo-info" style="margin:0;">
                                <i class="bi bi-check2-circle me-2"></i>
                                Pembayaran kedua tim sudah lengkap. Match sedang berjalan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Verification detail (if available) --}}
        @if ($match->verification)
            <div class="detail-section">
                <div class="detail-section-header">
                    <i class="bi bi-shield-check"></i> Verifikasi Skor
                </div>
                <div class="detail-section-body">
                    <div class="detail-row">
                        <span class="detail-row-label">Skor Tim A</span>
                        <span class="detail-row-val">{{ $match->verification->score_team_a }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-row-label">Skor Tim B</span>
                        <span class="detail-row-val">{{ $match->verification->score_team_b }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-row-label">Status</span>
                        <span class="detail-row-val">
                            @php
                                $vs = $match->verification->status;
                                $vClass = match($vs) {
                                    'verified' => 'status-completed',
                                    'rejected' => 'status-cancelled',
                                    default    => 'status-verifying',
                                };
                            @endphp
                            <span class="match-status-badge {{ $vClass }}">{{ ucfirst($vs) }}</span>
                        </span>
                    </div>
                    @if ($match->verification->auditor)
                        <div class="detail-row">
                            <span class="detail-row-label">Diverifikasi oleh</span>
                            <span class="detail-row-val">{{ $match->verification->auditor->name }}</span>
                        </div>
                    @endif
                    @if ($match->verification->notes)
                        <div class="detail-row">
                            <span class="detail-row-label">Catatan Admin</span>
                            <span class="detail-row-val">{{ $match->verification->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>

    {{-- RIGHT column: Status timeline + actions --}}
    <div>

        {{-- Status Timeline --}}
        <div class="detail-section" style="margin-bottom:1.25rem;">
            <div class="detail-section-header">
                <i class="bi bi-list-check"></i> Progress Match
            </div>
            <div class="detail-section-body">
                <div class="status-timeline">
                    @foreach ($statusSteps as $step)
                        @php
                            $dotClass = $step['done'] ? 'done' : (($step['current'] ?? false) ? 'current' : 'pending');
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $dotClass }}">
                                <i class="bi {{ $step['icon'] }}"></i>
                            </div>
                            <div class="timeline-text">
                                <div class="timeline-title"
                                     style="{{ $step['done'] ? 'color:var(--txt-primary)' : 'color:var(--txt-muted)' }}">
                                    {{ $step['label'] }}
                                </div>
                                <div class="timeline-sub">{{ $step['sub'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Actions card --}}
        <div class="detail-section">
            <div class="detail-section-header">
                <i class="bi bi-lightning"></i> Aksi
            </div>
            <div class="detail-section-body" style="display:flex; flex-direction:column; gap:8px;">

                @if ($canInputScore)
                    <a href="#input-score" class="btn-lime" style="justify-content:center;">
                        <i class="bi bi-pencil-square"></i> Input Skor Sekarang
                    </a>
                @endif

                @if (in_array($match->status, ['pending', 'accepted']))
                    <form action="{{ route('matches.cancel', $match) }}" method="POST"
                          onsubmit="return confirm('Batalkan match {{ $match->match_code }}?')">
                        @csrf
                        <button type="submit" class="btn-matchgo-danger" style="width:100%; justify-content:center;">
                            <i class="bi bi-x-circle"></i> Batalkan Match
                        </button>
                    </form>
                @endif

                <a href="{{ route('matches.index') }}" class="btn-outline-lime" style="justify-content:center;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>

                <a href="{{ route('matchmaking.index') }}" class="btn-outline-lime" style="justify-content:center;">
                    <i class="bi bi-search-heart"></i> Cari Lawan Baru
                </a>

            </div>
        </div>

    </div>

</div>

@endsection

@if ($match->status === 'awaiting_payment' && optional($myPayment)->status !== 'paid')
    @push('scripts')
        <script
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"
        ></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const button = document.querySelector('.js-pay-midtrans');

                if (!button) return;

                button.addEventListener('click', async function () {
                    button.disabled = true;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyiapkan Pembayaran...';

                    try {
                        const response = await fetch(button.dataset.createUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (!response.ok || !data.snap_token) {
                            throw new Error(data.message || 'Gagal membuat transaksi pembayaran.');
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function () {
                                window.location.href = '{{ route('matches.payment.success', $match) }}';
                            },
                            onPending: function () {
                                window.location.reload();
                            },
                            onError: function () {
                                window.location.href = '{{ route('matches.payment.failed', $match) }}';
                            },
                            onClose: function () {
                                button.disabled = false;
                                button.innerHTML = originalText;
                            }
                        });
                    } catch (error) {
                        alert(error.message);
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                });
            });
        </script>
    @endpush
@endif
