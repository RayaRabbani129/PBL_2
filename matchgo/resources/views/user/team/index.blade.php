@extends('user.layouts.app')
@section('title', 'Tim Saya')
@section('page-title', 'Tim Saya')

@section('content')

{{-- ===== BELUM PUNYA TIM ===== --}}
@if(!$team)

{{-- Hero empty state --}}
<div class="team-empty-wrap">

    {{-- Ilustrasi avatar bertumpuk --}}
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

    {{-- Teks --}}
    <div class="team-empty-body">
        <div class="team-empty-icon">⚽</div>
        <h2 class="team-empty-title">Anda belum punya tim</h2>
        <p class="team-empty-desc">
            Buat tim sekarang, undang teman, dan mulai tantang lawan di lapangan!
        </p>
        <a href="{{ route('team.create') }}" class="btn-matchgo-primary btn-lg-pill">
            <i class="bi bi-plus-circle-fill"></i>
            Buat Tim Sekarang
        </a>
    </div>

    {{-- Fitur hint --}}
    <div class="team-hints">
        @foreach([
            ['icon' => 'bi-people-fill',      'label' => 'Kelola anggota'],
            ['icon' => 'bi-calendar2-check',  'label' => 'Atur jadwal match'],
            ['icon' => 'bi-trophy-fill',       'label' => 'Lacak statistik'],
        ] as $hint)
        <div class="team-hint-item">
            <i class="bi {{ $hint['icon'] }} team-hint-icon"></i>
            <span>{{ $hint['label'] }}</span>
        </div>
        @endforeach
    </div>

</div>

@else
{{-- ===== SUDAH PUNYA TIM ===== --}}

{{-- ── Header Card Tim ── --}}
<div class="team-header-card mb-4">

    {{-- Kiri: Logo + info --}}
    <div class="d-flex align-items-center gap-3 flex-grow-1">
        <div class="team-logo-wrap">
            @if($team?->logo_path)
                <img src="{{ Storage::url($team->logo_path) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                ⚽
            @endif        
        </div>
        <div>
            <h4 class="team-name mb-0">{{ $team->name }}</h4>
            <p class="team-location mb-0">
                <i class="bi bi-geo-alt-fill me-1" style="font-size:11px;"></i>
                {{ $team->city }}, {{ $team->province }}
            </p>
        </div>
    </div>

    {{-- Stat pills --}}
    <div class="team-stat-row">
        @foreach([
            ['label' => 'Member', 'value' => $members->count(),      'icon' => 'bi-people-fill',     'accent' => ''],
            ['label' => 'Match',  'value' => $stats->total_matches ?? 0, 'icon' => 'bi-calendar2-check', 'accent' => ''],
            ['label' => 'Win',    'value' => $stats->wins ?? 0,       'icon' => 'bi-trophy-fill',     'accent' => 'win'],
            ['label' => 'Lose',   'value' => $stats->losses ?? 0,     'icon' => 'bi-x-circle-fill',   'accent' => 'lose'],
        ] as $s)
        <div class="team-stat-pill {{ $s['accent'] ? 'team-stat-pill--' . $s['accent'] : '' }}">
            <i class="bi {{ $s['icon'] }} team-stat-pill-icon"></i>
            <div>
                <div class="team-stat-val">{{ $s['value'] }}</div>
                <div class="team-stat-lbl">{{ $s['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Edit button --}}
    <a href="{{ route('team.edit', $team) }}" class="btn-matchgo-outline flex-shrink-0">
        <i class="bi bi-pencil-fill me-1" style="font-size:11px;"></i> Edit Tim
    </a>

</div>

{{-- ── Tabel Anggota ── --}}
<div class="card-matchgo">

    {{-- Header tabel --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0" style="font-family:'Manrope',sans-serif;font-weight:700;">Anggota Tim</h5>
            <p class="mb-0" style="font-size:12px;color:var(--txt-muted);margin-top:2px;">
                {{ $members->count() }} anggota terdaftar
            </p>
        </div>
        <a href="{{ route('team.members.create') }}" class="btn-matchgo-primary">
            <i class="bi bi-person-plus-fill"></i> Tambah Member
        </a>
    </div>

    @if($members->isEmpty())
    {{-- Empty state dalam card --}}
    <div class="mg-empty" style="padding:2.5rem 1rem;">
        <div class="mg-empty-icon"><i class="bi bi-people" style="font-size:2.2rem;color:var(--txt-faint);"></i></div>
        <h4>Belum ada anggota</h4>
        <p>Tambahkan anggota tim agar bisa mulai bermain bersama.</p>
        <a href="{{ route('team.members.create') }}" class="btn-matchgo-primary mt-3">
            <i class="bi bi-person-plus-fill"></i> Tambah Member Pertama
        </a>
    </div>
    @else

    <table class="table-matchgo">
        <thead>
            <tr>
                <th style="width:44px;"></th>
                <th>Nama</th>
                <th>Role</th>
                <th>Status</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            @php
                $name   = $member->user->name ?? $member->name ?? '??';
                $initials = strtoupper(substr($name, 0, 2));
            @endphp
            <tr>
                {{-- Avatar --}}
                <td style="padding:10px 8px 10px 16px;">
                    <div class="member-avatar">{{ $initials }}</div>
                </td>

                {{-- Nama --}}
                <td>
                    <span style="font-weight:500;color:var(--txt-primary);">{{ $name }}</span>
                </td>

                {{-- Role --}}
                <td>
                    <span class="badge-muted">{{ $member->role }}</span>
                </td>

                {{-- Status --}}
                <td>
                    @if(strtolower($member->status) === 'active' || strtolower($member->status) === 'aktif')
                        <span class="badge-status badge-status--active">
                            <span class="badge-dot"></span>{{ $member->status }}
                        </span>
                    @else
                        <span class="badge-status badge-status--inactive">
                            <span class="badge-dot"></span>{{ $member->status }}
                        </span>
                    @endif
                </td>

                {{-- Aksi --}}
                <td class="text-right">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('team.members.edit', $member) }}"
                           class="btn-matchgo-outline btn-sm">
                            <i class="bi bi-pencil" style="font-size:11px;"></i> Edit
                        </a>
                        <form action="{{ route('team.members.destroy', $member) }}"
                              method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn-matchgo-danger btn-sm"
                                    onclick="return confirm('Hapus anggota {{ $name }}?')">
                                <i class="bi bi-trash" style="font-size:11px;"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endif
</div>

@endif

@endsection

@push('styles')
<style>
/* ══════════════════════════════════════════
   EMPTY STATE — belum punya tim
══════════════════════════════════════════ */
.team-empty-wrap {
    max-width: 540px;
    margin: 2.5rem auto;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
}

/* Avatar stack */
.team-avatar-stack {
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-avatar-item {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2.5px solid var(--surface-0);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    margin-left: -12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}

.team-avatar-item:first-child { margin-left: 0; }

.team-avatar-more {
    background: var(--surface-4);
    color: var(--txt-muted);
    font-size: 12px;
}

/* Body */
.team-empty-icon {
    font-size: 3rem;
    line-height: 1;
    margin-bottom: .25rem;
}

.team-empty-title {
    font-family: 'Manrope', sans-serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--txt-primary);
    margin-bottom: .5rem;
}

.team-empty-desc {
    font-size: .9rem;
    color: var(--txt-muted);
    line-height: 1.6;
    margin-bottom: 1.25rem;
    max-width: 380px;
}

/* Big CTA button */
.btn-lg-pill {
    padding: 12px 28px !important;
    font-size: .9rem !important;
    border-radius: 14px !important;
    gap: 8px;
}

/* Hint strip */
.team-hints {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: .25rem;
}

.team-hint-item {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    border-radius: 99px;
    padding: 6px 14px;
    font-size: 12px;
    color: var(--txt-secondary);
    font-weight: 500;
}

.team-hint-icon {
    color: var(--accent);
    font-size: 13px;
}

/* ══════════════════════════════════════════
   HEADER CARD TIM
══════════════════════════════════════════ */
.team-header-card {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 18px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
    transition: background .3s, border-color .3s;
    position: relative;
    overflow: hidden;
}

/* Subtle accent strip kiri */
.team-header-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: var(--accent);
    border-radius: 4px 0 0 4px;
}

.team-logo-wrap {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.team-name {
    font-family: 'Manrope', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--txt-primary);
}

.team-location {
    font-size: 12px;
    color: var(--txt-muted);
    margin-top: 2px;
}

/* Stat pills */
.team-stat-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.team-stat-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    padding: 8px 14px;
    min-width: 72px;
    transition: background .15s, border-color .15s;
}

.team-stat-pill:hover {
    background: var(--accent-dim);
    border-color: rgba(163,177,75,.2);
}

.team-stat-pill--win {
    background: rgba(163,177,75,.08);
    border-color: rgba(163,177,75,.2);
}

.team-stat-pill--win .team-stat-pill-icon,
.team-stat-pill--win .team-stat-val { color: var(--accent); }

.team-stat-pill--lose {
    background: rgba(239,68,68,.06);
    border-color: rgba(239,68,68,.14);
}

.team-stat-pill--lose .team-stat-pill-icon { color: #f87171; }
.team-stat-pill--lose .team-stat-val { color: #f87171; }

.team-stat-pill-icon {
    font-size: 14px;
    color: var(--txt-muted);
}

.team-stat-val {
    font-family: 'Manrope', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1.1;
}

.team-stat-lbl {
    font-size: 10px;
    color: var(--txt-faint);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-top: 1px;
}

/* ══════════════════════════════════════════
   MEMBER TABLE ENHANCEMENTS
══════════════════════════════════════════ */
.member-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--accent-dim);
    border: 1.5px solid rgba(163,177,75,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--accent);
    flex-shrink: 0;
}

/* Status badge dengan dot */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .7rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 99px;
}

.badge-status--active {
    background: var(--accent-dim);
    color: var(--accent);
    border: 1px solid rgba(163,177,75,.2);
}

.badge-status--inactive {
    background: var(--surface-4);
    color: var(--txt-muted);
    border: 1px solid var(--border-subtle);
}

.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    display: inline-block;
    flex-shrink: 0;
}

/* Table row hover lebih smooth */
.table-matchgo tbody tr {
    transition: background .12s;
}

/* Responsive breakpoint */
@media (max-width: 767px) {
    .team-header-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .team-stat-row {
        width: 100%;
    }

    .team-stat-pill {
        flex: 1;
        min-width: 60px;
    }

    .team-empty-hints {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endpush