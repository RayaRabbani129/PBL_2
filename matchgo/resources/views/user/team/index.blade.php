@extends('user.layouts.app')
@section('title', 'Tim Saya — MATCHGO')
@section('page-title', 'Tim Saya')

@push('styles')
<style>
/* ══════════════════════════════════════════
   HERO
══════════════════════════════════════════ */
.team-hero {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    padding: 2rem 2rem 1.75rem;
    margin-bottom: 1.5rem;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
}

.team-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
    pointer-events: none;
}

.team-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    opacity: 0.35;
}

.team-hero-content {
    position: relative;
    z-index: 1;
}

.team-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--accent);
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    border-radius: 99px;
    padding: 4px 12px;
    margin-bottom: 14px;
}

.team-hero h2 {
    font-family: 'Manrope', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1.2;
    margin-bottom: 8px;
}

.team-hero h2 span { color: var(--accent); }

.team-hero p {
    font-size: 0.875rem;
    color: var(--txt-muted);
    max-width: 500px;
    margin-bottom: 0;
}

.team-hero-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    margin-top: 4px;
}

/* ══════════════════════════════════════════
   STATS ROW — 4 kolom (state: punya tim)
══════════════════════════════════════════ */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 1.5rem;
}

@media (max-width: 991px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px)  { .stats-row { grid-template-columns: 1fr 1fr; } }

.mini-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    transition: border-color 0.2s, background 0.3s;
}

.mini-stat:hover { border-color: rgba(163,177,75,0.25); }

.mini-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: var(--accent-dim);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.mini-stat-val {
    font-family: 'Manrope', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1;
}

.mini-stat-label {
    font-size: 0.75rem;
    color: var(--txt-muted);
    font-weight: 500;
    margin-top: 3px;
}

/* ══════════════════════════════════════════
   TEAM INFO BAR — pola mm-my-team
══════════════════════════════════════════ */
.team-info-bar {
    background: var(--surface-3);
    border: 1px solid var(--border-medium);
    border-radius: 14px;
    padding: 0.9rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}

.team-info-bar::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--accent);
    border-radius: 3px 0 0 3px;
}

.team-info-logo {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: var(--accent-dim);
    border: 1.5px solid rgba(163,177,75,0.30);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    overflow: hidden;
}

.team-info-name {
    font-family: 'Manrope', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--txt-primary);
    line-height: 1.2;
}

.team-info-meta {
    font-size: 0.73rem;
    color: var(--txt-muted);
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.team-info-meta span {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ══════════════════════════════════════════
   RESULTS HEADER + FILTER TABS
══════════════════════════════════════════ */
.team-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-subtle);
    flex-wrap: wrap;
    gap: 10px;
}

.team-results-title {
    font-family: 'Manrope', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--txt-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.team-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    border-radius: 99px;
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--accent);
    padding: 0 7px;
}

/* ══════════════════════════════════════════
   MEMBER CARD — pola mm-team-card
══════════════════════════════════════════ */
.member-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.member-card {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 16px;
    padding: 1rem 1.15rem;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: border-color 0.2s, background 0.15s, transform 0.15s;
    position: relative;
    overflow: hidden;
}

.member-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    border-radius: 3px 0 0 3px;
    background: var(--accent);
    opacity: 0;
    transition: opacity 0.2s;
}

.member-card:hover {
    border-color: rgba(163,177,75,0.28);
    background: var(--surface-3);
    transform: translateY(-1px);
}

.member-card:hover::before { opacity: 1; }

.member-avatar {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: var(--accent-dim);
    border: 1.5px solid rgba(163,177,75,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Manrope', sans-serif;
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--accent);
    flex-shrink: 0;
}

.member-info { flex: 1; min-width: 0; }

.member-name {
    font-family: 'Manrope', sans-serif;
    font-size: 0.925rem;
    font-weight: 700;
    color: var(--txt-primary);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.member-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    font-size: 0.73rem;
    color: var(--txt-muted);
}

/* Role tag — pola mm-reason-tag */
.role-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.67rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 99px;
    background: var(--surface-4);
    color: var(--txt-muted);
    border: 1px solid var(--border-subtle);
}

.role-tag i { font-size: 0.65rem; color: var(--accent); }

/* Status Pills */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: 99px;
}

.status-pill::before {
    content: '';
    width: 5px;
    height: 5px;
    background: currentColor;
    border-radius: 50%;
}

.status-pill.active {
    background: var(--alert-success-bg);
    color: var(--alert-success-txt);
    border: 1px solid var(--alert-success-bdr);
}

.status-pill.inactive {
    background: var(--surface-4);
    color: var(--txt-muted);
    border: 1px solid var(--border-subtle);
}

/* Action buttons */
.member-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.member-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--txt-muted);
    cursor: pointer;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    font-size: 0.825rem;
    text-decoration: none;
}

.member-action-btn:hover {
    background: var(--accent-dim);
    color: var(--accent);
    border-color: rgba(163,177,75,0.3);
    text-decoration: none;
}

.member-action-btn.delete:hover {
    background: var(--alert-danger-bg);
    color: var(--alert-danger-txt);
    border-color: var(--alert-danger-bdr);
}

/* ══════════════════════════════════════════
   EMPTY STATE — belum punya tim
══════════════════════════════════════════ */
.team-empty-wrap {
    max-width: 520px;
    margin: 3rem auto;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
}

.team-avatar-stack {
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-avatar-item {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2.5px solid var(--surface-1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    margin-left: -12px;
}

.team-avatar-item:first-child { margin-left: 0; }

.team-avatar-more {
    background: var(--surface-4);
    color: var(--txt-muted);
    font-size: 12px;
}

.team-empty-icon-box {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.85rem;
    color: var(--accent);
    margin: 0 auto;
}

.team-empty-title {
    font-family: 'Manrope', sans-serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--txt-primary);
    margin-bottom: 0.5rem;
}

.team-empty-desc {
    font-size: 0.875rem;
    color: var(--txt-muted);
    line-height: 1.6;
    margin-bottom: 1.25rem;
    max-width: 360px;
}

.team-hints {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
}

.team-hint-item {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    border-radius: 99px;
    padding: 6px 14px;
    font-size: 0.75rem;
    color: var(--txt-secondary);
    font-weight: 500;
}

.team-hint-icon { color: var(--accent); font-size: 13px; }

/* ══════════════════════════════════════════
   EMPTY STATE — belum ada anggota
══════════════════════════════════════════ */
.member-empty {
    text-align: center;
    padding: 3.5rem 1rem;
    background: var(--surface-2);
    border: 1px dashed var(--border-medium);
    border-radius: 16px;
}

.member-empty-icon {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: var(--surface-4);
    border: 1px solid var(--border-medium);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.65rem;
    color: var(--txt-faint);
    margin: 0 auto 1.25rem;
}

.member-empty h5 {
    font-family: 'Manrope', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--txt-secondary);
    margin-bottom: 6px;
}

.member-empty p {
    font-size: 0.825rem;
    color: var(--txt-muted);
    max-width: 300px;
    margin: 0 auto 1.25rem;
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 767px) {
    .team-info-bar { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
    .team-hints    { flex-direction: column; align-items: center; }
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ol class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li class="active">Tim Saya</li>
</ol>

@if(!$team)
{{-- ═══════════════════════════════════════════
     STATE: BELUM PUNYA TIM
═══════════════════════════════════════════ --}}

{{-- Hero --}}
<div class="team-hero">
    <div class="team-hero-grid"></div>
    <div class="team-hero-content d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="team-hero-eyebrow">
                <i class="bi bi-people-fill"></i> Tim Saya
            </div>
            <h2>Buat <span>Tim Kamu</span></h2>
            <p>Buat tim, undang teman, dan mulai tantang lawan di lapangan!</p>
        </div>
        <a href="{{ route('team.create') }}" class="btn-lime" style="margin-top: 4px; flex-shrink: 0;">
            <i class="bi bi-plus-circle-fill"></i> Buat Tim Sekarang
        </a>
    </div>
</div>

{{-- Empty state body --}}
<div class="team-empty-wrap">

    <div class="team-avatar-stack">
        @foreach([
            ['initials' => 'BK', 'bg' => '#EAF3DE', 'color' => '#3B6D11'],
            ['initials' => 'RD', 'bg' => '#E6F1FB', 'color' => '#185FA5'],
            ['initials' => 'FZ', 'bg' => '#FAEEDA', 'color' => '#854F0B'],
            ['initials' => 'AN', 'bg' => '#FBEAF0', 'color' => '#993556'],
        ] as $i => $av)
        <div class="team-avatar-item" style="background:{{ $av['bg'] }};color:{{ $av['color'] }};z-index:{{ 10 - $i }};">
            {{ $av['initials'] }}
        </div>
        @endforeach
        <div class="team-avatar-item team-avatar-more">+5</div>
    </div>

    <div>
        <div class="team-empty-icon-box"><i class="bi bi-people-fill"></i></div>
    </div>

    <div>
        <h2 class="team-empty-title">Belum punya tim</h2>
        <p class="team-empty-desc">
            Buat tim sekarang, undang teman, dan mulai tantang lawan di lapangan!
        </p>
        <a href="{{ route('team.create') }}" class="btn-lime" style="padding: 10px 24px; font-size: 0.9rem;">
            <i class="bi bi-plus-circle-fill"></i> Buat Tim Sekarang
        </a>
    </div>

    <div class="team-hints">
        @foreach([
            ['icon' => 'bi-people-fill',     'label' => 'Kelola anggota'],
            ['icon' => 'bi-calendar2-check', 'label' => 'Atur jadwal match'],
            ['icon' => 'bi-trophy-fill',     'label' => 'Lacak statistik'],
        ] as $hint)
        <div class="team-hint-item">
            <i class="bi {{ $hint['icon'] }} team-hint-icon"></i>
            <span>{{ $hint['label'] }}</span>
        </div>
        @endforeach
    </div>

</div>

@else
{{-- ═══════════════════════════════════════════
     STATE: SUDAH PUNYA TIM
═══════════════════════════════════════════ --}}

{{-- Hero --}}
<div class="team-hero">
    <div class="team-hero-grid"></div>
    <div class="team-hero-content d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="team-hero-eyebrow">
                <i class="bi bi-people-fill"></i> Tim Saya
            </div>
            <h2>{{ $team->name }}</h2>
            <p>
                <i class="bi bi-geo-alt-fill me-1" style="color: var(--accent);"></i>
                {{ $team->city }}, {{ $team->province }}
            </p>
        </div>
        <div class="team-hero-actions">
            <a href="{{ route('team.edit', $team) }}" class="btn-outline-lime">
                <i class="bi bi-pencil-fill"></i> Edit Tim
            </a>
            <a href="{{ route('team.members.create') }}" class="btn-lime">
                <i class="bi bi-person-plus-fill"></i> Tambah Member
            </a>
        </div>
    </div>
</div>

{{-- Stats row --}}
<div class="stats-row">
    <div class="mini-stat">
        <div class="mini-stat-icon">
            <i class="bi bi-people-fill"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $members->count() }}</div>
            <div class="mini-stat-label">Anggota</div>
        </div>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon">
            <i class="bi bi-calendar2-check"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $stats->total_matches ?? 0 }}</div>
            <div class="mini-stat-label">Total Match</div>
        </div>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background: var(--alert-success-bg); color: var(--alert-success-txt);">
            <i class="bi bi-trophy-fill"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $stats->wins ?? 0 }}</div>
            <div class="mini-stat-label">Menang</div>
        </div>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background: var(--alert-danger-bg); color: var(--alert-danger-txt);">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $stats->losses ?? 0 }}</div>
            <div class="mini-stat-label">Kalah</div>
        </div>
    </div>
</div>

{{-- Team info bar — pola mm-my-team --}}
<div class="team-info-bar">
    <div class="team-info-logo">
        @if($team?->logo_path)
            <img src="{{ Storage::url($team->logo_path) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
            ⚽
        @endif
    </div>
    <div>
        <div class="team-info-name">{{ $team->name }}</div>
        <div class="team-info-meta">
            <span><i class="bi bi-geo-alt-fill" style="color:var(--accent)"></i> {{ $team->city }}, {{ $team->province }}</span>
            @if($team->level)
                <span><i class="bi bi-trophy" style="color:var(--accent)"></i> {{ ucfirst(str_replace('_',' ',$team->level)) }}</span>
            @endif
            @if($team->founded_year)
                <span><i class="bi bi-calendar3" style="color:var(--accent)"></i> Berdiri {{ $team->founded_year }}</span>
            @endif
        </div>
    </div>
</div>

{{-- Member list --}}
<div class="card-matchgo">

    {{-- Results header — pola sched-results-header --}}
    <div class="team-results-header">
        <div class="team-results-title">
            Anggota Tim
            <span class="team-count-pill">{{ $members->count() }}</span>
        </div>
        <a href="{{ route('team.members.create') }}" class="btn-lime btn-sm">
            <i class="bi bi-person-plus-fill"></i> Tambah Member
        </a>
    </div>

    @if($members->isEmpty())
    <div class="member-empty">
        <div class="member-empty-icon">
            <i class="bi bi-people"></i>
        </div>
        <h5>Belum ada anggota</h5>
        <p>Tambahkan anggota tim agar bisa mulai bermain bersama.</p>
        <a href="{{ route('team.members.create') }}" class="btn-lime btn-sm">
            <i class="bi bi-person-plus-fill"></i> Tambah Member Pertama
        </a>
    </div>
    @else

    <div class="member-list">
        @foreach($members as $member)
        @php
            $name     = $member->user->name ?? $member->name ?? '??';
            $initials = strtoupper(substr($name, 0, 2));
            $isActive = in_array(strtolower($member->status), ['active', 'aktif']);
        @endphp
        <div class="member-card">

            <div class="member-avatar">{{ $initials }}</div>

            <div class="member-info">
                <div class="member-name">{{ $name }}</div>
                <div class="member-meta">
                    <span class="role-tag">
                        <i class="bi bi-person-badge"></i>
                        {{ $member->role }}
                    </span>
                    <span class="status-pill {{ $isActive ? 'active' : 'inactive' }}">
                        {{ $member->status }}
                    </span>
                </div>
            </div>

            <div class="member-actions">
                <a href="{{ route('team.members.edit', $member) }}"
                   class="member-action-btn" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('team.members.destroy', $member) }}"
                      method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="member-action-btn delete"
                            title="Hapus"
                            onclick="return confirm('Hapus anggota {{ $name }}?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>

    @endif
</div>

@endif

@endsection