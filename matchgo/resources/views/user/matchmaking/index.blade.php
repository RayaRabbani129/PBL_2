{{-- resources/views/user/matchmaking/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Matchmaking — MATCHGO')
@section('page-title', 'Matchmaking')

@push('styles')
<style>
    /* ── Hero ── */
    .mm-hero {
        position: relative; border-radius: 20px; overflow: hidden;
        padding: 2rem 2rem 1.75rem; margin-bottom: 1.5rem;
        background: var(--surface-2); border: 1px solid var(--border-subtle);
    }
    .mm-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
        pointer-events: none;
    }
    .mm-hero-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(var(--border-subtle) 1px, transparent 1px),
            linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
        background-size: 32px 32px; pointer-events: none; opacity: 0.35;
    }
    .mm-hero-content { position: relative; z-index: 1; }
    .mm-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--accent); background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,0.20); border-radius: 99px;
        padding: 4px 12px; margin-bottom: 14px;
    }
    .mm-hero h2 {
        font-family: 'Manrope', sans-serif; font-size: 1.6rem; font-weight: 800;
        color: var(--txt-primary); line-height: 1.2; margin-bottom: 8px;
    }
    .mm-hero h2 span { color: var(--accent); }
    .mm-hero p { font-size: 0.875rem; color: var(--txt-muted); max-width: 500px; margin-bottom: 0; }
    .mm-hero-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .mm-hero-link {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.72rem; font-weight: 600; padding: 6px 12px; border-radius: 8px;
        text-decoration: none; transition: background 0.15s, color 0.15s;
    }
    .mm-hero-link.accent {
        background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20); color: var(--accent);
    }
    .mm-hero-link.accent:hover { background: rgba(163,177,75,0.20); }
    .mm-hero-link.subtle {
        background: var(--surface-3); border: 1px solid var(--border-medium); color: var(--txt-secondary);
    }
    .mm-hero-link.subtle:hover { background: var(--surface-4); }

    /* ── My Team bar ── */
    .mm-my-team {
        background: var(--surface-3); border: 1px solid var(--border-medium);
        border-radius: 14px; padding: 0.9rem 1.25rem;
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 1.5rem; flex-wrap: wrap;
    }
    .mm-my-team-avatar {
        width: 42px; height: 42px; border-radius: 11px;
        background: var(--accent-dim); border: 1.5px solid rgba(163,177,75,0.30);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 0.95rem;
        color: var(--accent); flex-shrink: 0;
    }
    .mm-my-team-name { font-family: 'Manrope', sans-serif; font-size: 0.9rem; font-weight: 700; color: var(--txt-primary); line-height: 1.2; }
    .mm-my-team-meta { font-size: 0.73rem; color: var(--txt-muted); margin-top: 2px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .mm-my-team-meta span { display: flex; align-items: center; gap: 4px; }
    .mm-schedule-strip { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-left: auto; }
    .mm-schedule-strip-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--txt-faint); margin-right: 2px; }
    .mm-sched-pill { display: inline-flex; flex-direction: column; align-items: center; padding: 4px 9px; border-radius: 8px; background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20); cursor: default; }
    .mm-sched-pill-day { font-size: 0.65rem; font-weight: 700; color: var(--accent); line-height: 1.2; }
    .mm-sched-pill-time { font-size: 0.6rem; color: var(--txt-muted); line-height: 1.2; }
    .mm-no-schedule { font-size: 0.75rem; color: var(--txt-faint); display: flex; align-items: center; gap: 5px; }
    .mm-no-schedule a { color: var(--accent); text-decoration: none; }

    /* ── Layout ── */
    .mm-layout { display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem; align-items: start; }
    @media (max-width: 1100px) { .mm-layout { grid-template-columns: 1fr; } }

    /* ── Results ── */
    .mm-results-grid { display: flex; flex-direction: column; gap: 12px; }
    .mm-results-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-subtle); }
    .mm-results-title { font-family: 'Manrope', sans-serif; font-size: 1rem; font-weight: 700; color: var(--txt-primary); display: flex; align-items: center; gap: 8px; }
    .mm-count-pill { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; border-radius: 99px; background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20); font-size: 0.7rem; font-weight: 700; color: var(--accent); padding: 0 7px; }
    .mm-results-sub { font-size: 0.775rem; color: var(--txt-muted); }

    /* ── Card styles ── */
    .mm-team-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px; padding: 1.1rem 1.25rem;
        display: flex; align-items: flex-start; gap: 12px;
        transition: border-color 0.2s, background 0.15s, transform 0.15s;
        position: relative; overflow: hidden;
    }
    .mm-team-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; border-radius: 3px 0 0 3px; background: var(--accent);
        opacity: 0; transition: opacity 0.2s;
    }
    .mm-team-card:hover { border-color: rgba(163,177,75,0.28); background: var(--surface-3); transform: translateY(-1px); }
    .mm-team-card:hover::before, .mm-team-card.rank-1::before { opacity: 1; }
    .mm-team-card.rank-1 { border-color: rgba(163,177,75,0.25); }
    .mm-rank { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 0.75rem; flex-shrink: 0; margin-top: 3px; }
    .mm-rank-1 { background: rgba(251,191,36,0.15); color: #fcd34d; border: 1px solid rgba(251,191,36,0.25); }
    .mm-rank-2 { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.20); }
    .mm-rank-3 { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .mm-rank-n { background: var(--surface-4); color: var(--txt-faint); border: 1px solid var(--border-subtle); }
    .mm-card-avatar { width: 46px; height: 46px; border-radius: 12px; background: var(--accent-dim); border: 1.5px solid rgba(163,177,75,0.22); display: flex; align-items: center; justify-content: center; font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 1rem; color: var(--accent); flex-shrink: 0; }
    .mm-card-body { flex: 1; min-width: 0; }
    .mm-card-team-name { font-family: 'Manrope', sans-serif; font-size: 0.925rem; font-weight: 700; color: var(--txt-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
    .mm-card-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 7px; }
    .mm-card-meta-item { font-size: 0.71rem; color: var(--txt-muted); display: flex; align-items: center; gap: 3px; }
    .mm-card-slots { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 7px; }
    .mm-slot-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 0.67rem; font-weight: 600; padding: 2px 8px; border-radius: 99px; background: rgba(163,177,75,0.10); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .mm-slot-tag i { font-size: 0.65rem; }
    .mm-card-reasons { display: flex; flex-wrap: wrap; gap: 4px; }
    .mm-reason-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 0.67rem; font-weight: 600; padding: 2px 8px; border-radius: 99px; background: var(--surface-4); color: var(--txt-muted); border: 1px solid var(--border-subtle); }
    .mm-reason-tag i { font-size: 0.67rem; color: var(--accent); }
    .mm-score-block { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
    .mm-score-ring { position: relative; width: 54px; height: 54px; }
    .mm-score-ring svg { transform: rotate(-90deg); width: 54px; height: 54px; }
    .mm-score-ring-bg { fill: none; stroke: var(--surface-4); stroke-width: 4; }
    .mm-score-ring-fill { fill: none; stroke-width: 4; stroke-linecap: round; }
    .score-success { stroke: #86efac; } .score-accent { stroke: var(--accent); }
    .score-warning { stroke: #fcd34d; } .score-muted { stroke: var(--txt-faint); }
    .mm-score-number { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 0.85rem; color: var(--txt-primary); }
    .mm-score-label { font-size: 0.65rem; font-weight: 700; text-align: right; padding: 2px 7px; border-radius: 99px; white-space: nowrap; }
    .mm-score-label-success { background: rgba(134,239,172,0.12); color: #86efac; border: 1px solid rgba(134,239,172,0.20); }
    .mm-score-label-accent { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .mm-score-label-warning { background: rgba(251,191,36,0.10); color: #fcd34d; border: 1px solid rgba(251,191,36,0.20); }
    .mm-score-label-muted { background: var(--surface-4); color: var(--txt-muted); border: 1px solid var(--border-subtle); }
    .mm-challenge-btn { display: inline-flex; align-items: center; gap: 5px; font-size: 0.755rem; font-weight: 600; padding: 6px 13px; border-radius: 8px; background: var(--accent); color: var(--btn-primary-txt); border: none; cursor: pointer; transition: background 0.15s, transform 0.15s; font-family: 'Inter', sans-serif; white-space: nowrap; }
    .mm-challenge-btn:hover { background: var(--accent-hover); transform: translateY(-1px); }
    .mm-challenge-btn:active { transform: scale(0.98); }

    /* ── Idle ── */
    .mm-idle { text-align: center; padding: 4rem 1rem; }
    .mm-idle-icon { width: 68px; height: 68px; border-radius: 18px; background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20); display: flex; align-items: center; justify-content: center; font-size: 1.65rem; color: var(--accent); margin: 0 auto 1.25rem; }
    .mm-idle-icon.muted { background: var(--surface-4); border-color: var(--border-medium); color: var(--txt-faint); }
    .mm-idle h4 { font-family: 'Manrope', sans-serif; font-size: 1rem; font-weight: 700; color: var(--txt-secondary); margin-bottom: 6px; }
    .mm-idle p { font-size: 0.85rem; color: var(--txt-muted); max-width: 300px; margin: 0 auto; }

    /* ── Active filter tags ── */
    .mm-active-filters { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 1rem; }
    .mm-filter-tag { display: inline-flex; align-items: center; gap: 5px; font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 99px; background: var(--surface-4); color: var(--txt-secondary); border: 1px solid var(--border-medium); }
    .mm-filter-tag i { color: var(--accent); }

    /* ── Modal Challenge ── */
    #mm-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.60);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.22s ease;
    }
    #mm-backdrop.is-open {
        opacity: 1;
        pointer-events: all;
    }
    #mm-modal {
        background: var(--surface-2);
        border: 1px solid var(--border-medium);
        border-radius: 20px;
        padding: 1.5rem;
        width: 100%; max-width: 430px;
        transform: translateY(14px) scale(0.97);
        transition: transform 0.22s ease;
    }
    #mm-backdrop.is-open #mm-modal {
        transform: translateY(0) scale(1);
    }
    .mm-modal-hd {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.1rem;
    }
    .mm-modal-title {
        font-family: 'Manrope', sans-serif; font-size: 1rem; font-weight: 800;
        color: var(--txt-primary); display: flex; align-items: center; gap: 8px;
    }
    .mm-modal-title i { color: var(--accent); }
    .mm-modal-close-btn {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        background: var(--surface-4); border: 1px solid var(--border-subtle);
        color: var(--txt-muted); display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 0.875rem; transition: all 0.15s; line-height: 1;
    }
    .mm-modal-close-btn:hover { background: var(--surface-5); color: var(--txt-primary); }
    .mm-opponent-strip {
        background: var(--surface-3); border: 1px solid var(--border-subtle);
        border-radius: 12px; padding: 10px 14px;
        display: flex; align-items: center; gap: 10px; margin-bottom: 1.1rem;
    }
    .mm-opponent-ava {
        width: 38px; height: 38px; border-radius: 10px;
        background: var(--accent-dim); border: 1.5px solid rgba(163,177,75,0.25);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 0.85rem;
        color: var(--accent); flex-shrink: 0;
    }

    .mm-opponent-logo {
        width: 38px; height: 38px; border-radius: 10px;
        background-size: cover; background-position: center;
        flex-shrink: 0;
    }
    .mm-opponent-name { font-family: 'Manrope', sans-serif; font-size: 0.875rem; font-weight: 700; color: var(--txt-primary); }
    .mm-opponent-meta { font-size: 0.7rem; color: var(--txt-muted); margin-top: 1px; }
    .mm-alert {
        display: none; align-items: center; gap: 8px;
        padding: 9px 12px; border-radius: 9px;
        font-size: 0.78rem; font-weight: 600;
        margin-bottom: 1rem;
    }
    .mm-alert.show { display: flex; }
    .mm-alert.is-error { background: rgba(248,113,113,0.10); color: #f87171; border: 1px solid rgba(248,113,113,0.25); }
    .mm-alert.is-success { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.25); }
    .mm-field { margin-bottom: 1rem; }
    .mm-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.09em;
        color: var(--txt-faint); margin-bottom: 6px;
    }
    .mm-input {
        width: 100%; box-sizing: border-box;
        background: var(--surface-3); border: 1px solid var(--border-medium);
        border-radius: 10px; padding: 9px 12px;
        font-size: 0.85rem; color: var(--txt-primary);
        font-family: 'Inter', sans-serif; outline: none;
        transition: border-color 0.15s, background 0.15s;
        appearance: none; -webkit-appearance: none;
    }
    .mm-input:focus { border-color: var(--accent); background: var(--surface-4); }
    .mm-input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .mm-submit-btn {
        width: 100%; margin-top: 1.1rem; padding: 11px; border-radius: 11px;
        background: var(--accent); color: var(--btn-primary-txt);
        font-weight: 700; font-size: 0.875rem; font-family: 'Manrope', sans-serif;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background 0.15s, transform 0.15s, opacity 0.15s;
    }
    .mm-submit-btn:hover { background: var(--accent-hover); transform: translateY(-1px); }
    .mm-submit-btn:active { transform: scale(0.98); }
    .mm-submit-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none !important; }

    /* ── Matchmaking Loading Overlay ── */
    .mm-search-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(0,0,0,.72);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
    }

    .mm-search-overlay.is-active {
        opacity: 1;
        pointer-events: all;
    }

    .mm-search-card {
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 520px;
        border-radius: 24px;
        background: var(--surface-2);
        border: 1px solid var(--border-medium);
        padding: 2rem;
        box-shadow: 0 24px 80px rgba(0,0,0,.45);
    }

    .mm-search-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
        pointer-events: none;
    }

    .mm-search-grid-bg {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(var(--border-subtle) 1px, transparent 1px),
            linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
        background-size: 32px 32px;
        opacity: .22;
        pointer-events: none;
    }

    .mm-search-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .mm-radar {
        width: 132px;
        height: 132px;
        margin: 0 auto 1.4rem;
        border-radius: 999px;
        position: relative;
        background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,.25);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mm-radar::before,
    .mm-radar::after {
        content: "";
        position: absolute;
        inset: 12px;
        border-radius: 999px;
        border: 1px solid rgba(163,177,75,.25);
        animation: mmPulse 1.7s infinite ease-out;
    }

    .mm-radar::after {
        inset: 28px;
        animation-delay: .35s;
    }

    .mm-radar-line {
        position: absolute;
        width: 50%;
        height: 2px;
        left: 50%;
        top: 50%;
        background: linear-gradient(90deg, var(--accent), transparent);
        transform-origin: left center;
        animation: mmRadarSpin 1.4s linear infinite;
    }

    .mm-radar-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1.5rem;
        z-index: 2;
    }

    .mm-search-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--accent);
        background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,.20);
        border-radius: 999px;
        padding: 4px 12px;
        margin-bottom: 12px;
    }

    .mm-search-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--txt-primary);
        margin-bottom: 8px;
    }

    .mm-search-title span {
        color: var(--accent);
    }

    .mm-search-desc {
        font-size: .86rem;
        color: var(--txt-muted);
        margin-bottom: 1.2rem;
    }

    .mm-search-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 1.2rem;
    }

    .mm-search-stat {
        background: var(--surface-3);
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        padding: .85rem .75rem;
    }

    .mm-search-stat-value {
        font-family: 'Manrope', sans-serif;
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--accent);
    }

    .mm-search-stat-label {
        font-size: .68rem;
        color: var(--txt-muted);
        font-weight: 600;
        margin-top: 2px;
    }

    .mm-search-progress {
        width: 100%;
        height: 7px;
        border-radius: 99px;
        background: var(--surface-4);
        overflow: hidden;
        margin-top: 1.3rem;
    }

    .mm-search-progress-fill {
        height: 100%;
        width: 35%;
        border-radius: 99px;
        background: linear-gradient(90deg, var(--accent), rgba(163,177,75,.35));
        animation: mmProgress 1.15s infinite ease-in-out;
    }

    @keyframes mmRadarSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes mmPulse {
        0% {
            transform: scale(.7);
            opacity: .9;
        }
        100% {
            transform: scale(1.4);
            opacity: 0;
        }
    }

    @keyframes mmProgress {
        0% {
            transform: translateX(-120%);
        }
        100% {
            transform: translateX(320%);
        }
    }

    @media(max-width: 640px) {
        .mm-search-card {
            padding: 1.5rem;
        }

        .mm-search-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

@php
    $dayNames = [0=>'Min',1=>'Sen',2=>'Sel',3=>'Rab',4=>'Kam',5=>'Jum',6=>'Sab'];
    $dayNamesFull = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
@endphp

<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Matchmaking</span></li>
</ul>

<div class="mm-hero">
    <div class="mm-hero-grid"></div>
    <div class="mm-hero-content d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="mm-hero-eyebrow"><i class="bi bi-stars"></i> Core Feature</div>
            <h2>Temukan <span>Lawan Tanding</span><br>yang Tepat</h2>
            <p>Skor kecocokan dihitung otomatis dari level, jadwal, dan lokasi tim kamu.</p>
        </div>
        <div class="mm-hero-actions">
            <a href="{{ route('matchmaking.incoming') }}" class="mm-hero-link accent">
                <i class="bi bi-inbox"></i> Tantangan Masuk
            </a>
            <a href="{{ route('matchmaking.outgoing') }}" class="mm-hero-link subtle">
                <i class="bi bi-send"></i> Terkirim
            </a>
        </div>
    </div>
</div>

<div class="mm-my-team">
    @if ($myTeam->logo_path)
        <img src="{{ asset('storage/' . $myTeam->logo_path) }}" alt="Logo {{ $myTeam->name }}" class="mm-my-team-avatar" style="object-fit: cover;">
    @else
         <div class="mm-my-team-avatar">{{ strtoupper(substr($myTeam->name, 0, 2)) }}</div>
    @endif
    <div>
        <div class="mm-my-team-name">{{ $myTeam->name }}</div>
        <div class="mm-my-team-meta">
            <span><i class="bi bi-trophy" style="color:var(--accent)"></i> {{ ucfirst(str_replace('_',' ',$myTeam->level ?? '-')) }}</span>
            @if ($myTeam->city)
                <span><i class="bi bi-geo-alt" style="color:var(--accent)"></i> {{ $myTeam->city }}</span>
            @endif
        </div>
    </div>

    <div class="mm-schedule-strip">
        <span class="mm-schedule-strip-label">Jadwalku:</span>
        @forelse ($mySchedules as $sched)
            <div class="mm-sched-pill"
                 title="{{ $dayNamesFull[$sched->day_of_week] }} {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}">
                <span class="mm-sched-pill-day">{{ $dayNames[$sched->day_of_week] }}</span>
                <span class="mm-sched-pill-time">{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}</span>
            </div>
        @empty
            <span class="mm-no-schedule">
                <i class="bi bi-exclamation-circle"></i>
                Belum ada jadwal — <a href="{{ route('schedule.create') }}">Tambah sekarang</a>
            </span>
        @endforelse

        <a href="{{ route('schedule.index') }}" class="btn-outline-lime btn-sm" style="margin-left:4px;">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="mm-layout">

    @include('user.matchmaking.filter', ['filters' => $filters, 'mySchedules' => $mySchedules])

    <div>
        <div class="mm-results-header">
            <div class="mm-results-title">
                Hasil Matchmaking
                @if ($searched)
                    <span class="mm-count-pill">{{ $results->count() }}</span>
                @endif
            </div>

            @if ($searched && $results->count() > 0)
                <span class="mm-results-sub">Diurutkan: skor tertinggi</span>
            @endif
        </div>

        @if ($searched && (isset($filters['level']) || isset($filters['day_of_week']) || ($filters['use_my_schedule'] ?? false)))
            <div class="mm-active-filters">
                <span style="font-size:0.7rem;color:var(--txt-faint);font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Filter aktif:</span>

                @if (!empty($filters['level']))
                    <span class="mm-filter-tag"><i class="bi bi-trophy"></i> {{ ucfirst(str_replace('_',' ',$filters['level'])) }}</span>
                @endif

                @if (isset($filters['day_of_week']) && $filters['day_of_week'] !== '')
                    <span class="mm-filter-tag"><i class="bi bi-calendar3"></i> {{ $dayNamesFull[$filters['day_of_week']] }}</span>
                @endif

                @if ($filters['use_my_schedule'] ?? false)
                    <span class="mm-filter-tag"><i class="bi bi-calendar-check"></i> Sesuai jadwalku</span>
                @endif
            </div>
        @endif

        @if (!$searched)
            <div class="mm-idle">
                <div class="mm-idle-icon"><i class="bi bi-search-heart"></i></div>
                <h4>Siap Mencari Lawan?</h4>
                <p>Atur filter di panel kiri lalu klik <strong>Cari Lawan</strong>.</p>
            </div>
        @elseif ($results->isEmpty())
            <div class="mm-idle">
                <div class="mm-idle-icon muted"><i class="bi bi-emoji-frown"></i></div>
                <h4>Tidak Ada Tim Ditemukan</h4>
                <p>Coba perluas filter — hapus filter level, ganti hari, atau nonaktifkan filter jadwal.</p>
            </div>
        @else
            @include('user.matchmaking.results', ['results' => $results])
        @endif
    </div>
</div>

<div id="mm-backdrop" role="dialog" aria-modal="true" aria-labelledby="mm-modal-title-text">
    <div id="mm-modal">
        <div class="mm-modal-hd">
            <div class="mm-modal-title">
                <i class="bi bi-lightning-charge-fill"></i>
                <span id="mm-modal-title-text">Kirim Tantangan</span>
            </div>
            <button type="button" class="mm-modal-close-btn" id="mm-close-btn" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="mm-opponent-strip">
            <div class="mm-opponent-ava" id="mm-opp-ava"></div>
            <div class="mm-opponent-logo" id="mm-opp-logo"></div>
            <div>
                <div class="mm-opponent-name" id="mm-opp-name">—</div>
                <div class="mm-opponent-meta" id="mm-opp-meta">—</div>
            </div>
        </div>

        <div class="mm-alert" id="mm-alert" role="alert">
            <i class="bi" id="mm-alert-icon"></i>
            <span id="mm-alert-msg"></span>
        </div>

        <form id="mm-form" method="POST" action="#">
            @csrf

            <div class="mm-field">
                <label class="mm-label" for="mm-date">Tanggal Tanding</label>
                <input type="date" id="mm-date" name="preferred_date"
                       class="mm-input" min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mm-input-row">
                <div class="mm-field">
                    <label class="mm-label" for="mm-start">Mulai</label>
                    <input type="time" id="mm-start" name="start_time" class="mm-input" required>
                </div>
                <div class="mm-field">
                    <label class="mm-label" for="mm-end">Selesai</label>
                    <input type="time" id="mm-end" name="end_time" class="mm-input" required>
                </div>
            </div>

            <button type="submit" class="mm-submit-btn" id="mm-submit">
                <i class="bi bi-send-fill"></i>
                <span id="mm-submit-txt">Kirim Tantangan</span>
            </button>
        </form>
    </div>
</div>

<div class="mm-search-overlay" id="mm-search-overlay">
    <div class="mm-search-card">
        <div class="mm-search-grid-bg"></div>

        <div class="mm-search-content">
            <div class="mm-radar">
                <div class="mm-radar-line"></div>
                <div class="mm-radar-icon">
                    <i class="bi bi-search-heart"></i>
                </div>
            </div>

            <div class="mm-search-eyebrow">
                <i class="bi bi-controller"></i>
                Matchmaking System
            </div>

            <div class="mm-search-title">
                Mencari <span>Lawan Tanding</span>
            </div>

            <div class="mm-search-desc" id="mm-search-desc">
                Sistem sedang memindai tim berdasarkan level, jadwal, dan lokasi terbaik.
            </div>

            <div class="mm-search-stats">
                <div class="mm-search-stat">
                    <div class="mm-search-stat-value" id="mm-found-count">0</div>
                    <div class="mm-search-stat-label">Tim Ditemukan</div>
                </div>

                <div class="mm-search-stat">
                    <div class="mm-search-stat-value" id="mm-scan-count">0</div>
                    <div class="mm-search-stat-label">Dipindai</div>
                </div>

                <div class="mm-search-stat">
                    <div class="mm-search-stat-value" id="mm-match-percent">0%</div>
                    <div class="mm-search-stat-label">Kecocokan</div>
                </div>
            </div>

            <div class="mm-search-progress">
                <div class="mm-search-progress-fill"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const backdrop  = document.getElementById('mm-backdrop');
    const closeBtn  = document.getElementById('mm-close-btn');
    const form      = document.getElementById('mm-form');
    const oppAva    = document.getElementById('mm-opp-ava');
    const oppLogo   = document.getElementById('mm-opp-logo');
    const oppName   = document.getElementById('mm-opp-name');
    const oppMeta   = document.getElementById('mm-opp-meta');
    const submitBtn = document.getElementById('mm-submit');
    const submitTxt = document.getElementById('mm-submit-txt');
    const alertEl   = document.getElementById('mm-alert');
    const alertIcon = document.getElementById('mm-alert-icon');
    const alertMsg  = document.getElementById('mm-alert-msg');

    let currentTeamName = '';

    function openModal(btn) {
        const { action, name, logo, initials, meta } = btn.dataset;

        currentTeamName = name;
        if (logo) {
            const img = new Image();
            img.onload = () => {
                oppLogo.style.backgroundImage = `url('/storage/${logo}')`;
                oppLogo.style.display = 'block';
                oppAva.style.display = 'none';
            };
            img.onerror = () => {
                oppAva.textContent = initials;
                oppAva.style.display = 'block';
                oppLogo.style.display = 'none';
            };
            img.src = `/storage/${logo}`;
        } else {
            oppAva.textContent = initials;
            oppAva.style.display = 'block';
            oppLogo.style.display = 'none';
        }
        oppName.textContent = name;
        oppMeta.textContent = meta || '';
        submitTxt.textContent = 'Kirim Tantangan ke ' + name;

        form.action = action;
        form.reset();
        hideAlert();

        backdrop.classList.add('is-open');
        document.getElementById('mm-date').focus();
    }

    function closeModal() {
        backdrop.classList.remove('is-open');
    }

    function showAlert(type, msg) {
        alertEl.className = 'mm-alert show is-' + type;
        alertIcon.className = 'bi bi-' + (type === 'error' ? 'exclamation-triangle-fill' : 'check-circle-fill');
        alertMsg.textContent = msg;
    }

    function hideAlert() {
        alertEl.className = 'mm-alert';
        alertMsg.textContent = '';
    }

    function setLoading(on) {
        submitBtn.disabled = on;
        submitTxt.textContent = on ? 'Mengirim...' : ('Kirim Tantangan ke ' + currentTeamName);
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-open-challenge');

        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            openModal(btn);
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (backdrop) {
        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) closeModal();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && backdrop.classList.contains('is-open')) closeModal();
    });

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            hideAlert();
            setLoading(true);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert('success', data.message ?? 'Tantangan berhasil dikirim! 🔥');
                    setTimeout(closeModal, 1800);
                } else {
                    let msg = data.message ?? '';

                    if (!msg && data.errors) {
                        msg = Object.values(data.errors).flat().join(' ');
                    }

                    showAlert('error', msg || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            } catch (err) {
                showAlert('error', 'Gagal terhubung ke server. Periksa koneksi dan coba lagi.');
            } finally {
                setLoading(false);
            }
        });
    }
})();
</script>

<script>
(function () {
    const overlay = document.getElementById('mm-search-overlay');
    const foundCount = document.getElementById('mm-found-count');
    const scanCount = document.getElementById('mm-scan-count');
    const matchPercent = document.getElementById('mm-match-percent');
    const desc = document.getElementById('mm-search-desc');

    if (!overlay) return;

    function startMatchmakingLoading() {
        overlay.classList.add('is-active');

        let found = 0;
        let scanned = 0;
        let percent = 0;

        const messages = [
            'Menganalisis level tim...',
            'Mencocokkan jadwal bermain...',
            'Memindai lokasi terdekat...',
            'Menghitung skor kecocokan...',
            'Menyiapkan hasil terbaik...'
        ];

        let messageIndex = 0;

        foundCount.textContent = '0';
        scanCount.textContent = '0';
        matchPercent.textContent = '0%';
        desc.textContent = messages[0];

        clearInterval(window.mmSearchLoadingInterval);

        window.mmSearchLoadingInterval = setInterval(() => {
            scanned += Math.floor(Math.random() * 4) + 2;

            if (Math.random() > 0.45) {
                found += 1;
            }

            percent = Math.min(99, percent + Math.floor(Math.random() * 9) + 4);

            foundCount.textContent = found;
            scanCount.textContent = scanned;
            matchPercent.textContent = percent + '%';

            desc.textContent = messages[messageIndex];
            messageIndex = (messageIndex + 1) % messages.length;
        }, 450);
    }

    document.addEventListener('submit', function (event) {
        const form = event.target;

        if (form.id === 'mm-form') return;

        const isMatchmakingForm =
            form.closest('.mm-layout') ||
            form.action.includes('matchmaking') ||
            form.querySelector('[name="level"]') ||
            form.querySelector('[name="day_of_week"]') ||
            form.querySelector('[name="use_my_schedule"]');

        if (!isMatchmakingForm) return;

        startMatchmakingLoading();
    });
})();
</script>
@endpush