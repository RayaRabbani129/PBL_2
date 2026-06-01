{{-- resources/views/user/matches/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Pertandingan — MATCHGO')
@section('page-title', 'Pertandingan')

@push('styles')
<style>
    /* ── Hero ── */
    .matches-hero {
        position: relative; border-radius: 20px; overflow: hidden;
        padding: 2rem 2rem 1.75rem; margin-bottom: 1.5rem;
        background: var(--surface-2); border: 1px solid var(--border-subtle);
    }
    .matches-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
        pointer-events: none;
    }
    .matches-hero-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(var(--border-subtle) 1px, transparent 1px),
            linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
        background-size: 32px 32px; pointer-events: none; opacity: 0.35;
    }
    .matches-hero-content { position: relative; z-index: 1; }
    .matches-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--accent); background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,0.20); border-radius: 99px;
        padding: 4px 12px; margin-bottom: 14px;
    }
    .matches-hero h2 {
        font-family: 'Manrope', sans-serif; font-size: 1.6rem; font-weight: 800;
        color: var(--txt-primary); line-height: 1.2; margin-bottom: 8px;
    }
    .matches-hero h2 span { color: var(--accent); }
    .matches-hero p { font-size: 0.875rem; color: var(--txt-muted); max-width: 500px; margin-bottom: 0; }
    .matches-hero-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

    /* ── Stats ── */
    .matches-stats { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .matches-stat-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px; padding: 1rem 1.25rem;
        flex: 1; min-width: 130px;
        display: flex; align-items: center; gap: 12px;
        transition: border-color 0.2s, transform 0.15s;
    }
    .matches-stat-card:hover { border-color: rgba(163,177,75,0.25); transform: translateY(-1px); }
    .matches-stat-icon {
        width: 40px; height: 40px; border-radius: 11px;
        background: var(--accent-dim);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent); font-size: 1.05rem; flex-shrink: 0;
    }
    .matches-stat-val {
        font-family: 'Manrope', sans-serif; font-size: 1.5rem;
        font-weight: 800; color: var(--txt-primary); line-height: 1.1;
    }
    .matches-stat-label { font-size: 0.72rem; color: var(--txt-muted); font-weight: 500; margin-top: 2px; }

    /* ── Stat pulse animation saat angka berubah ── */
    @keyframes stat-pop {
        0%   { transform: scale(1); }
        40%  { transform: scale(1.25); color: var(--accent); }
        100% { transform: scale(1); }
    }
    .stat-pop { animation: stat-pop 0.4s ease; }

    /* ── Tabs ── */
    .matches-tabs {
        display: flex; gap: 2px;
        background: var(--surface-3); border: 1px solid var(--border-subtle);
        border-radius: 12px; padding: 4px; margin-bottom: 1.5rem;
        overflow-x: auto; scrollbar-width: none;
    }
    .matches-tabs::-webkit-scrollbar { display: none; }
    .matches-tab {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 9px;
        font-size: 0.825rem; font-weight: 600; color: var(--txt-muted);
        cursor: pointer; text-decoration: none; white-space: nowrap;
        border: 1px solid transparent; font-family: 'Inter', sans-serif;
        transition: all 0.15s;
    }
    .matches-tab:hover { color: var(--txt-primary); text-decoration: none; background: var(--surface-4); }
    .matches-tab.active {
        background: var(--surface-1); color: var(--txt-primary);
        border-color: var(--border-medium); box-shadow: var(--shadow-sm);
    }
    .tab-badge {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 18px; height: 18px; border-radius: 99px;
        background: var(--accent-dim); color: var(--accent);
        font-size: 0.62rem; font-weight: 700; padding: 0 5px;
        border: 1px solid rgba(163,177,75,0.20);
        transition: transform 0.2s;
    }
    .matches-tab.active .tab-badge { background: var(--accent); color: var(--btn-primary-txt); border-color: transparent; }
    .tab-badge.danger { background: rgba(239,68,68,0.12); color: #f87171; border-color: rgba(239,68,68,0.20); }
    .matches-tab.active .tab-badge.danger { background: #ef4444; color: #fff; border-color: transparent; }

    /* ── Section heading ── */
    .matches-section-heading {
        font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.10em; color: var(--txt-faint); margin-bottom: 10px;
        display: flex; align-items: center; gap: 8px;
    }
    .matches-section-heading::after { content: ''; flex: 1; height: 1px; background: var(--border-subtle); }

    /* ── Empty state ── */
    .matches-empty {
        text-align: center; padding: 4rem 1rem;
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px;
    }
    .matches-empty-icon {
        width: 68px; height: 68px; border-radius: 18px;
        background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.65rem; color: var(--accent); margin: 0 auto 1.25rem;
    }
    .matches-empty-icon.muted {
        background: var(--surface-4); border-color: var(--border-medium); color: var(--txt-faint);
    }
    .matches-empty h4 {
        font-family: 'Manrope', sans-serif; font-size: 1rem; font-weight: 700;
        color: var(--txt-secondary); margin-bottom: 6px;
    }
    .matches-empty p { font-size: 0.85rem; color: var(--txt-muted); max-width: 300px; margin: 0 auto; }

    /* ── Match card ── */
    .match-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px; padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        transition: border-color 0.2s, background 0.15s, transform 0.15s;
        position: relative; overflow: hidden; text-decoration: none;
    }
    .match-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; border-radius: 3px 0 0 3px;
    }
    .match-card.status-scheduled::before { background: var(--accent); }
    .match-card.status-completed::before { background: #86efac; }
    .match-card.status-cancelled::before { background: var(--txt-faint); }
    .match-card:hover {
        border-color: rgba(163,177,75,0.28); background: var(--surface-3);
        transform: translateY(-1px); text-decoration: none;
    }

    .match-card-teams {
        flex: 1; min-width: 200px; display: flex; align-items: center; gap: 10px;
    }
    .match-team-ava {
        width: 42px; height: 42px; border-radius: 11px;
        background: var(--accent-dim); border: 1.5px solid rgba(163,177,75,0.22);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 0.9rem;
        color: var(--accent); flex-shrink: 0;
    }
    .match-vs {
        font-family: 'Manrope', sans-serif; font-weight: 800;
        font-size: 0.75rem; color: var(--txt-faint); flex-shrink: 0;
    }
    .match-team-name {
        font-family: 'Manrope', sans-serif; font-size: 0.875rem;
        font-weight: 700; color: var(--txt-primary);
    }
    .match-team-label { font-size: 0.65rem; color: var(--txt-faint); margin-top: 2px; }

    .match-card-info { display: flex; flex-direction: column; gap: 4px; flex-shrink: 0; }
    .match-info-item {
        font-size: 0.75rem; color: var(--txt-muted);
        display: flex; align-items: center; gap: 5px;
    }
    .match-info-item i { color: var(--accent); font-size: 0.7rem; }

    .match-score {
        font-family: 'Manrope', sans-serif; font-size: 1.2rem;
        font-weight: 800; color: var(--txt-primary);
        flex-shrink: 0; letter-spacing: 0.05em;
    }

    .match-status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.68rem; font-weight: 700; padding: 4px 11px;
        border-radius: 99px; flex-shrink: 0;
    }
    .badge-scheduled {
        background: var(--accent-dim); color: var(--accent);
        border: 1px solid rgba(163,177,75,0.25);
    }
    .badge-completed {
        background: rgba(134,239,172,0.12); color: #86efac;
        border: 1px solid rgba(134,239,172,0.20);
    }
    .badge-cancelled {
        background: var(--surface-4); color: var(--txt-faint);
        border: 1px solid var(--border-subtle);
    }

    /* ── Challenge card ── */
    .challenge-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px; padding: 1.1rem 1.25rem;
        display: flex; align-items: flex-start; gap: 14px; flex-wrap: wrap;
        position: relative; overflow: hidden;
        transition: border-color 0.2s, background 0.15s, opacity 0.3s, transform 0.3s;
    }
    .challenge-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; border-radius: 3px 0 0 3px; background: var(--accent);
    }
    .challenge-card:hover { border-color: rgba(163,177,75,0.28); background: var(--surface-3); }
    .challenge-card.is-processing { opacity: 0.5; pointer-events: none; }
    .challenge-card.is-removing   { opacity: 0; transform: translateX(30px); }

    /* ── Card baru dari polling: slide-in dari atas ── */
    .challenge-card--new {
        opacity: 0;
        transform: translateY(-12px);
    }
    /* Transisi aktif setelah class --new dihapus */
    .challenge-card:not(.challenge-card--new):not(.is-removing) {
        transition: border-color 0.2s, background 0.15s,
                    opacity 0.35s ease, transform 0.35s ease;
    }

    .challenge-card-body { flex: 1; min-width: 0; }
    .challenge-team-name {
        font-family: 'Manrope', sans-serif; font-size: 0.925rem;
        font-weight: 700; color: var(--txt-primary); margin-bottom: 4px;
    }
    .challenge-meta {
        display: flex; flex-wrap: wrap; gap: 10px;
        font-size: 0.74rem; color: var(--txt-muted); margin-bottom: 10px;
    }
    .challenge-meta span { display: flex; align-items: center; gap: 4px; }
    .challenge-meta i { color: var(--accent); }
    .challenge-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

    /* ── Buttons ── */
    .btn-accept {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.775rem; font-weight: 600; padding: 7px 14px; border-radius: 8px;
        background: var(--accent); color: var(--btn-primary-txt);
        border: none; cursor: pointer; font-family: 'Inter', sans-serif;
        transition: background 0.15s, transform 0.15s;
    }
    .btn-accept:hover:not(:disabled) { background: var(--accent-hover); transform: translateY(-1px); }
    .btn-accept:active { transform: scale(0.98); }
    .btn-accept:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-reject {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.775rem; font-weight: 600; padding: 7px 14px; border-radius: 8px;
        background: var(--surface-4); color: var(--txt-secondary);
        border: 1px solid var(--border-medium); cursor: pointer;
        font-family: 'Inter', sans-serif; transition: all 0.15s;
    }
    .btn-reject:hover:not(:disabled) { border-color: #f87171; color: #f87171; background: rgba(248,113,113,0.08); }
    .btn-reject:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-cancel-sm {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.72rem; font-weight: 600; padding: 5px 11px; border-radius: 8px;
        background: transparent; border: 1px solid var(--border-medium);
        color: var(--txt-muted); cursor: pointer;
        font-family: 'Inter', sans-serif; transition: all 0.15s;
    }
    .btn-cancel-sm:hover { border-color: #f87171; color: #f87171; background: rgba(248,113,113,0.07); }

    .outgoing-status {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.7rem; font-weight: 700; padding: 4px 11px; border-radius: 99px;
        background: rgba(251,191,36,0.10); color: #fcd34d;
        border: 1px solid rgba(251,191,36,0.20);
    }

    /* ── Card avatar ── */
    .mm-card-avatar {
        width: 46px; height: 46px; border-radius: 12px;
        background: var(--accent-dim); border: 1.5px solid rgba(163,177,75,0.22);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif; font-weight: 800; font-size: 1rem;
        color: var(--accent); flex-shrink: 0;
    }

    /* ── Indikator polling aktif (titik hijau kecil di pojok kanan atas) ── */
    .poll-indicator {
        position: fixed; bottom: 1.5rem; left: 1.5rem; z-index: 9000;
        display: flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 600; color: var(--txt-faint);
        font-family: 'Inter', sans-serif;
        opacity: 0; transition: opacity 0.3s;
        pointer-events: none;
    }
    .poll-indicator.visible { opacity: 1; }
    .poll-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--accent);
    }
    @keyframes poll-ping {
        0%   { transform: scale(1); opacity: 1; }
        70%  { transform: scale(2.2); opacity: 0; }
        100% { transform: scale(2.2); opacity: 0; }
    }
    .poll-dot-wrap {
        position: relative; width: 7px; height: 7px;
    }
    .poll-dot-wrap::before {
        content: ''; position: absolute; inset: 0;
        border-radius: 50%; background: var(--accent);
        animation: poll-ping 1.2s ease-out;
    }

    /* ── Toast notifikasi AJAX ── */
    #ajax-toast {
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 99999;
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 12px;
        min-width: 260px; max-width: 360px;
        font-size: 0.85rem; font-weight: 600; font-family: 'Inter', sans-serif;
        box-shadow: 0 8px 24px rgba(0,0,0,0.35);
        transform: translateY(20px); opacity: 0;
        transition: opacity 0.25s, transform 0.25s;
        pointer-events: none;
    }
    #ajax-toast.show           { opacity: 1; transform: translateY(0); }
    #ajax-toast.toast-success  { background: var(--surface-2); border: 1px solid rgba(134,239,172,0.35); color: #86efac; }
    #ajax-toast.toast-error    { background: var(--surface-2); border: 1px solid rgba(248,113,113,0.35); color: #f87171; }
    #ajax-toast.toast-info     { background: var(--surface-2); border: 1px solid rgba(163,177,75,0.30);  color: var(--accent); }

    /* ── Spinner kecil ── */
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-spinner {
        width: 13px; height: 13px; border: 2px solid currentColor;
        border-top-color: transparent; border-radius: 50%;
        animation: spin .6s linear infinite;
        display: inline-block; flex-shrink: 0;
    }

    /* ── Reject modal ── */
    #reject-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.60);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 9999;
        display: flex; align-items: center; justify-content: center; padding: 1rem;
        opacity: 0; pointer-events: none; transition: opacity 0.22s ease;
    }
    #reject-backdrop.is-open { opacity: 1; pointer-events: all; }
    #reject-modal {
        background: var(--surface-2); border: 1px solid var(--border-medium);
        border-radius: 20px; padding: 1.5rem; width: 100%; max-width: 430px;
        transform: translateY(14px) scale(0.97); transition: transform 0.22s ease;
    }
    #reject-backdrop.is-open #reject-modal { transform: translateY(0) scale(1); }

    .rj-title {
        font-family: 'Manrope', sans-serif; font-size: 1rem; font-weight: 800;
        color: var(--txt-primary); display: flex; align-items: center; gap: 8px;
    }
    .rj-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.09em;
        color: var(--txt-faint); margin-bottom: 6px;
    }
    .rj-textarea {
        width: 100%; box-sizing: border-box;
        background: var(--surface-3); border: 1px solid var(--border-medium);
        border-radius: 10px; padding: 9px 12px;
        font-size: 0.85rem; color: var(--txt-primary);
        font-family: 'Inter', sans-serif; outline: none; resize: vertical;
        transition: border-color 0.15s, background 0.15s;
    }
    .rj-textarea:focus { border-color: var(--accent); background: var(--surface-4); }
    .rj-btn-confirm {
        width: 100%; margin-top: 1rem; padding: 11px; border-radius: 11px;
        background: rgba(248,113,113,0.15); color: #f87171;
        border: 1px solid rgba(248,113,113,0.30);
        font-weight: 700; font-size: 0.875rem; cursor: pointer;
        font-family: 'Manrope', sans-serif; transition: background 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .rj-btn-confirm:hover:not(:disabled) { background: rgba(248,113,113,0.25); }
    .rj-btn-confirm:disabled { opacity: 0.6; cursor: not-allowed; }
    .rj-close {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--surface-4); border: 1px solid var(--border-subtle);
        color: var(--txt-muted); display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 0.875rem; transition: all 0.15s;
    }
    .rj-close:hover { background: var(--surface-5); color: var(--txt-primary); }
    /* ── Cancel Modal ── */
    #cancel-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.60);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.22s ease;
    }

    #cancel-backdrop.is-open {
        opacity: 1;
        pointer-events: all;
    }

    #cancel-modal {
        background: var(--surface-2);
        border: 1px solid var(--border-medium);
        border-radius: 20px;
        padding: 1.5rem;
        width: 100%;
        max-width: 430px;
        transform: translateY(14px) scale(0.97);
        transition: transform 0.22s ease;
    }

    #cancel-backdrop.is-open #cancel-modal {
        transform: translateY(0) scale(1);
    }

    .cancel-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        font-weight: 800;
        color: var(--txt-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cancel-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--surface-4);
        border: 1px solid var(--border-subtle);
        color: var(--txt-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .cancel-desc {
        font-size: 0.85rem;
        color: var(--txt-muted);
        line-height: 1.6;
    }

    .cancel-team-box {
        background: var(--surface-3);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        padding: 10px 14px;
        margin: 1rem 0;
        color: var(--txt-primary);
        font-weight: 700;
    }

    .cancel-actions {
        display: flex;
        gap: 10px;
        margin-top: 1.2rem;
    }

    .cancel-btn {
        flex: 1;
        border-radius: 11px;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 0.84rem;
        cursor: pointer;
        border: none;
    }

    .cancel-btn-secondary {
        background: var(--surface-4);
        color: var(--txt-secondary);
        border: 1px solid var(--border-medium);
    }

    .cancel-btn-danger {
        background: rgba(248,113,113,0.15);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.30);
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Pertandingan</span></li>
</ul>

{{-- Alert flash (fallback non-AJAX) --}}
@foreach (['success','error','info','warning'] as $msg)
    @if (session($msg))
        <div class="alert-matchgo alert-{{ $msg }} mb-3">
            <i class="bi bi-{{ $msg === 'success' ? 'check-circle-fill' : ($msg === 'error' ? 'exclamation-triangle-fill' : 'info-circle-fill') }}"></i>
            {{ session($msg) }}
        </div>
    @endif
@endforeach

{{-- Hero --}}
<div class="matches-hero">
    <div class="matches-hero-grid"></div>
    <div class="matches-hero-content d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="matches-hero-eyebrow"><i class="bi bi-calendar2-heart"></i> Match Saya</div>
            <h2>Kelola <span>Pertandingan</span><br>Timmu</h2>
            <p>Terima tantangan, jadwalkan laga, dan pantau riwayat pertandingan timmu.</p>
        </div>
        <div class="matches-hero-actions">
            <a href="{{ route('matchmaking.index') }}" class="btn-lime btn-sm">
                <i class="bi bi-search-heart"></i> Cari Lawan Baru
            </a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="matches-stats">
    <div class="matches-stat-card">
        <div class="matches-stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="matches-stat-val" id="stat-upcoming">{{ $counts['upcoming'] }}</div>
            <div class="matches-stat-label">Mendatang</div>
        </div>
    </div>
    <div class="matches-stat-card">
        <div class="matches-stat-icon" style="background:rgba(134,239,172,0.12);color:#86efac;">
            <i class="bi bi-trophy"></i>
        </div>
        <div>
            <div class="matches-stat-val">{{ $counts['completed'] }}</div>
            <div class="matches-stat-label">Selesai</div>
        </div>
    </div>
    <div class="matches-stat-card">
        <div class="matches-stat-icon" style="background:rgba(239,68,68,0.10);color:#f87171;">
            <i class="bi bi-lightning-charge"></i>
        </div>
        <div>
            <div class="matches-stat-val" id="stat-incoming">{{ $counts['incoming'] }}</div>
            <div class="matches-stat-label">Tantangan Masuk</div>
        </div>
    </div>
    <div class="matches-stat-card">
        <div class="matches-stat-icon" style="background:rgba(251,191,36,0.10);color:#fcd34d;">
            <i class="bi bi-send"></i>
        </div>
        <div>
            <div class="matches-stat-val" id="stat-outgoing">{{ $counts['outgoing'] }}</div>
            <div class="matches-stat-label">Tantangan Terkirim</div>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="matches-tabs">
    @php
        $tabs = [
            ['key'=>'upcoming',  'label'=>'Mendatang',       'icon'=>'bi-calendar-event',  'count'=>$counts['upcoming'],  'danger'=>false],
            ['key'=>'incoming',  'label'=>'Tantangan Masuk', 'icon'=>'bi-lightning-charge', 'count'=>$counts['incoming'],  'danger'=>true],
            ['key'=>'outgoing',  'label'=>'Terkirim',        'icon'=>'bi-send',             'count'=>$counts['outgoing'],  'danger'=>false],
            ['key'=>'completed', 'label'=>'Selesai',         'icon'=>'bi-trophy',           'count'=>$counts['completed'], 'danger'=>false],
        ];
    @endphp
    @foreach ($tabs as $t)
        <a href="{{ route('matches.index', ['tab' => $t['key']]) }}"
           class="matches-tab {{ $tab === $t['key'] ? 'active' : '' }}">
            <i class="bi {{ $t['icon'] }}"></i>
            {{ $t['label'] }}
            <span class="tab-badge {{ $t['danger'] ? 'danger' : '' }}"
                  id="tab-badge-{{ $t['key'] }}"
                  @if($t['count'] === 0) style="display:none;" @endif>
                {{ $t['count'] }}
            </span>
        </a>
    @endforeach
</div>

{{-- ── TAB: Mendatang ── --}}
@if ($tab === 'upcoming')
    @if ($upcoming->isEmpty())
        <div class="matches-empty">
            <div class="matches-empty-icon muted"><i class="bi bi-calendar-x"></i></div>
            <h4>Belum Ada Match Terjadwal</h4>
            <p>Cari lawan lewat fitur matchmaking dan mulai pertandingan pertamamu!</p>
        </div>
    @else
        <div class="matches-section-heading">{{ $upcoming->count() }} match mendatang</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach ($upcoming as $match)
                @php
                    $isHome         = $match->home_team_id === $myTeam->id;
                    $myTeamInMatch  = $isHome ? $match->homeTeam : $match->awayTeam;
                    $oppTeamInMatch = $isHome ? $match->awayTeam : $match->homeTeam;
                @endphp
                <a href="{{ route('matches.show', $match) }}" class="match-card status-scheduled">
                    <div class="match-card-teams">
                        <div>
                            @if ($myTeamInMatch && $myTeamInMatch->logo_path)
                                <img src="{{ asset('storage/' . $myTeamInMatch->logo_path) }}" alt="{{ $myTeamInMatch->name }} Logo"
                                     class="match-team-ava" style="object-fit:cover;">
                            @else
                                <div class="match-team-ava">{{ strtoupper(substr($myTeamInMatch->name, 0, 2)) }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="match-team-name">{{ $myTeamInMatch->name }}</div>
                            <div class="match-team-label">Tim Saya</div>
                        </div>
                        <div class="match-vs">VS</div>
                        <div>
                            @if ($oppTeamInMatch && $oppTeamInMatch->logo_path)
                                <img src="{{ asset('storage/' . $oppTeamInMatch->logo_path) }}" alt="{{ $oppTeamInMatch->name }} Logo"
                                     class="match-team-ava" style="object-fit:cover;">
                            @else
                                <div class="match-team-ava" style="background:var(--surface-4);border-color:var(--border-medium);color:var(--txt-secondary);">
                                    {{ strtoupper(substr($oppTeamInMatch->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="match-team-name">{{ $oppTeamInMatch->name }}</div>
                            <div class="match-team-label">Lawan</div>
                        </div>
                    </div>
                    <div class="match-card-info">
                        <div class="match-info-item">
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($match->match_datetime)->translatedFormat('l, d M Y') }}
                        </div>
                        <div class="match-info-item">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($match->match_datetime)->format('H:i') }}
                        </div>
                        @if ($match->venue)
                            <div class="match-info-item">
                                <i class="bi bi-geo-alt"></i>
                                {{ $match->venue->name }}
                            </div>
                        @endif
                    </div>
                    <span class="match-status-badge badge-scheduled">
                        <i class="bi bi-calendar-check"></i> Terjadwal
                    </span>
                </a>
            @endforeach
        </div>
    @endif

{{-- ── TAB: Tantangan Masuk ── --}}
@elseif ($tab === 'incoming')
    <div id="incoming-list">
        @if ($incoming->isEmpty())
            <div class="matches-empty">
                <div class="matches-empty-icon muted"><i class="bi bi-inbox"></i></div>
                <h4>Tidak Ada Tantangan Masuk</h4>
                <p>Tantangan dari tim lain akan muncul di sini.</p>
            </div>
        @else
            <div class="matches-section-heading" id="incoming-heading">
                {{ $incoming->count() }} tantangan menunggu respons
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;" id="incoming-cards">
                @foreach ($incoming as $req)
                    @php $challenger = $req->team; @endphp
                    <div class="challenge-card" data-challenge-id="{{ $req->id }}">
                        @if ($challenger && $challenger->logo_path)
                            <img src="{{ asset('storage/' . $challenger->logo_path) }}" alt="{{ $challenger->name }} Logo"
                                 class="mm-card-avatar" style="object-fit:cover;">
                        @else
                            <div class="mm-card-avatar">
                                {{ strtoupper(substr($challenger->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="challenge-card-body">
                            <div class="challenge-team-name">{{ $challenger->name }}</div>
                            <div class="challenge-meta">
                                <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($req->preferred_date)->translatedFormat('l, d M Y') }}</span>
                                <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}</span>
                                @if ($challenger->city)
                                    <span><i class="bi bi-geo-alt"></i> {{ $challenger->city }}</span>
                                @endif
                                @if ($challenger->level)
                                    <span><i class="bi bi-trophy"></i> {{ ucfirst(str_replace('_', ' ', $challenger->level)) }}</span>
                                @endif
                            </div>
                            <div class="challenge-actions">
                                <button type="button"
                                        class="btn-accept js-accept-challenge"
                                        data-accept-url="{{ route('matches.challenge.accept', $req) }}"
                                        data-challenge-id="{{ $req->id }}">
                                    <i class="bi bi-check-lg"></i> Terima
                                </button>
                                <button type="button"
                                        class="btn-reject js-open-reject"
                                        data-reject-url="{{ route('matches.challenge.reject', $req) }}"
                                        data-challenge-id="{{ $req->id }}"
                                        data-name="{{ $challenger->name }}">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

{{-- ── TAB: Terkirim ── --}}
@elseif ($tab === 'outgoing')
    @if ($outgoing->isEmpty())
        <div class="matches-empty">
            <div class="matches-empty-icon muted"><i class="bi bi-send-x"></i></div>
            <h4>Belum Ada Tantangan Terkirim</h4>
            <p>Tantangan yang kamu kirim lewat matchmaking muncul di sini.</p>
        </div>
    @else
        <div class="matches-section-heading">{{ $outgoing->count() }} tantangan terkirim</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach ($outgoing as $req)
                @php $opponent = $req->matchedTeam; @endphp
                <div class="challenge-card">
                    @if ($opponent && $opponent->logo_path)
                        <img src="{{ asset('storage/' . $opponent->logo_path) }}" alt="{{ $opponent->name }} Logo"
                             class="mm-card-avatar" style="object-fit:cover;">
                    @else
                        <div class="mm-card-avatar" style="background:var(--surface-4);border-color:var(--border-medium);color:var(--txt-secondary);">
                            {{ $opponent ? strtoupper(substr($opponent->name, 0, 2)) : '?' }}
                        </div>
                    @endif
                    <div class="challenge-card-body">
                        <div class="challenge-team-name">{{ $opponent->name ?? 'Tim tidak ditemukan' }}</div>
                        <div class="challenge-meta">
                            <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($req->preferred_date)->translatedFormat('l, d M Y') }}</span>
                            <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="challenge-actions">
                            <span class="outgoing-status">
                                <i class="bi bi-hourglass-split"></i> Menunggu Respons
                            </span>
                            <button
                                type="button"
                                class="btn-cancel-sm js-open-cancel"
                                data-cancel-url="{{ route('matchmaking.cancel', $req) }}"
                                data-challenge-id="{{ $req->id }}"
                                data-team="{{ $opponent->name ?? 'Tim tidak ditemukan' }}"
                            >
                                <i class="bi bi-trash3"></i> Batalkan
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

{{-- ── TAB: Selesai ── --}}
@elseif ($tab === 'completed')
    @if ($completed->isEmpty())
        <div class="matches-empty">
            <div class="matches-empty-icon muted"><i class="bi bi-trophy"></i></div>
            <h4>Belum Ada Match Selesai</h4>
            <p>Riwayat pertandingan yang sudah selesai akan tampil di sini.</p>
        </div>
    @else
        <div class="matches-section-heading">{{ $completed->count() }} match selesai</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach ($completed as $match)
                @php
                    $isHome         = $match->home_team_id === $myTeam->id;
                    $myTeamInMatch  = $isHome ? $match->homeTeam : $match->awayTeam;
                    $oppTeamInMatch = $isHome ? $match->awayTeam : $match->homeTeam;
                    $myScore        = $isHome ? $match->home_score : $match->away_score;
                    $oppScore       = $isHome ? $match->away_score : $match->home_score;
                @endphp
                <a href="{{ route('matches.show', $match) }}" class="match-card status-completed">
                    <div class="match-card-teams">
                        <div>
                            @if ($myTeamInMatch->logo_path)
                                <img src="{{ asset('storage/' . $myTeamInMatch->logo_path) }}" alt="{{ $myTeamInMatch->name }} Logo"
                                     class="match-team-ava" style="object-fit:cover;">
                            @else
                                <div class="match-team-ava">{{ strtoupper(substr($myTeamInMatch->name, 0, 2)) }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="match-team-name">{{ $myTeamInMatch->name }}</div>
                            <div class="match-team-label">Tim Saya</div>
                        </div>
                        <div class="match-score">{{ $myScore }} – {{ $oppScore }}</div>
                        <div>
                            @if ($oppTeamInMatch->logo_path)
                                <img src="{{ asset('storage/' . $oppTeamInMatch->logo_path) }}" alt="{{ $oppTeamInMatch->name }} Logo"
                                     class="match-team-ava" style="object-fit:cover;">
                            @else
                                <div class="match-team-ava" style="background:var(--surface-4);border-color:var(--border-medium);color:var(--txt-secondary);">
                                    {{ strtoupper(substr($oppTeamInMatch->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="match-team-name">{{ $oppTeamInMatch->name }}</div>
                            <div class="match-team-label">Lawan</div>
                        </div>
                    </div>
                    <div class="match-card-info">
                        <div class="match-info-item">
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($match->match_datetime)->translatedFormat('d M Y') }}
                        </div>
                    </div>
                    <span class="match-status-badge badge-completed">
                        <i class="bi bi-check-circle-fill"></i> Selesai
                    </span>
                </a>
            @endforeach
        </div>
    @endif
@endif

{{-- ── Reject Modal ── --}}
<div id="reject-backdrop" onclick="if(event.target===this)rejectClose()" role="dialog" aria-modal="true">
    <div id="reject-modal">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="rj-title">
                <i class="bi bi-x-circle-fill" style="color:#f87171;"></i>
                Tolak Tantangan dari <span id="rj-team-name" style="color:var(--accent);margin-left:4px;"></span>
            </div>
            <button type="button" class="rj-close" onclick="rejectClose()" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="margin-bottom:1rem;">
            <label class="rj-label" for="rj-reason">Alasan penolakan (opsional)</label>
            <textarea id="rj-reason" class="rj-textarea" rows="3"
                placeholder="Contoh: Jadwal bentrok, lokasi terlalu jauh..."></textarea>
        </div>
        <button type="button" id="rj-confirm-btn" class="rj-btn-confirm">
            <i class="bi bi-x-circle"></i> Konfirmasi Penolakan
        </button>
    </div>
</div>

{{-- ── Polling indicator ── --}}
<div class="poll-indicator" id="poll-indicator">
    <div class="poll-dot-wrap"><div class="poll-dot"></div></div>
    Live
</div>

{{-- ── Toast AJAX ── --}}
<div id="ajax-toast"></div>
{{-- ── Cancel Modal ── --}}
<div id="cancel-backdrop" role="dialog" aria-modal="true">
    <div id="cancel-modal">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="cancel-title">
                <i class="bi bi-trash3-fill" style="color:#f87171;"></i>
                Batalkan Tantangan
            </div>

            <button type="button" class="cancel-close" id="cancel-close" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="cancel-desc">
            Kamu yakin ingin membatalkan tantangan yang sudah dikirim ke tim ini?
        </div>

        <div class="cancel-team-box" id="cancel-team-name">
            -
        </div>

        <div class="cancel-desc">
            Setelah dibatalkan, tim lawan tidak bisa menerima tantangan ini lagi.
        </div>

        <div class="cancel-actions">
            <button type="button" class="cancel-btn cancel-btn-secondary" id="cancel-no">
                Tidak
            </button>

            <button type="button" class="cancel-btn cancel-btn-danger" id="cancel-yes">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ─────────────────────────────────────────────────────────
// Cancel outgoing challenge — modal
// ─────────────────────────────────────────────────────────
const cancelBackdrop = document.getElementById('cancel-backdrop');
const cancelCloseBtn = document.getElementById('cancel-close');
const cancelNoBtn    = document.getElementById('cancel-no');
const cancelYesBtn   = document.getElementById('cancel-yes');
const cancelTeamName = document.getElementById('cancel-team-name');

let pendingCancelUrl = null;

function cancelClose() {
    cancelBackdrop?.classList.remove('is-open');
    pendingCancelUrl = null;

    if (cancelYesBtn) {
        cancelYesBtn.disabled = false;
        cancelYesBtn.innerHTML = 'Ya, Batalkan';
    }
}

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.js-open-cancel');
    if (!btn) return;

    e.preventDefault();

    pendingCancelUrl = btn.dataset.cancelUrl;

    if (cancelTeamName) {
        cancelTeamName.textContent = btn.dataset.team || 'Tim lawan';
    }

    cancelBackdrop?.classList.add('is-open');
});

cancelCloseBtn?.addEventListener('click', cancelClose);
cancelNoBtn?.addEventListener('click', cancelClose);

cancelBackdrop?.addEventListener('click', function (e) {
    if (e.target === cancelBackdrop) {
        cancelClose();
    }
});

cancelYesBtn?.addEventListener('click', function () {
    if (!pendingCancelUrl) return;

    cancelYesBtn.disabled = true;
    cancelYesBtn.innerHTML = '<span class="btn-spinner"></span> Membatalkan...';

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = pendingCancelUrl;

    form.innerHTML = `
        <input
            type="hidden"
            name="_token"
            value="{{ csrf_token() }}"
        >

        <input
            type="hidden"
            name="_method"
            value="DELETE"
        >
    `;

    document.body.appendChild(form);
    form.submit();
});

(function () {
    'use strict';

    const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';
    const POLL_URL = '{{ route("matches.poll") }}';
    const POLL_MS  = 8000; // interval polling (ms)

    // ─────────────────────────────────────────────────────────
    // Toast helper
    // ─────────────────────────────────────────────────────────
    const toastEl = document.getElementById('ajax-toast');
    let toastTimer;

    function showToast(message, type = 'success') {
        const icons = {
            success : 'bi-check-circle-fill',
            error   : 'bi-exclamation-triangle-fill',
            info    : 'bi-info-circle-fill',
        };
        toastEl.className = `toast-${type}`;
        toastEl.innerHTML = `<i class="bi ${icons[type] ?? icons.info}"></i> ${message}`;
        toastEl.classList.remove('show');
        void toastEl.offsetWidth; // reflow
        toastEl.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 3500);
    }

    // ─────────────────────────────────────────────────────────
    // Polling indicator (titik hijau "Live")
    // ─────────────────────────────────────────────────────────
    const pollIndicator = document.getElementById('poll-indicator');
    let indicatorTimer;

    function flashIndicator() {
        // Rebuild inner agar animasi ping re-trigger
        pollIndicator.innerHTML = `
            <div class="poll-dot-wrap"><div class="poll-dot"></div></div>
            Live`;
        pollIndicator.classList.add('visible');
        clearTimeout(indicatorTimer);
        indicatorTimer = setTimeout(() => pollIndicator.classList.remove('visible'), 1500);
    }

    // ─────────────────────────────────────────────────────────
    // Animasi hapus card
    // ─────────────────────────────────────────────────────────
    function removeCard(challengeId, onDone) {
        const card = document.querySelector(`.challenge-card[data-challenge-id="${challengeId}"]`);
        if (!card) { onDone?.(); return; }
        card.classList.add('is-removing');
        card.addEventListener('transitionend', () => { card.remove(); onDone?.(); }, { once: true });
    }

    // ─────────────────────────────────────────────────────────
    // Update counter helpers
    // ─────────────────────────────────────────────────────────
    function setCount(statId, badgeId, n, isDanger = false) {
        const statEl = document.getElementById(statId);
        if (statEl) {
            const prev = parseInt(statEl.textContent, 10);
            if (prev !== n) {
                statEl.textContent = n;
                // Animasi pop hanya jika angka naik
                if (n > prev) {
                    statEl.classList.remove('stat-pop');
                    void statEl.offsetWidth;
                    statEl.classList.add('stat-pop');
                    statEl.addEventListener('animationend', () => statEl.classList.remove('stat-pop'), { once: true });
                }
            }
        }
        const badge = document.getElementById(badgeId);
        if (badge) {
            badge.textContent   = n;
            badge.style.display = n > 0 ? '' : 'none';
        }
    }

    function decrementIncoming() {
        const statEl  = document.getElementById('stat-incoming');
        const current = statEl ? Math.max(0, parseInt(statEl.textContent, 10) - 1) : 0;
        setCount('stat-incoming', 'tab-badge-incoming', current, true);

        const remaining = document.querySelectorAll('#incoming-cards .challenge-card');
        if (remaining.length === 0) {
            const list = document.getElementById('incoming-list');
            if (list) {
                list.innerHTML = `
                    <div class="matches-empty">
                        <div class="matches-empty-icon muted"><i class="bi bi-inbox"></i></div>
                        <h4>Tidak Ada Tantangan Masuk</h4>
                        <p>Tantangan dari tim lain akan muncul di sini.</p>
                    </div>`;
            }
        } else {
            const heading = document.getElementById('incoming-heading');
            if (heading) heading.firstChild.textContent = `${remaining.length} tantangan menunggu respons `;
        }
    }

    // ─────────────────────────────────────────────────────────
    // AJAX POST helper
    // ─────────────────────────────────────────────────────────
    async function ajaxPost(url, payload = {}) {
        const res = await fetch(url, {
            method  : 'POST',
            headers : {
                'Content-Type' : 'application/json',
                'Accept'       : 'application/json',
                'X-CSRF-TOKEN' : CSRF,
            },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        return { ok: res.ok, data };
    }

    // ─────────────────────────────────────────────────────────
    // Render card tantangan baru (dari polling)
    // ─────────────────────────────────────────────────────────
    function renderChallengeCard(req) {
        const div = document.createElement('div');
        div.className = 'challenge-card challenge-card--new';
        div.dataset.challengeId = req.id;
        div.innerHTML = `
            <div class="mm-card-avatar">${escHtml(req.team_initials)}</div>
            <div class="challenge-card-body">
                <div class="challenge-team-name">${escHtml(req.team_name)}</div>
                <div class="challenge-meta">
                    <span><i class="bi bi-calendar-event"></i> ${escHtml(req.preferred_date)}</span>
                    <span><i class="bi bi-clock"></i> ${escHtml(req.start_time)} – ${escHtml(req.end_time)}</span>
                    ${req.team_city  ? `<span><i class="bi bi-geo-alt"></i> ${escHtml(req.team_city)}</span>` : ''}
                    ${req.team_level ? `<span><i class="bi bi-trophy"></i> ${escHtml(req.team_level)}</span>` : ''}
                </div>
                <div class="challenge-actions">
                    <button type="button"
                            class="btn-accept js-accept-challenge"
                            data-accept-url="${escHtml(req.accept_url)}"
                            data-challenge-id="${req.id}">
                        <i class="bi bi-check-lg"></i> Terima
                    </button>
                    <button type="button"
                            class="btn-reject js-open-reject"
                            data-reject-url="${escHtml(req.reject_url)}"
                            data-challenge-id="${req.id}"
                            data-name="${escHtml(req.team_name)}">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>
            </div>`;
        return div;
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ─────────────────────────────────────────────────────────
    // Polling — rekonsiliasi DOM
    // ─────────────────────────────────────────────────────────
    // Catat ID yang sudah ada saat page load (tidak akan di-toast)
    const seenIds = new Set(
        [...document.querySelectorAll('.challenge-card[data-challenge-id]')]
            .map(el => el.dataset.challengeId)
    );

    async function pollIncoming() {
        try {
            const res = await fetch(POLL_URL, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            });
            if (!res.ok) return;

            const { incoming, incoming_count, upcoming_count, outgoing_count } = await res.json();

            flashIndicator();

            // Update semua badge/stat
            setCount('stat-incoming', 'tab-badge-incoming', incoming_count, true);
            setCount('stat-upcoming', 'tab-badge-upcoming', upcoming_count, false);
            setCount('stat-outgoing', 'tab-badge-outgoing', outgoing_count, false);

            // DOM reconciliation hanya kalau tab incoming sedang aktif
            const isIncomingTab = !!document.getElementById('incoming-list');
            if (!isIncomingTab) return;

            const serverIds = new Set(incoming.map(r => String(r.id)));
            let addedCount  = 0;

            // ── Tambah kartu baru ──
            for (const req of incoming) {
                const sid = String(req.id);
                if (!document.querySelector(`.challenge-card[data-challenge-id="${sid}"]`)) {
                    // Pastikan container ada; jika sebelumnya empty-state, rebuild struktur
                    let cards = document.getElementById('incoming-cards');
                    if (!cards) {
                        const list = document.getElementById('incoming-list');
                        list.innerHTML = `
                            <div class="matches-section-heading" id="incoming-heading">
                                0 tantangan menunggu respons
                            </div>
                            <div style="display:flex;flex-direction:column;gap:10px;"
                                 id="incoming-cards"></div>`;
                        cards = document.getElementById('incoming-cards');
                    }

                    const card = renderChallengeCard(req);
                    cards.prepend(card);

                    // Trigger slide-in: hapus class --new di frame berikutnya
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => card.classList.remove('challenge-card--new'));
                    });
                    addedCount++;
                }
                seenIds.add(sid);
            }

            // ── Hapus kartu yang tidak ada lagi di server
            //    (sudah accepted/rejected oleh pihak lain atau expired)
            document.querySelectorAll('.challenge-card[data-challenge-id]').forEach(el => {
                if (!serverIds.has(el.dataset.challengeId) && !el.classList.contains('is-processing')) {
                    el.classList.add('is-removing');
                    el.addEventListener('transitionend', () => el.remove(), { once: true });
                }
            });

            // Update heading
            const heading = document.getElementById('incoming-heading');
            if (heading && incoming_count > 0) {
                heading.firstChild.textContent = `${incoming_count} tantangan menunggu respons `;
            }

            // Toast & notifikasi browser hanya untuk tantangan benar-benar baru
            if (addedCount > 0) {
                showToast(`⚡ ${addedCount} tantangan baru masuk!`, 'info');

                if (Notification.permission === 'granted') {
                    new Notification('MATCHGO – Tantangan Baru! ⚡', {
                        body : `${addedCount} tantangan baru menunggu responsmu.`,
                        icon : '/favicon.ico',
                    });
                }
            }

        } catch {
            // Silent fail — jangan ganggu UX saat offline / server error
        }
    }

    // Minta izin notifikasi browser sekali
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Mulai polling setelah 2 detik (beri waktu halaman selesai render)
    setTimeout(() => {
        pollIncoming();                        // langsung sekali
        setInterval(pollIncoming, POLL_MS);    // lalu setiap 8 detik
    }, 2000);

    // ─────────────────────────────────────────────────────────
    // Accept challenge (AJAX)
    // ─────────────────────────────────────────────────────────
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.js-accept-challenge');
        if (!btn) return;

        const url         = btn.dataset.acceptUrl;
        const challengeId = btn.dataset.challengeId;
        const card        = btn.closest('.challenge-card');

        card?.querySelectorAll('button').forEach(b => b.disabled = true);
        btn.innerHTML = '<span class="btn-spinner"></span> Memproses...';
        card?.classList.add('is-processing');

        try {
            const { ok, data } = await ajaxPost(url);
            if (ok && data.success) {
                showToast(data.message, 'success');
                removeCard(challengeId, () => {
                    decrementIncoming();
                    if (data.match_url) {
                        setTimeout(() => { window.location.href = data.match_url; }, 600);
                    }
                });
            } else {
                showToast(data.message ?? 'Terjadi kesalahan.', 'error');
                resetCard(card, btn, '<i class="bi bi-check-lg"></i> Terima');
            }
        } catch {
            showToast('Gagal terhubung ke server.', 'error');
            resetCard(card, btn, '<i class="bi bi-check-lg"></i> Terima');
        }
    });

    function resetCard(card, btn, originalHtml) {
        card?.classList.remove('is-processing');
        card?.querySelectorAll('button').forEach(b => b.disabled = false);
        btn.innerHTML = originalHtml;
    }

    // ─────────────────────────────────────────────────────────
    // Reject modal — buka
    // ─────────────────────────────────────────────────────────
    const backdrop  = document.getElementById('reject-backdrop');
    const rjName    = document.getElementById('rj-team-name');
    const rjReason  = document.getElementById('rj-reason');
    const rjConfirm = document.getElementById('rj-confirm-btn');

    let pendingRejectUrl = null;
    let pendingRejectId  = null;

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-open-reject');
        if (!btn) return;

        pendingRejectUrl   = btn.dataset.rejectUrl;
        pendingRejectId    = btn.dataset.challengeId;
        rjName.textContent = btn.dataset.name;
        rjReason.value     = '';

        rjConfirm.disabled  = false;
        rjConfirm.innerHTML = '<i class="bi bi-x-circle"></i> Konfirmasi Penolakan';

        backdrop.classList.add('is-open');
        setTimeout(() => rjReason.focus(), 200);
    });

    window.rejectClose = function () { backdrop.classList.remove('is-open'); };
    document.addEventListener('keydown', e => { if (e.key === 'Escape') rejectClose(); });

    // ─────────────────────────────────────────────────────────
    // Reject challenge — konfirmasi via AJAX
    // ─────────────────────────────────────────────────────────
    rjConfirm?.addEventListener('click', async function () {
        if (!pendingRejectUrl) return;

        rjConfirm.disabled  = true;
        rjConfirm.innerHTML = '<span class="btn-spinner"></span> Menolak...';

        const card = document.querySelector(`.challenge-card[data-challenge-id="${pendingRejectId}"]`);
        card?.querySelectorAll('button').forEach(b => b.disabled = true);
        card?.classList.add('is-processing');

        try {
            const { ok, data } = await ajaxPost(pendingRejectUrl, {
                reject_reason: rjReason.value.trim(),
            });

            if (ok && data.success) {
                rejectClose();
                showToast(data.message, 'info');
                removeCard(pendingRejectId, () => decrementIncoming());
            } else {
                showToast(data.message ?? 'Terjadi kesalahan.', 'error');
                rjConfirm.disabled  = false;
                rjConfirm.innerHTML = '<i class="bi bi-x-circle"></i> Konfirmasi Penolakan';
                card?.classList.remove('is-processing');
                card?.querySelectorAll('button').forEach(b => b.disabled = false);
            }
        } catch {
            showToast('Gagal terhubung ke server.', 'error');
            rjConfirm.disabled  = false;
            rjConfirm.innerHTML = '<i class="bi bi-x-circle"></i> Konfirmasi Penolakan';
            card?.classList.remove('is-processing');
            card?.querySelectorAll('button').forEach(b => b.disabled = false);
        }
    });

})();
</script>
@endpush