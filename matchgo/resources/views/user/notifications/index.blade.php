@extends('user.layouts.app')

@section('title', 'Notifications — MATCHGO')
@section('page-title', 'Notifications')

@push('styles')
<style>

*, *::before, *::after { box-sizing: border-box; }

/* ═══════════════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════════════ */
.mg-hero {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
}
.mg-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
    pointer-events: none;
}
.mg-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    opacity: 0.35;
}
.mg-hero-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.mg-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--accent);
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    border-radius: 99px;
    padding: 3px 11px;
    margin-bottom: 10px;
}
.mg-hero h2 {
    font-family: 'Manrope', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1.25;
    margin-bottom: 6px;
}
.mg-hero h2 span { color: var(--accent); }
.mg-hero p {
    font-size: 0.83rem;
    color: var(--txt-muted);
    margin: 0;
    max-width: 420px;
}
.mg-hero-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    align-self: flex-start;
    margin-top: 4px;
}
.mg-hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s;
}
.mg-hero-btn-accent {
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    color: var(--accent);
}
.mg-hero-btn-muted {
    background: var(--surface-3);
    border: 1px solid var(--border-medium);
    color: var(--txt-secondary);
}

/* ═══════════════════════════════════════════════════════
   SECTION CARD
═══════════════════════════════════════════════════════ */
.mg-section {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 20px;
    overflow: hidden;
    transition: border-color .25s;
    margin-bottom: 1.25rem;
}
.mg-section-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--surface-3);
}
.mg-section-num {
    font-size: 1.35rem;
    font-family: 'Manrope', sans-serif;
    font-weight: 900;
    color: var(--accent);
    opacity: .30;
    line-height: 1;
    flex-shrink: 0;
    margin-top: 2px;
    min-width: 2ch;
}
.mg-section-title {
    font-family: 'Manrope', sans-serif;
    font-size: .82rem;
    font-weight: 800;
    color: var(--txt-primary);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 2px;
}
.mg-section-sub {
    font-size: .75rem;
    color: var(--txt-faint);
    margin: 0;
    line-height: 1.5;
}
.mg-section-header-meta { flex: 1; min-width: 0; }
.mg-section-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.mg-section-body { padding: 1.5rem; }

/* ═══════════════════════════════════════════════════════
   MARK ALL BUTTON
═══════════════════════════════════════════════════════ */
.mg-mark-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.775rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid var(--border-medium);
    background: var(--surface-3);
    color: var(--txt-secondary);
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: all 0.15s;
    flex-shrink: 0;
    text-decoration: none;
}
.mg-mark-all:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--surface-4);
    text-decoration: none;
}

/* ═══════════════════════════════════════════════════════
   NOTIFICATION LIST
═══════════════════════════════════════════════════════ */
.mg-notif-list {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

/* ═══════════════════════════════════════════════════════
   NOTIFICATION ITEM
═══════════════════════════════════════════════════════ */
.mg-notif-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 1.25rem 1.5rem;
    transition: background 0.15s;
    position: relative;
    border-bottom: 1px solid var(--border-subtle);
}

.mg-notif-item:last-child { border-bottom: none; }

.mg-notif-item:hover { background: var(--accent-dim); }

.mg-notif-item.unread {
    border-left: 3px solid var(--accent);
}

.mg-notif-item.read { opacity: 0.55; }

/* ═══════════════════════════════════════════════════════
   ICON
═══════════════════════════════════════════════════════ */
.mg-notif-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    margin-top: 1px;
}

.mg-notif-icon.type-match     { background: rgba(163,177,75,0.14); color: var(--accent); }
.mg-notif-icon.type-challenge { background: rgba(91,140,255,0.12);  color: #5b8cff; }
.mg-notif-icon.type-reminder  { background: rgba(255,190,60,0.12);  color: #ffbe3c; }
.mg-notif-icon.type-result    { background: rgba(80,200,120,0.12);  color: #50c878; }
.mg-notif-icon.type-system    { background: rgba(180,180,180,0.10); color: var(--txt-secondary); }

/* ═══════════════════════════════════════════════════════
   CONTENT
═══════════════════════════════════════════════════════ */
.mg-notif-body { flex: 1; min-width: 0; }

.mg-notif-body-title {
    font-family: 'Manrope', sans-serif;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--txt-primary);
    margin: 0 0 3px 0;
    line-height: 1.3;
}

.mg-notif-body-desc {
    font-size: 0.8rem;
    color: var(--txt-secondary);
    margin: 0 0 8px 0;
    line-height: 1.5;
}

.mg-notif-time {
    font-size: 0.68rem;
    color: var(--txt-faint);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 500;
}

/* ═══════════════════════════════════════════════════════
   ACTION BUTTONS
═══════════════════════════════════════════════════════ */
.mg-notif-actions {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.btn-notif-accept {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 16px;
    border-radius: 10px;
    background: var(--accent);
    color: var(--btn-primary-txt);
    font-size: 0.775rem;
    font-weight: 700;
    font-family: 'Manrope', sans-serif;
    border: none;
    cursor: pointer;
    transition: background 0.15s, transform 0.15s;
    text-decoration: none;
}
.btn-notif-accept:hover {
    background: var(--accent-hover);
    transform: translateY(-1px);
    color: var(--btn-primary-txt);
    text-decoration: none;
}

.btn-notif-reject {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 16px;
    border-radius: 10px;
    background: var(--surface-3);
    color: var(--txt-secondary);
    border: 1px solid var(--border-medium);
    font-size: 0.775rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
}
.btn-notif-reject:hover {
    border-color: #f87171;
    color: #f87171;
    background: rgba(248,113,113,0.06);
    text-decoration: none;
}

/* ═══════════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════════ */
.mg-notif-empty {
    text-align: center;
    padding: 80px 24px;
}
.mg-notif-empty-icon {
    font-size: 2.5rem;
    color: var(--txt-faint);
    opacity: 0.35;
    margin-bottom: 14px;
}
.mg-notif-empty-title {
    font-family: 'Manrope', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--txt-secondary);
    margin: 0 0 6px 0;
}
.mg-notif-empty-sub {
    font-size: 0.8rem;
    color: var(--txt-muted);
    margin: 0;
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .mg-hero              { padding: 1.25rem; margin-bottom: 1rem; }
    .mg-hero h2           { font-size: 1.2rem; }
    .mg-hero p            { font-size: 0.8rem; }
    .mg-hero-actions      { margin-top: 0.75rem; width: 100%; }
    .mg-hero-btn          { font-size: 0.7rem; padding: 5px 10px; }
    .mg-section-header    { padding: 1rem 1.25rem; }
    .mg-section-body      { padding: 1.25rem; }
    .mg-notif-item        { padding: 1rem 1.25rem; gap: 12px; }
    .mg-notif-icon        { width: 36px; height: 36px; font-size: 0.875rem; }
    .mg-notif-body-title  { font-size: 0.825rem; }
    .mg-notif-body-desc   { font-size: 0.775rem; }
    .mg-section-header-row { flex-direction: column; gap: 0.5rem; }
}

@media (max-width: 480px) {
    .mg-hero              { border-radius: 14px; }
    .mg-section           { border-radius: 14px; }
    .mg-section-body      { padding: 1rem; }
    .mg-notif-item        { padding: 0.875rem 1rem; }
    .mg-notif-actions     { gap: 6px; }
    .btn-notif-accept,
    .btn-notif-reject     { font-size: 0.725rem; padding: 6px 12px; }
}

</style>
@endpush

@section('content')

{{-- ── Breadcrumb ── --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Notifications</span></li>
</ul>

{{-- ── Hero ── --}}
<div class="mg-hero">
    <div class="mg-hero-grid"></div>
    <div class="mg-hero-content">
        <div>
            <div class="mg-hero-eyebrow">
                <i class="bi bi-bell-fill"></i> Notifikasi
            </div>
            <h2>Pusat <span>Notifikasi</span> Kamu</h2>
            <p>Pantau semua aktivitas pertandingan, tantangan, dan pengingat jadwal kamu.</p>
        </div>
        <div class="mg-hero-actions">
            <a href="{{ route('matchmaking.index') }}" class="mg-hero-btn mg-hero-btn-accent">
                <i class="bi bi-search-heart"></i> Cari Lawan
            </a>
            <a href="{{ route('matches.index') }}" class="mg-hero-btn mg-hero-btn-muted">
                <i class="bi bi-calendar-event"></i> Pertandingan
            </a>
        </div>
    </div>
</div>

{{-- ── Section Notifikasi ── --}}
<div class="mg-section">

    {{-- Section Header --}}
    <div class="mg-section-header">
        <div class="mg-section-num">
            {{ $notifications->total() > 0 ? $notifications->total() : '—' }}
        </div>
        <div class="mg-section-header-meta">
            <div class="mg-section-header-row">
                <div>
                    <h6 class="mg-section-title">Semua Notifikasi</h6>
                    <p class="mg-section-sub">
                        @if($unreadCount > 0)
                            {{ $unreadCount }} notifikasi belum dibaca.
                        @else
                            Semua notifikasi sudah dibaca.
                        @endif
                    </p>
                </div>
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="mg-mark-all">
                            <i class="bi bi-check2-all"></i> Tandai semua dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Section Body --}}
    <div class="mg-section-body" style="padding: 0;">

        @if(session('success'))
            <div style="
                background: var(--alert-success-bg);
                border-bottom: 1px solid var(--alert-success-bdr);
                color: var(--alert-success-txt);
                padding: 12px 1.5rem;
                font-size: 0.825rem;
                display: flex;
                align-items: center;
                gap: 8px;
            ">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Empty state --}}
        @if($notifications->isEmpty())
            <div class="mg-notif-empty">
                <div class="mg-notif-empty-icon">
                    <i class="bi bi-bell-slash"></i>
                </div>
                <p class="mg-notif-empty-title">Belum ada notifikasi</p>
                <p class="mg-notif-empty-sub">
                    Notifikasi pertandingan dan aktivitas kamu akan muncul di sini.
                </p>
            </div>

        @else

            <div class="mg-notif-list">

                @foreach($notifications as $notif)

                    @php
                        $notifData = is_string($notif->data)
                            ? json_decode($notif->data, true)
                            : (array) $notif->data;
                        $notifData = $notifData ?? [];
                    @endphp

                    <div class="mg-notif-item {{ in_array($notif->status, ['unread','sent']) ? 'unread' : 'read' }}">

                        {{-- Icon --}}
                        <div class="mg-notif-icon
                            @switch($notif->type)
                                @case('match_confirmed') type-match     @break
                                @case('match_challenge') type-challenge @break
                                @case('match_reminder')  type-reminder  @break
                                @case('match_result')    type-result    @break
                                @default                 type-system
                            @endswitch
                        ">
                            @switch($notif->type)
                                @case('match_confirmed')
                                    <i class="bi bi-trophy-fill"></i>
                                    @break
                                @case('match_challenge')
                                    <i class="bi bi-send-fill"></i>
                                    @break
                                @case('match_reminder')
                                    <i class="bi bi-calendar-event-fill"></i>
                                    @break
                                @case('match_result')
                                    <i class="bi bi-check-circle-fill"></i>
                                    @break
                                @default
                                    <i class="bi bi-bell-fill"></i>
                            @endswitch
                        </div>

                        {{-- Body --}}
                        <div class="mg-notif-body">

                            <p class="mg-notif-body-title">{{ $notif->title }}</p>
                            <p class="mg-notif-body-desc">{{ $notif->message }}</p>

                            {{-- Tombol Terima / Tolak untuk challenge --}}
                            @if(
                                $notif->type === 'match_challenge' &&
                                in_array($notif->status, ['unread','sent']) &&
                                isset($notifData['match_request_id'])
                            )
                                <div class="mg-notif-actions">
                                    <form action="{{ route('matchmaking.accept', $notifData['match_request_id']) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-notif-accept">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('matchmaking.reject', $notifData['match_request_id']) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-notif-reject">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <p class="mg-notif-time">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-subtle);">
                    {{ $notifications->links() }}
                </div>
            @endif

        @endif

    </div>
</div>

@endsection