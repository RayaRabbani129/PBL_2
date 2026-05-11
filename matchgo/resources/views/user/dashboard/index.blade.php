@extends('user.layouts.app')

@section('title', 'Dashboard — MATCHGO')
@section('page-title', 'Dashboard')

@push('styles')
<style>

/* ══════════════════════════════════════════════════
   HERO
══════════════════════════════════════════════════ */
.dash-hero {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
}
.dash-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
    pointer-events: none;
}
.dash-hero-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    opacity: 0.35;
}
.dash-hero-inner {
    position: relative; z-index: 1;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.dash-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.12em; color: var(--accent);
    background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20);
    border-radius: 99px; padding: 3px 11px; margin-bottom: 10px;
}
.dash-hero h2 {
    font-family: 'Manrope', sans-serif; font-size: 1.5rem; font-weight: 800;
    color: var(--txt-primary); line-height: 1.25; margin-bottom: 6px;
}
.dash-hero h2 span { color: var(--accent); }
.dash-hero p { font-size: 0.83rem; color: var(--txt-muted); margin: 0; }
.dash-hero-actions {
    display: flex; align-items: center; gap: 8px;
    flex-shrink: 0; align-self: flex-start; margin-top: 4px;
}
.dash-hero-link {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.72rem; font-weight: 600; padding: 7px 13px;
    border-radius: 9px; text-decoration: none;
    transition: background 0.15s; white-space: nowrap;
}
.dash-hero-link.accent {
    background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20); color: var(--accent);
}
.dash-hero-link.subtle {
    background: var(--surface-3); border: 1px solid var(--border-medium); color: var(--txt-secondary);
}

/* ══════════════════════════════════════════════════
   STATS GRID — 4 kolom → 2 kolom → 2 kolom kecil
══════════════════════════════════════════════════ */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 1.5rem;
}
.mini-stat {
    display: flex; align-items: center; gap: 11px;
    background: var(--surface-2); border: 1px solid var(--border-subtle);
    border-radius: 14px; padding: 1rem 1.1rem;
    transition: border-color 0.2s, transform 0.15s;
    min-width: 0;
}
.mini-stat:hover { border-color: rgba(163,177,75,0.28); transform: translateY(-1px); }
.mini-stat-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--accent-dim); color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.mini-stat-body { flex: 1; min-width: 0; }
.mini-stat-val {
    font-family: 'Manrope', sans-serif; font-size: 1.45rem;
    font-weight: 800; color: var(--txt-primary); line-height: 1;
}
.mini-stat-val small { font-size: 0.78rem; font-weight: 600; color: var(--txt-muted); }
.mini-stat-label { font-size: 0.7rem; color: var(--txt-muted); font-weight: 500; margin-top: 3px; }
.mini-stat-badge {
    font-size: 0.62rem; font-weight: 700; padding: 2px 7px;
    border-radius: 99px; flex-shrink: 0; white-space: nowrap; margin-left: auto;
}
.badge-win  { background: rgba(16,185,129,0.12); color: #10b981; }
.badge-loss { background: rgba(239,68,68,0.10);  color: #f87171; }

/* ══════════════════════════════════════════════════
   MAIN LAYOUT — desktop: 2 kolom, mobile: 1 kolom
══════════════════════════════════════════════════ */
.dash-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 1.25rem;
    align-items: start;
}

/* ══════════════════════════════════════════════════
   CARD BASE
══════════════════════════════════════════════════ */
.dash-card {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 18px;
    padding: 1.25rem 1.35rem;
}

/* ══════════════════════════════════════════════════
   SECTION HEADER
══════════════════════════════════════════════════ */
.section-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1rem; padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-subtle);
    gap: 10px; flex-wrap: wrap;
}
.section-title {
    font-family: 'Manrope', sans-serif; font-size: 0.9rem;
    font-weight: 700; color: var(--txt-primary);
    display: flex; align-items: center; gap: 7px; margin: 0;
}
.section-title i { color: var(--accent); }
.count-pill {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 22px; height: 22px; border-radius: 99px;
    background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20);
    font-size: 0.68rem; font-weight: 700; color: var(--accent); padding: 0 7px;
}
.view-all {
    font-size: 0.75rem; font-weight: 600; color: var(--accent);
    text-decoration: none; display: flex; align-items: center; gap: 4px;
}
.view-all:hover { color: var(--txt-primary); text-decoration: none; }

/* ══════════════════════════════════════════════════
   DASH ITEMS
══════════════════════════════════════════════════ */
.dash-item-list { display: flex; flex-direction: column; gap: 9px; }
.dash-item {
    background: var(--surface-3); border: 1px solid var(--border-subtle);
    border-radius: 13px; padding: 0.85rem 1rem;
    display: flex; align-items: center; gap: 11px;
    transition: border-color 0.18s, background 0.15s, transform 0.15s;
    position: relative; overflow: hidden; min-width: 0;
}
.dash-item::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--accent); opacity: 0; transition: opacity 0.18s;
}
.dash-item:hover { border-color: rgba(163,177,75,0.28); background: var(--surface-4); transform: translateY(-1px); }
.dash-item:hover::before { opacity: 1; }

.item-badge {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; flex-shrink: 0;
}
.item-badge.accent   { background: var(--accent-dim);            color: var(--accent);    border: 1px solid rgba(163,177,75,0.20); }
.item-badge.pending  { background: rgba(245,158,11,0.10);         color: #f59e0b;          border: 1px solid rgba(245,158,11,0.20); }
.item-badge.confirm  { background: rgba(59,130,246,0.10);         color: #60a5fa;          border: 1px solid rgba(59,130,246,0.18); }
.item-badge.win      { background: rgba(16,185,129,0.12);         color: #10b981;          border: 1px solid rgba(16,185,129,0.22); }
.item-badge.loss     { background: rgba(239,68,68,0.10);          color: #f87171;          border: 1px solid rgba(239,68,68,0.18); }
.item-badge.draw     { background: var(--surface-4);              color: var(--txt-faint); border: 1px solid var(--border-subtle); }
.item-badge.schedule { background: rgba(167,139,250,0.10);        color: #a78bfa;          border: 1px solid rgba(167,139,250,0.20); }

.item-info { flex: 1; min-width: 0; }
.item-title {
    font-family: 'Manrope', sans-serif; font-size: 0.875rem;
    font-weight: 700; color: var(--txt-primary);
    margin-bottom: 3px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.item-meta {
    display: flex; align-items: center; gap: 9px;
    flex-wrap: wrap; font-size: 0.7rem; color: var(--txt-muted);
}
.item-meta span { display: flex; align-items: center; gap: 3px; }

/* Score chip */
.score-chip {
    font-family: 'Manrope', sans-serif; font-size: 0.78rem;
    font-weight: 800; padding: 3px 9px; border-radius: 7px; flex-shrink: 0;
}
.score-chip.win  { background: rgba(16,185,129,0.12); color: #10b981; }
.score-chip.loss { background: rgba(239,68,68,0.10);  color: #f87171; }
.score-chip.draw { background: var(--surface-4);      color: var(--txt-muted); }

/* Result pill */
.result-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.65rem; font-weight: 700; padding: 2px 9px;
    border-radius: 99px; flex-shrink: 0;
}
.result-pill::before { content: ''; width: 5px; height: 5px; background: currentColor; border-radius: 50%; }
.result-pill.win  { background: rgba(16,185,129,0.12); color: #10b981; border: 1px solid rgba(16,185,129,0.22); }
.result-pill.loss { background: rgba(239,68,68,0.10);  color: #f87171; border: 1px solid rgba(239,68,68,0.18); }
.result-pill.draw { background: var(--surface-4);      color: var(--txt-muted); border: 1px solid var(--border-subtle); }

/* Status pill */
.status-pill {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.65rem; font-weight: 600; padding: 2px 8px;
    border-radius: 99px; flex-shrink: 0;
}
.status-pill::before { content: ''; width: 5px; height: 5px; background: currentColor; border-radius: 50%; }
.status-pill.available { background: rgba(16,185,129,0.10); color: #10b981; border: 1px solid rgba(16,185,129,0.20); }
.status-pill.pending   { background: rgba(245,158,11,0.10); color: #f59e0b; border: 1px solid rgba(245,158,11,0.20); }
.status-pill.confirmed { background: rgba(59,130,246,0.10); color: #60a5fa; border: 1px solid rgba(59,130,246,0.18); }

/* ══════════════════════════════════════════════════
   PERFORMA BARS
══════════════════════════════════════════════════ */
.perf-bar-label {
    display: flex; justify-content: space-between;
    font-size: 0.7rem; color: var(--txt-muted); margin-bottom: 5px;
}
.perf-bar { height: 5px; background: var(--surface-4); border-radius: 99px; overflow: hidden; }
.perf-bar-fill { height: 100%; border-radius: 99px; background: var(--accent); transition: width 0.6s ease; }
.perf-bar-fill.red  { background: #f87171; }
.perf-bar-fill.gray { background: var(--txt-faint); }
.perf-badges { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 0.9rem; }
.perf-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.68rem; font-weight: 600; padding: 3px 10px;
    border-radius: 99px;
}

/* ══════════════════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════════════════ */
.quick-list { display: flex; flex-direction: column; gap: 7px; }
.quick-item {
    display: flex; align-items: center; gap: 11px; padding: 0.8rem 0.9rem;
    border-radius: 12px; text-decoration: none;
    color: var(--txt-secondary); border: 1px solid var(--border-subtle);
    background: var(--surface-3);
    transition: border-color 0.15s, background 0.15s, transform 0.15s;
    position: relative; overflow: hidden;
}
.quick-item::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--accent); opacity: 0; transition: opacity 0.18s;
}
.quick-item:hover {
    border-color: rgba(163,177,75,0.28); background: var(--surface-4);
    color: var(--txt-primary); transform: translateY(-1px); text-decoration: none;
}
.quick-item:hover::before { opacity: 1; }
.quick-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.quick-label {
    font-family: 'Manrope', sans-serif; font-weight: 700;
    font-size: 0.84rem; color: var(--txt-primary); line-height: 1.2;
}
.quick-sub { font-size: 0.7rem; color: var(--txt-muted); margin-top: 2px; }

/* ══════════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════════ */
.dash-empty {
    text-align: center; padding: 1.75rem 1rem;
    background: var(--surface-3); border: 1px dashed var(--border-medium);
    border-radius: 13px;
}
.dash-empty-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: var(--surface-4); border: 1px solid var(--border-medium);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: var(--txt-faint); margin: 0 auto 0.75rem;
}
.dash-empty-icon.accent { background: var(--accent-dim); color: var(--accent); border-color: rgba(163,177,75,0.20); }
.dash-empty h5 {
    font-family: 'Manrope', sans-serif; font-size: 0.875rem;
    font-weight: 700; color: var(--txt-secondary); margin-bottom: 5px;
}
.dash-empty p  {
    font-size: 0.77rem; color: var(--txt-muted);
    max-width: 240px; margin: 0 auto 0.75rem; line-height: 1.5;
}

/* ══════════════════════════════════════════════════
   MOBILE QUICK ACTIONS (horizontal scroll row)
   — hanya muncul di mobile sebagai ganti sidebar
══════════════════════════════════════════════════ */
.quick-scroll-row {
    display: none; /* disembunyikan di desktop */
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 4px;
    margin-bottom: 1.25rem;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.quick-scroll-row::-webkit-scrollbar { display: none; }
.quick-scroll-card {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    background: var(--surface-2); border: 1px solid var(--border-subtle);
    border-radius: 14px; padding: 0.85rem 0.75rem;
    text-decoration: none; flex-shrink: 0; width: 88px;
    transition: border-color 0.15s, background 0.15s;
}
.quick-scroll-card:hover {
    border-color: rgba(163,177,75,0.28); background: var(--surface-3); text-decoration: none;
}
.quick-scroll-icon {
    width: 40px; height: 40px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.quick-scroll-label {
    font-size: 0.65rem; font-weight: 700; color: var(--txt-secondary);
    text-align: center; line-height: 1.3;
    font-family: 'Manrope', sans-serif;
}

/* ══════════════════════════════════════════════════
   RESPONSIVE — TABLET  ≤ 1100px
══════════════════════════════════════════════════ */
@media (max-width: 1100px) {
    .dash-layout {
        grid-template-columns: 1fr;
    }
    /* Sidebar kanan disembunyikan — kontennya tampil via .quick-scroll-row di mobile */
    .dash-sidebar { display: none; }
    /* Aksi cepat horizontal scroll tampil */
    .quick-scroll-row { display: flex; }
    /* Stats tetap 2×2 di tablet */
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}

/* ══════════════════════════════════════════════════
   RESPONSIVE — MOBILE  ≤ 640px
══════════════════════════════════════════════════ */
@media (max-width: 640px) {
    /* Hero */
    .dash-hero { padding: 1.25rem; border-radius: 16px; margin-bottom: 1.1rem; }
    .dash-hero h2 { font-size: 1.2rem; }
    .dash-hero p  { font-size: 0.79rem; }
    .dash-hero-actions { width: 100%; margin-top: 0.75rem; }
    .dash-hero-link { flex: 1; justify-content: center; font-size: 0.7rem; padding: 7px 10px; }

    /* Stats tetap 2×2 */
    .stats-row { gap: 9px; margin-bottom: 1.1rem; }
    .mini-stat { padding: 0.8rem 0.85rem; border-radius: 12px; }
    .mini-stat-icon { width: 36px; height: 36px; font-size: 0.9rem; }
    .mini-stat-val  { font-size: 1.25rem; }
    /* sembunyikan badge di stat kecil agar tidak penuh */
    .mini-stat-badge { display: none; }

    /* Dash card */
    .dash-card { padding: 1rem; border-radius: 14px; }

    /* Item */
    .dash-item { padding: 0.75rem 0.85rem; }
    .item-badge { width: 34px; height: 34px; font-size: 0.85rem; }
    .item-title { font-size: 0.825rem; }
    /* Di mobile sembunyikan score-chip, result tetap tampil */
    .score-chip { display: none; }

    /* Quick scroll card lebih kecil */
    .quick-scroll-card { width: 78px; padding: 0.75rem 0.5rem; }
    .quick-scroll-icon { width: 36px; height: 36px; font-size: 0.88rem; }
}

/* ══════════════════════════════════════════════════
   RESPONSIVE — SMALL  ≤ 400px
══════════════════════════════════════════════════ */
@media (max-width: 400px) {
    .stats-row { grid-template-columns: 1fr 1fr; gap: 8px; }
    .mini-stat-val { font-size: 1.1rem; }
}

</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ol class="breadcrumb-matchgo">
    <li><span class="active"><i class="bi bi-house me-1"></i>Dashboard</span></li>
</ol>

{{-- ══ HERO ══ --}}
<div class="dash-hero">
    <div class="dash-hero-grid"></div>
    <div class="dash-hero-inner">
        <div>
            <div class="dash-hero-eyebrow">
                <i class="bi bi-stars"></i> Overview
            </div>
            <h2>Halo, <span>{{ Auth::user()->name }}</span>!</h2>
            <p>Kelola tim, cari lawan, dan atur pertandingan futsalmu di sini.</p>
        </div>
        <div class="dash-hero-actions">
            <a href="{{ route('matchmaking.index') }}" class="dash-hero-link accent">
                <i class="bi bi-search"></i> Cari Lawan
            </a>
            <a href="{{ route('schedule.index') }}" class="dash-hero-link subtle">
                <i class="bi bi-calendar3"></i> Jadwal
            </a>
        </div>
    </div>
</div>

{{-- ══ STATS ROW ══ --}}
<div class="stats-row">

    <div class="mini-stat">
        <div class="mini-stat-icon"><i class="bi bi-trophy-fill"></i></div>
        <div class="mini-stat-body">
            <div class="mini-stat-val">{{ $totalMatch }}</div>
            <div class="mini-stat-label">Total Match</div>
        </div>
        @if($totalWin > 0)
            <span class="mini-stat-badge badge-win">{{ $totalWin }}W</span>
        @endif
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:rgba(16,185,129,0.12);color:#10b981;">
            <i class="bi bi-arrow-up-right-circle-fill"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-val">{{ $totalWin }}<small> W</small></div>
            <div class="mini-stat-label">Menang</div>
        </div>
        <span class="mini-stat-badge badge-win">{{ $winRate }}%</span>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:rgba(239,68,68,0.10);color:#f87171;">
            <i class="bi bi-arrow-down-right-circle-fill"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-val">{{ $totalLoss }}<small> L</small></div>
            <div class="mini-stat-label">Kalah</div>
        </div>
        <span class="mini-stat-badge badge-loss">{{ $lossRate }}%</span>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background:rgba(245,158,11,0.10);color:#f59e0b;">
            <i class="bi bi-star-fill"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-val">{{ $teamRating }}</div>
            <div class="mini-stat-label">Team Rating</div>
        </div>
    </div>

</div>

{{-- ══ MOBILE: Aksi Cepat (horizontal scroll) ══ --}}
{{-- Hanya tampil saat sidebar kanan disembunyikan (≤1100px) --}}
@php
$quickActions = [
    ['icon'=>'bi-search',        'bg'=>'var(--accent-dim)',           'color'=>'var(--accent)', 'label'=>'Cari Lawan',      'url'=>route('matchmaking.index')],
    ['icon'=>'bi-shield-fill',   'bg'=>'rgba(59,130,246,0.10)',       'color'=>'#60a5fa',       'label'=>'Kelola Tim',       'url'=>route('team.index')],
    ['icon'=>'bi-calendar-plus', 'bg'=>'rgba(167,139,250,0.10)',      'color'=>'#a78bfa',       'label'=>'Tambah Jadwal',    'url'=>route('schedule.create')],
    // ['icon'=>'bi-geo-alt-fill',  'bg'=>'rgba(34,211,238,0.10)',       'color'=>'#22d3ee',       'label'=>'Lapangan',         'url'=>route('venues.index')],
    // ['icon'=>'bi-calculator',    'bg'=>'rgba(251,146,60,0.10)',       'color'=>'#fb923c',       'label'=>'Biaya Split',      'url'=>route('match-cost.index')],
    ['icon'=>'bi-person-circle', 'bg'=>'rgba(16,185,129,0.10)',       'color'=>'#10b981',       'label'=>'Profil',           'url'=>route('profile.index')],
];
@endphp
<div class="quick-scroll-row">
    @foreach($quickActions as $a)
    <a href="{{ $a['url'] }}" class="quick-scroll-card">
        <div class="quick-scroll-icon" style="background:{{ $a['bg'] }};color:{{ $a['color'] }};">
            <i class="bi {{ $a['icon'] }}"></i>
        </div>
        <span class="quick-scroll-label">{{ $a['label'] }}</span>
    </a>
    @endforeach
</div>

{{-- ══ MAIN LAYOUT ══ --}}
<div class="dash-layout">

    {{-- ──────── KOLOM KIRI ──────── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;min-width:0;">

        {{-- Performa Tim --}}
        <div class="dash-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-bar-chart-fill"></i> Performa Tim
                </h2>
                <span class="count-pill">{{ $totalMatch }} match</span>
            </div>

            @if($totalMatch > 0)
                @php $drawRate = round(($totalDraw / $totalMatch) * 100); @endphp
                <div style="display:flex;flex-direction:column;gap:0.9rem;">

                    <div>
                        <div class="perf-bar-label">
                            <span style="display:flex;align-items:center;gap:5px;">
                                <i class="bi bi-circle-fill" style="color:#10b981;font-size:0.4rem;"></i> Menang
                            </span>
                            <span style="font-weight:700;color:#10b981;">{{ $totalWin }} ({{ $winRate }}%)</span>
                        </div>
                        <div class="perf-bar"><div class="perf-bar-fill" style="width:{{ $winRate }}%;"></div></div>
                    </div>

                    <div>
                        <div class="perf-bar-label">
                            <span style="display:flex;align-items:center;gap:5px;">
                                <i class="bi bi-circle-fill" style="color:#f87171;font-size:0.4rem;"></i> Kalah
                            </span>
                            <span style="font-weight:700;color:#f87171;">{{ $totalLoss }} ({{ $lossRate }}%)</span>
                        </div>
                        <div class="perf-bar"><div class="perf-bar-fill red" style="width:{{ $lossRate }}%;"></div></div>
                    </div>

                    <div>
                        <div class="perf-bar-label">
                            <span style="display:flex;align-items:center;gap:5px;">
                                <i class="bi bi-circle-fill" style="color:var(--txt-faint);font-size:0.4rem;"></i> Seri
                            </span>
                            <span style="font-weight:700;color:var(--txt-muted);">{{ $totalDraw }} ({{ $drawRate }}%)</span>
                        </div>
                        <div class="perf-bar"><div class="perf-bar-fill gray" style="width:{{ $drawRate }}%;"></div></div>
                    </div>

                </div>

                <div class="perf-badges">
                    <span class="perf-badge" style="background:rgba(16,185,129,0.10);color:#10b981;border:1px solid rgba(16,185,129,0.18);">
                        <i class="bi bi-trophy-fill"></i> Win Rate {{ $winRate }}%
                    </span>
                    <span class="perf-badge" style="background:rgba(245,158,11,0.10);color:#f59e0b;border:1px solid rgba(245,158,11,0.18);">
                        <i class="bi bi-star-fill"></i> {{ $teamRating }} pts
                    </span>
                    <span class="perf-badge" style="background:var(--accent-dim);color:var(--accent);border:1px solid rgba(163,177,75,0.20);">
                        <i class="bi bi-lightning-charge-fill"></i> {{ $totalMatch }} Games
                    </span>
                </div>
            @else
                <div style="text-align:center;padding:1.5rem;color:var(--txt-muted);font-size:0.83rem;">
                    <i class="bi bi-bar-chart" style="font-size:1.8rem;color:var(--txt-faint);display:block;margin-bottom:10px;"></i>
                    Belum ada data pertandingan selesai
                </div>
            @endif
        </div>

        {{-- Upcoming Matches --}}
        <div class="dash-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-calendar-event"></i> Upcoming Matches
                </h2>
                <a href="{{ route('matches.index') }}" class="view-all">
                    Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="dash-item-list">
                @forelse($upcomingMatches as $match)
                @php
                    $badgeClass  = $match->status === 'confirmed' ? 'confirm'   : 'pending';
                    $statusClass = $match->status === 'confirmed' ? 'confirmed' : 'pending';
                    $statusLabel = $match->status === 'confirmed' ? 'Terkonfirmasi' : 'Menunggu';
                @endphp
                <div class="dash-item">
                    <div class="item-badge {{ $badgeClass }}">
                        <i class="bi {{ $match->status === 'confirmed' ? 'bi-check-circle-fill' : 'bi-clock-fill' }}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-title">
                            {{ $match->homeTeam->name ?? 'Tim A' }}
                            <span style="font-weight:500;color:var(--txt-muted);"> vs </span>
                            {{ $match->awayTeam->name ?? 'Tim B' }}
                        </div>
                        <div class="item-meta">
                            <span><i class="bi bi-clock"></i>
                                {{ \Carbon\Carbon::parse($match->match_datetime)->format('d M Y, H:i') }}
                            </span>
                            @if($match->venue ?? null)
                                <span><i class="bi bi-geo-alt"></i> {{ $match->venue->name }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
                @empty
                <div class="dash-empty">
                    <div class="dash-empty-icon accent"><i class="bi bi-calendar-plus"></i></div>
                    <h5>Belum ada pertandingan mendatang</h5>
                    <p>Cari lawan tanding dan atur jadwal pertandinganmu.</p>
                    <a href="{{ route('matchmaking.index') }}" class="btn-lime btn-sm">
                        <i class="bi bi-search"></i> Cari Lawan
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Riwayat Pertandingan --}}
        <div class="dash-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-clock-history"></i> Riwayat Pertandingan
                </h2>
                <a href="{{ route('matches.index') }}" class="view-all">
                    Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="dash-item-list">
                @forelse($recentMatches as $match)
                @php
                    $isWin  = $match->home_score > $match->away_score;
                    $isLoss = $match->home_score < $match->away_score;
                    $rc     = $isWin ? 'win' : ($isLoss ? 'loss' : 'draw');
                    $ri     = $isWin ? 'bi-trophy-fill' : ($isLoss ? 'bi-x-circle-fill' : 'bi-dash-circle');
                    $rl     = $isWin ? 'Menang' : ($isLoss ? 'Kalah' : 'Seri');
                @endphp
                <div class="dash-item">
                    <div class="item-badge {{ $rc }}">
                        <i class="bi {{ $ri }}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-title">
                            {{ $match->homeTeam->name ?? 'Tim A' }}
                            <span style="font-weight:500;color:var(--txt-muted);"> vs </span>
                            {{ $match->awayTeam->name ?? 'Tim B' }}
                        </div>
                        <div class="item-meta">
                            <span>
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($match->match_datetime)->format('d M Y') }}
                            </span>
                            @if($match->venue ?? null)
                                <span><i class="bi bi-geo-alt"></i> {{ $match->venue->name }}</span>
                            @endif
                        </div>
                    </div>
                    @if(!is_null($match->home_score) && !is_null($match->away_score))
                        <span class="score-chip {{ $rc }}">
                            {{ $match->home_score }} – {{ $match->away_score }}
                        </span>
                    @endif
                    <span class="result-pill {{ $rc }}">{{ $rl }}</span>
                </div>
                @empty
                <div class="dash-empty">
                    <div class="dash-empty-icon"><i class="bi bi-bar-chart"></i></div>
                    <h5>Belum ada riwayat</h5>
                    <p>Setiap pertandingan yang selesai akan tercatat di sini.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
    {{-- /kolom kiri --}}

    {{-- ──────── SIDEBAR KANAN (desktop only) ──────── --}}
    <div class="dash-sidebar" style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Aksi Cepat --}}
        <div class="dash-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-lightning-charge-fill"></i> Aksi Cepat
                </h2>
            </div>
            <div class="quick-list">
                @foreach($quickActions as $a)
                <a href="{{ $a['url'] }}" class="quick-item">
                    <div class="quick-icon" style="background:{{ $a['bg'] }};color:{{ $a['color'] }};">
                        <i class="bi {{ $a['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="quick-label">{{ $a['label'] }}</div>
                        @if(isset($a['sub']))<div class="quick-sub">{{ $a['sub'] }}</div>@endif
                    </div>
                    <i class="bi bi-chevron-right" style="margin-left:auto;font-size:0.7rem;color:var(--txt-faint);flex-shrink:0;"></i>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Jadwal Aktif --}}
        <div class="dash-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-calendar-week"></i> Jadwal Aktif
                </h2>
                <a href="{{ route('schedule.index') }}" class="view-all">
                    Edit <i class="bi bi-pencil" style="font-size:0.68rem;"></i>
                </a>
            </div>

            @php $daysMap = [0=>'Min',1=>'Sen',2=>'Sel',3=>'Rab',4=>'Kam',5=>'Jum',6=>'Sab']; @endphp

            @if(isset($mySchedules) && $mySchedules->count())
                <div class="dash-item-list">
                    @foreach($mySchedules as $sched)
                    <div class="dash-item" style="padding:0.65rem 0.9rem;">
                        <div class="item-badge schedule"
                             style="width:34px;height:34px;font-size:0.7rem;font-family:'Manrope',sans-serif;font-weight:800;">
                            {{ $daysMap[$sched->day_of_week] }}
                        </div>
                        <div class="item-info">
                            <div class="item-title" style="font-size:0.8rem;">
                                {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}
                            </div>
                        </div>
                        <span class="status-pill available">Aktif</span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="dash-empty" style="padding:1.25rem;">
                    <div class="dash-empty-icon" style="width:44px;height:44px;font-size:1.1rem;margin-bottom:0.65rem;">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h5 style="font-size:0.83rem;">Belum ada jadwal</h5>
                    <p style="font-size:0.73rem;margin-bottom:0.65rem;">
                        Tambahkan jadwal agar bisa dicocokkan dengan lawan.
                    </p>
                    <a href="{{ route('schedule.create') }}" class="btn-lime btn-sm">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                </div>
            @endif
        </div>

    </div>
    {{-- /sidebar kanan --}}

</div>
{{-- /dash-layout --}}

@php
// Tambahkan sub untuk quickActions versi desktop jika belum ada
$quickActions = [
    ['icon'=>'bi-search',        'bg'=>'var(--accent-dim)',           'color'=>'var(--accent)', 'label'=>'Cari Lawan Tanding',   'sub'=>'Temukan lawan sesuai levelmu',   'url'=>route('matchmaking.index')],
    ['icon'=>'bi-shield-fill',   'bg'=>'rgba(59,130,246,0.10)',       'color'=>'#60a5fa',       'label'=>'Kelola Tim',            'sub'=>'Profil & anggota tim',           'url'=>route('team.index')],
    ['icon'=>'bi-calendar-plus', 'bg'=>'rgba(167,139,250,0.10)',      'color'=>'#a78bfa',       'label'=>'Tambah Jadwal',         'sub'=>'Input ketersediaan baru',        'url'=>route('schedule.create')],
    ['icon'=>'bi-geo-alt-fill',  'bg'=>'rgba(34,211,238,0.10)',       'color'=>'#22d3ee',       'label'=>'Rekomendasi Lapangan',  'sub'=>'Temukan lapangan terdekat',      'url'=>route('venues.index')],
    ['icon'=>'bi-calculator',    'bg'=>'rgba(251,146,60,0.10)',       'color'=>'#fb923c',       'label'=>'Hitung Biaya Split',    'sub'=>'Kalkulasi biaya per orang',      'url'=>route('match-cost.index')],
    ['icon'=>'bi-person-circle', 'bg'=>'rgba(16,185,129,0.10)',       'color'=>'#10b981',       'label'=>'Edit Profil',           'sub'=>'Perbarui informasi akun',        'url'=>route('profile.index')],
];
@endphp

@endsection