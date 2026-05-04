{{-- resources/views/user/matches/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Pertandingan — MATCHGO')
@section('page-title', 'Pertandingan')

@push('styles')
<style>
    .matches-hero {
        position: relative; border-radius: 18px; overflow: hidden;
        padding: 1.75rem 2rem; margin-bottom: 1.75rem;
        background: var(--surface-2); border: 1px solid var(--border-subtle);
    }
    .matches-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at bottom right, var(--accent-dim) 0%, transparent 60%);
        pointer-events: none;
    }
    .matches-hero-content { position: relative; z-index: 1; }
    .matches-hero h2 { font-family:'Manrope',sans-serif; font-size:1.4rem; font-weight:800; color:var(--txt-primary); margin-bottom:4px; }
    .matches-hero h2 span { color: var(--accent); }
    .matches-hero p { font-size:0.85rem; color:var(--txt-muted); margin:0; }

    .matches-stats { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
    .matches-stat-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 14px; padding: 1rem 1.25rem;
        flex: 1; min-width: 130px;
        display: flex; align-items: center; gap: 12px;
        transition: border-color 0.2s;
    }
    .matches-stat-card:hover { border-color: rgba(163,177,75,0.25); }
    .matches-stat-icon { width:36px; height:36px; border-radius:10px; background:var(--accent-dim); display:flex; align-items:center; justify-content:center; color:var(--accent); font-size:1rem; flex-shrink:0; }
    .matches-stat-val { font-family:'Manrope',sans-serif; font-size:1.5rem; font-weight:800; color:var(--txt-primary); line-height:1.1; }
    .matches-stat-label { font-size:0.72rem; color:var(--txt-muted); font-weight:500; }

    .matches-tabs { display:flex; gap:2px; background:var(--surface-3); border:1px solid var(--border-subtle); border-radius:12px; padding:4px; margin-bottom:1.5rem; overflow-x:auto; scrollbar-width:none; }
    .matches-tabs::-webkit-scrollbar { display:none; }
    .matches-tab { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:9px; font-size:0.825rem; font-weight:600; color:var(--txt-muted); cursor:pointer; text-decoration:none; transition:all 0.15s; white-space:nowrap; border:1px solid transparent; font-family:'Inter',sans-serif; }
    .matches-tab:hover { color:var(--txt-primary); text-decoration:none; background:var(--surface-4); }
    .matches-tab.active { background:var(--surface-1); color:var(--txt-primary); border-color:var(--border-medium); box-shadow:var(--shadow-sm); }
    .tab-badge { display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; border-radius:99px; background:var(--accent-dim); color:var(--accent); font-size:0.62rem; font-weight:700; padding:0 5px; border:1px solid rgba(163,177,75,0.20); }
    .matches-tab.active .tab-badge { background:var(--accent); color:var(--btn-primary-txt); border-color:transparent; }
    .tab-badge.danger { background:rgba(239,68,68,0.12); color:#f87171; border-color:rgba(239,68,68,0.20); }
    .matches-tab.active .tab-badge.danger { background:#ef4444; color:#fff; border-color:transparent; }

    .matches-section-heading { font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.10em; color:var(--txt-faint); margin-bottom:10px; display:flex; align-items:center; gap:8px; }
    .matches-section-heading::after { content:''; flex:1; height:1px; background:var(--border-subtle); }

    .matches-empty { text-align:center; padding:3.5rem 1rem; background:var(--surface-2); border:1px solid var(--border-subtle); border-radius:16px; }
    .matches-empty-icon { width:60px; height:60px; border-radius:16px; background:var(--surface-4); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:var(--txt-faint); margin:0 auto 1rem; }
    .matches-empty h4 { font-family:'Manrope',sans-serif; font-size:0.95rem; font-weight:700; color:var(--txt-secondary); margin-bottom:5px; }
    .matches-empty p { font-size:0.82rem; color:var(--txt-muted); max-width:260px; margin:0 auto; }

    /* ── Match card ── */
    .match-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px; padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        transition: border-color 0.2s, transform 0.15s;
        position: relative; overflow: hidden;
        text-decoration: none;
    }
    .match-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:3px 0 0 3px; }
    .match-card.status-scheduled::before { background: var(--accent); }
    .match-card.status-completed::before { background: #86efac; }
    .match-card.status-cancelled::before { background: var(--txt-faint); }
    .match-card:hover { border-color: rgba(163,177,75,0.25); transform: translateY(-1px); text-decoration: none; }

    .match-card-teams { flex: 1; min-width: 200px; display: flex; align-items: center; gap: 10px; }
    .match-team-ava { width:38px; height:38px; border-radius:10px; background:var(--accent-dim); border:1.5px solid rgba(163,177,75,0.22); display:flex; align-items:center; justify-content:center; font-family:'Manrope',sans-serif; font-weight:800; font-size:0.85rem; color:var(--accent); flex-shrink:0; }
    .match-vs { font-family:'Manrope',sans-serif; font-weight:800; font-size:0.75rem; color:var(--txt-faint); flex-shrink:0; }
    .match-team-name { font-family:'Manrope',sans-serif; font-size:0.85rem; font-weight:700; color:var(--txt-primary); }
    .match-team-label { font-size:0.65rem; color:var(--txt-faint); }

    .match-card-info { display:flex; flex-direction:column; gap:3px; flex-shrink:0; }
    .match-info-item { font-size:0.75rem; color:var(--txt-muted); display:flex; align-items:center; gap:5px; }
    .match-info-item i { color:var(--accent); font-size:0.7rem; }

    .match-score { font-family:'Manrope',sans-serif; font-size:1.2rem; font-weight:800; color:var(--txt-primary); flex-shrink:0; letter-spacing:0.05em; }

    .match-status-badge { display:inline-flex; align-items:center; gap:5px; font-size:0.68rem; font-weight:700; padding:3px 10px; border-radius:99px; flex-shrink:0; }
    .badge-scheduled { background:var(--accent-dim); color:var(--accent); border:1px solid rgba(163,177,75,0.25); }
    .badge-completed { background:rgba(134,239,172,0.12); color:#86efac; border:1px solid rgba(134,239,172,0.20); }
    .badge-cancelled { background:var(--surface-4); color:var(--txt-faint); border:1px solid var(--border-subtle); }

    /* ── Challenge card ── */
    .challenge-card {
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px; padding: 1.1rem 1.25rem;
        display: flex; align-items: flex-start; gap: 14px; flex-wrap: wrap;
        position: relative; overflow: hidden;
        transition: opacity 0.3s, transform 0.3s;
    }
    .challenge-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:3px 0 0 3px; background:var(--accent); }
    /* State saat sedang diproses AJAX */
    .challenge-card.is-processing { opacity: 0.5; pointer-events: none; }
    /* State animasi keluar */
    .challenge-card.is-removing   { opacity: 0; transform: translateX(30px); }

    .challenge-card-body { flex:1; min-width:0; }
    .challenge-team-name { font-family:'Manrope',sans-serif; font-size:0.9rem; font-weight:700; color:var(--txt-primary); margin-bottom:4px; }
    .challenge-meta { display:flex; flex-wrap:wrap; gap:10px; font-size:0.74rem; color:var(--txt-muted); margin-bottom:10px; }
    .challenge-meta span { display:flex; align-items:center; gap:4px; }
    .challenge-meta i { color:var(--accent); }
    .challenge-actions { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }

    .btn-accept { display:inline-flex; align-items:center; gap:5px; font-size:0.775rem; font-weight:600; padding:7px 14px; border-radius:8px; background:var(--accent); color:var(--btn-primary-txt); border:none; cursor:pointer; transition:background 0.15s, transform 0.15s; font-family:'Inter',sans-serif; }
    .btn-accept:hover:not(:disabled) { background:var(--accent-hover); transform:translateY(-1px); }
    .btn-accept:disabled { opacity:0.6; cursor:not-allowed; }

    .btn-reject { display:inline-flex; align-items:center; gap:5px; font-size:0.775rem; font-weight:600; padding:7px 14px; border-radius:8px; background:var(--surface-4); color:var(--txt-secondary); border:1px solid var(--border-medium); cursor:pointer; transition:all 0.15s; font-family:'Inter',sans-serif; }
    .btn-reject:hover:not(:disabled) { border-color:#f87171; color:#f87171; background:rgba(248,113,113,0.08); }
    .btn-reject:disabled { opacity:0.6; cursor:not-allowed; }

    .btn-cancel-sm { display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; font-weight:600; padding:5px 11px; border-radius:8px; background:transparent; border:1px solid var(--border-medium); color:var(--txt-muted); cursor:pointer; transition:all 0.15s; font-family:'Inter',sans-serif; }
    .btn-cancel-sm:hover { border-color:#f87171; color:#f87171; background:rgba(248,113,113,0.07); }

    .outgoing-status { display:inline-flex; align-items:center; gap:5px; font-size:0.7rem; font-weight:700; padding:3px 10px; border-radius:99px; background:rgba(251,191,36,0.10); color:#fcd34d; border:1px solid rgba(251,191,36,0.20); }

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
    #ajax-toast.toast-success  { background:var(--surface-2); border:1px solid rgba(134,239,172,0.35); color:#86efac; }
    #ajax-toast.toast-error    { background:var(--surface-2); border:1px solid rgba(248,113,113,0.35); color:#f87171; }
    #ajax-toast.toast-info     { background:var(--surface-2); border:1px solid rgba(163,177,75,0.30);  color:var(--accent); }

    /* ── Spinner kecil ── */
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-spinner { width:13px; height:13px; border:2px solid currentColor; border-top-color:transparent; border-radius:50%; animation:spin .6s linear infinite; display:inline-block; flex-shrink:0; }

    /* ── Reject modal ── */
    #reject-backdrop {
        position:fixed; inset:0; background:rgba(0,0,0,0.60);
        backdrop-filter:blur(5px); z-index:9999;
        display:flex; align-items:center; justify-content:center; padding:1rem;
        opacity:0; pointer-events:none; transition:opacity 0.2s;
    }
    #reject-backdrop.is-open { opacity:1; pointer-events:all; }
    #reject-modal {
        background:var(--surface-2); border:1px solid var(--border-medium);
        border-radius:20px; padding:1.5rem; width:100%; max-width:400px;
        transform:translateY(12px) scale(0.98); transition:transform 0.2s;
    }
    #reject-backdrop.is-open #reject-modal { transform:translateY(0) scale(1); }
    .rj-title  { font-family:'Manrope',sans-serif; font-size:0.95rem; font-weight:800; color:var(--txt-primary); display:flex; align-items:center; gap:8px; margin-bottom:1rem; }
    .rj-label  { display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.09em; color:var(--txt-faint); margin-bottom:6px; }
    .rj-textarea { width:100%; box-sizing:border-box; background:var(--surface-3); border:1px solid var(--border-medium); border-radius:10px; padding:9px 12px; font-size:0.85rem; color:var(--txt-primary); font-family:'Inter',sans-serif; outline:none; resize:vertical; transition:border-color 0.15s; }
    .rj-textarea:focus { border-color:var(--accent); }
    .rj-btn-confirm { width:100%; margin-top:1rem; padding:10px; border-radius:10px; background:rgba(248,113,113,0.15); color:#f87171; border:1px solid rgba(248,113,113,0.30); font-weight:700; font-size:0.875rem; cursor:pointer; font-family:'Manrope',sans-serif; transition:background 0.15s; display:flex; align-items:center; justify-content:center; gap:6px; }
    .rj-btn-confirm:hover:not(:disabled) { background:rgba(248,113,113,0.25); }
    .rj-btn-confirm:disabled { opacity:0.6; cursor:not-allowed; }
    .rj-close  { width:32px; height:32px; border-radius:8px; background:var(--surface-4); border:1px solid var(--border-subtle); color:var(--txt-muted); display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:0.875rem; transition:all 0.15s; }
    .rj-close:hover { background:var(--surface-5); color:var(--txt-primary); }
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
    <div class="matches-hero-content d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h2>Match <span>Saya</span></h2>
            <p>Kelola semua pertandingan, terima tantangan, dan input skor.</p>
        </div>
        <a href="{{ route('matchmaking.index') }}" class="btn-lime btn-sm">
            <i class="bi bi-search-heart"></i> Cari Lawan Baru
        </a>
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
        <div class="matches-stat-icon" style="background:rgba(134,239,172,0.12);color:#86efac;"><i class="bi bi-trophy"></i></div>
        <div>
            <div class="matches-stat-val">{{ $counts['completed'] }}</div>
            <div class="matches-stat-label">Selesai</div>
        </div>
    </div>
    <div class="matches-stat-card">
        <div class="matches-stat-icon" style="background:rgba(239,68,68,0.10);color:#f87171;"><i class="bi bi-lightning-charge"></i></div>
        <div>
            <div class="matches-stat-val" id="stat-incoming">{{ $counts['incoming'] }}</div>
            <div class="matches-stat-label">Tantangan Masuk</div>
        </div>
    </div>
    <div class="matches-stat-card">
        <div class="matches-stat-icon" style="background:rgba(251,191,36,0.10);color:#fcd34d;"><i class="bi bi-send"></i></div>
        <div>
            <div class="matches-stat-val">{{ $counts['outgoing'] }}</div>
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
            <div class="matches-empty-icon"><i class="bi bi-calendar-x"></i></div>
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
                            <div class="match-team-ava">{{ strtoupper(substr($myTeamInMatch->name, 0, 2)) }}</div>
                        </div>
                        <div>
                            <div class="match-team-name">{{ $myTeamInMatch->name }}</div>
                            <div class="match-team-label">Tim Saya</div>
                        </div>
                        <div class="match-vs">VS</div>
                        <div>
                            <div class="match-team-ava" style="background:var(--surface-4);border-color:var(--border-medium);color:var(--txt-secondary);">
                                {{ strtoupper(substr($oppTeamInMatch->name, 0, 2)) }}
                            </div>
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
    {{-- Wrapper utama — JS akan memanipulasi konten di dalamnya --}}
    <div id="incoming-list">
        @if ($incoming->isEmpty())
            <div class="matches-empty">
                <div class="matches-empty-icon"><i class="bi bi-inbox"></i></div>
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
                        <div class="mm-card-avatar" style="width:46px;height:46px;border-radius:12px;">
                            {{ strtoupper(substr($challenger->name, 0, 2)) }}
                        </div>
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
                                {{-- Terima — AJAX --}}
                                <button type="button"
                                        class="btn-accept js-accept-challenge"
                                        data-accept-url="{{ route('matches.challenge.accept', $req) }}"
                                        data-challenge-id="{{ $req->id }}">
                                    <i class="bi bi-check-lg"></i> Terima
                                </button>
                                {{-- Tolak — buka modal, lalu AJAX --}}
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
            <div class="matches-empty-icon"><i class="bi bi-send-x"></i></div>
            <h4>Belum Ada Tantangan Terkirim</h4>
            <p>Tantangan yang kamu kirim lewat matchmaking muncul di sini.</p>
        </div>
    @else
        <div class="matches-section-heading">{{ $outgoing->count() }} tantangan terkirim</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach ($outgoing as $req)
                @php $opponent = $req->matchedTeam; @endphp
                <div class="challenge-card">
                    <div class="mm-card-avatar" style="width:46px;height:46px;border-radius:12px;background:var(--surface-4);border-color:var(--border-medium);color:var(--txt-secondary);">
                        {{ $opponent ? strtoupper(substr($opponent->name, 0, 2)) : '?' }}
                    </div>
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
                            {{-- Batalkan tantangan — route matchmaking.cancel tetap di group matchmaking --}}
                            <form action="{{ route('matchmaking.cancel', $req) }}" method="POST"
                                  onsubmit="return confirm('Batalkan tantangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-cancel-sm">
                                    <i class="bi bi-trash3"></i> Batalkan
                                </button>
                            </form>
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
            <div class="matches-empty-icon"><i class="bi bi-trophy"></i></div>
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
                            <div class="match-team-ava">{{ strtoupper(substr($myTeamInMatch->name, 0, 2)) }}</div>
                        </div>
                        <div>
                            <div class="match-team-name">{{ $myTeamInMatch->name }}</div>
                            <div class="match-team-label">Tim Saya</div>
                        </div>
                        <div class="match-score">{{ $myScore }} – {{ $oppScore }}</div>
                        <div>
                            <div class="match-team-ava" style="background:var(--surface-4);border-color:var(--border-medium);color:var(--txt-secondary);">
                                {{ strtoupper(substr($oppTeamInMatch->name, 0, 2)) }}
                            </div>
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
<div id="reject-backdrop" onclick="if(event.target===this)rejectClose()">
    <div id="reject-modal">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="rj-title">
                <i class="bi bi-x-circle-fill" style="color:#f87171;"></i>
                Tolak Tantangan dari <span id="rj-team-name" style="color:var(--accent);"></span>
            </div>
            <button type="button" class="rj-close" onclick="rejectClose()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div style="margin-bottom:1rem;">
            <label class="rj-label">Alasan penolakan (opsional)</label>
            <textarea id="rj-reason" class="rj-textarea" rows="3"
                placeholder="Contoh: Jadwal bentrok, lokasi terlalu jauh..."></textarea>
        </div>
        {{-- Tombol ini tidak lagi di dalam <form>, submit dilakukan via JS --}}
        <button type="button" id="rj-confirm-btn" class="rj-btn-confirm">
            <i class="bi bi-x-circle"></i> Konfirmasi Penolakan
        </button>
    </div>
</div>

{{-- ── Toast AJAX ── --}}
<div id="ajax-toast"></div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // Ambil CSRF token dari meta tag (best practice) atau fallback ke Blade
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
               ?? '{{ csrf_token() }}';

    // ─────────────────────────────────────────────────────────
    // Toast helper
    // ─────────────────────────────────────────────────────────
    const toastEl = document.getElementById('ajax-toast');
    let toastTimer;

    function showToast(message, type = 'success') {
        const icons = {
            success: 'bi-check-circle-fill',
            error:   'bi-exclamation-triangle-fill',
            info:    'bi-info-circle-fill',
        };
        toastEl.className = `toast-${type}`;
        toastEl.innerHTML = `<i class="bi ${icons[type] ?? icons.info}"></i> ${message}`;
        toastEl.classList.remove('show');
        void toastEl.offsetWidth;           // force reflow → transisi selalu berjalan
        toastEl.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 3500);
    }

    // ─────────────────────────────────────────────────────────
    // Animasi hapus card
    // ─────────────────────────────────────────────────────────
    function removeCard(challengeId, onDone) {
        const card = document.querySelector(
            `.challenge-card[data-challenge-id="${challengeId}"]`
        );
        if (!card) { onDone?.(); return; }

        card.classList.add('is-removing');
        card.addEventListener('transitionend', () => {
            card.remove();
            onDone?.();
        }, { once: true });
    }

    // ─────────────────────────────────────────────────────────
    // Update counter setelah accept / reject
    // ─────────────────────────────────────────────────────────
    function decrementIncoming() {
        // Stat card
        const statEl = document.getElementById('stat-incoming');
        if (statEl) {
            const n = Math.max(0, parseInt(statEl.textContent, 10) - 1);
            statEl.textContent = n;
        }

        // Tab badge
        const badge = document.getElementById('tab-badge-incoming');
        if (badge) {
            const n = Math.max(0, parseInt(badge.textContent, 10) - 1);
            badge.textContent   = n;
            badge.style.display = n > 0 ? '' : 'none';
        }

        // Cek sisa card
        const remaining = document.querySelectorAll('#incoming-cards .challenge-card');

        if (remaining.length === 0) {
            // Tampilkan empty state
            const list = document.getElementById('incoming-list');
            if (list) {
                list.innerHTML = `
                    <div class="matches-empty">
                        <div class="matches-empty-icon"><i class="bi bi-inbox"></i></div>
                        <h4>Tidak Ada Tantangan Masuk</h4>
                        <p>Tantangan dari tim lain akan muncul di sini.</p>
                    </div>`;
            }
        } else {
            // Update heading count
            const heading = document.getElementById('incoming-heading');
            if (heading) heading.firstChild.textContent = `${remaining.length} tantangan menunggu respons `;
        }
    }

    // ─────────────────────────────────────────────────────────
    // AJAX POST helper
    // ─────────────────────────────────────────────────────────
    async function ajaxPost(url, payload = {}) {
        const res = await fetch(url, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        return { ok: res.ok, data };
    }

    // ─────────────────────────────────────────────────────────
    // Accept challenge (AJAX)
    // ─────────────────────────────────────────────────────────
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.js-accept-challenge');
        if (!btn) return;

        const url         = btn.dataset.acceptUrl;
        const challengeId = btn.dataset.challengeId;
        const card        = btn.closest('.challenge-card');

        // Disable semua tombol di card ini
        card?.querySelectorAll('button').forEach(b => b.disabled = true);
        btn.innerHTML = '<span class="btn-spinner"></span> Memproses...';
        card?.classList.add('is-processing');

        try {
            const { ok, data } = await ajaxPost(url);

            if (ok && data.success) {
                showToast(data.message, 'success');
                removeCard(challengeId, () => {
                    decrementIncoming();
                    // Redirect ke halaman detail match setelah animasi selesai
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

        pendingRejectUrl  = btn.dataset.rejectUrl;
        pendingRejectId   = btn.dataset.challengeId;
        rjName.textContent = btn.dataset.name;
        rjReason.value     = '';

        // Reset tombol konfirmasi
        rjConfirm.disabled = false;
        rjConfirm.innerHTML = '<i class="bi bi-x-circle"></i> Konfirmasi Penolakan';

        backdrop.classList.add('is-open');
        setTimeout(() => rjReason.focus(), 200);
    });

    window.rejectClose = function () {
        backdrop.classList.remove('is-open');
    };

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') rejectClose();
    });

    // ─────────────────────────────────────────────────────────
    // Reject challenge — konfirmasi via AJAX
    // ─────────────────────────────────────────────────────────
    rjConfirm?.addEventListener('click', async function () {
        if (!pendingRejectUrl) return;

        rjConfirm.disabled = true;
        rjConfirm.innerHTML = '<span class="btn-spinner"></span> Menolak...';

        const card = document.querySelector(
            `.challenge-card[data-challenge-id="${pendingRejectId}"]`
        );
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
                rjConfirm.disabled = false;
                rjConfirm.innerHTML = '<i class="bi bi-x-circle"></i> Konfirmasi Penolakan';
                card?.classList.remove('is-processing');
                card?.querySelectorAll('button').forEach(b => b.disabled = false);
            }
        } catch {
            showToast('Gagal terhubung ke server.', 'error');
            rjConfirm.disabled = false;
            rjConfirm.innerHTML = '<i class="bi bi-x-circle"></i> Konfirmasi Penolakan';
            card?.classList.remove('is-processing');
            card?.querySelectorAll('button').forEach(b => b.disabled = false);
        }
    });

})();
</script>
@endpush