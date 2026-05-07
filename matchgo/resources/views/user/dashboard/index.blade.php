@extends('user.layouts.app')

@section('title', 'Dashboard — MATCHGO')
@section('page-title', 'Dashboard')

@push('styles')
<style>

/* ══════════════════════════════════════════
   SECTION
══════════════════════════════════════════ */

.section-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:18px;
    gap:12px;
}

.section-title{
    font-family:'Manrope',sans-serif;
    font-size:1.8rem;
    font-weight:800;
    color:var(--txt-primary);
    margin:0;
}

.view-all{
    font-size:.85rem;
    font-weight:600;
    color:var(--accent);
    text-decoration:none;
    transition:.15s;
}

.view-all:hover{
    color:var(--txt-primary);
    text-decoration:none;
}

/* ══════════════════════════════════════════
   GRID
══════════════════════════════════════════ */

.dashboard-grid{
    display:grid;
    grid-template-columns:minmax(0,1fr) 340px;
    gap:20px;
    align-items:start;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
    margin-bottom:24px;
}

/* ══════════════════════════════════════════
   STAT CARD
══════════════════════════════════════════ */

.stat-card{
    background:var(--surface-2);
    border:1px solid var(--border-subtle);
    border-radius:18px;
    padding:20px;
    transition:
        border-color .18s,
        background .25s,
        transform .15s;
}

.stat-card:hover{
    border-color:rgba(163,177,75,.18);
    transform:translateY(-1px);
}

.stat-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:26px;
}

.stat-icon{
    width:46px;
    height:46px;
    border-radius:14px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:1.1rem;
}

.icon-accent{
    background:var(--accent-dim);
    color:var(--accent);
}

.icon-emerald{
    background:rgba(16,185,129,.12);
    color:#10b981;
}

.icon-red{
    background:rgba(239,68,68,.10);
    color:#ef4444;
}

.icon-yellow{
    background:rgba(245,158,11,.10);
    color:#f59e0b;
}

.badge-green{
    background:rgba(16,185,129,.10);
    color:#10b981;
}

.badge-red{
    background:rgba(239,68,68,.10);
    color:#ef4444;
}

.badge-green,
.badge-red{
    border-radius:999px;
    padding:4px 10px;
    font-size:.72rem;
    font-weight:700;
}

.stat-number{
    font-family:'Manrope',sans-serif;
    font-size:2.6rem;
    font-weight:800;
    line-height:1;
    color:var(--txt-primary);
}

.stat-number span{
    font-size:1rem;
    font-weight:600;
    color:var(--txt-muted);
}

.stat-text{
    margin-top:10px;
    font-size:.82rem;
    color:var(--txt-muted);
}

/* ══════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════ */

.empty-card{
    background:var(--surface-2);
    border:1px dashed var(--border-medium);
    border-radius:18px;
    padding:70px 20px;
    text-align:center;
}

.empty-icon{
    font-size:2.8rem;
    color:var(--txt-faint);
    margin-bottom:12px;
}

.empty-text{
    color:var(--txt-muted);
    font-size:.9rem;
}

/* ══════════════════════════════════════════
   QUICK ACTION
══════════════════════════════════════════ */

.quick-title{
    font-family:'Manrope',sans-serif;
    font-size:1.7rem;
    font-weight:800;
    color:var(--txt-primary);
    margin:0 0 18px 0;
}

.quick-actions{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.quick-card{
    background:var(--surface-2);
    border:1px solid var(--border-subtle);
    border-radius:16px;

    padding:16px;

    display:flex;
    align-items:center;
    gap:14px;

    text-decoration:none;

    transition:
        border-color .18s,
        background .2s,
        transform .15s;
}

.quick-card:hover{
    border-color:rgba(163,177,75,.25);
    background:var(--surface-3);
    transform:translateY(-1px);

    text-decoration:none;
}

.quick-icon{
    width:48px;
    height:48px;
    border-radius:13px;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:1rem;

    flex-shrink:0;
}

.quick-green{
    background:var(--accent-dim);
    color:var(--accent);
}

.quick-blue{
    background:rgba(59,130,246,.10);
    color:#60a5fa;
}

.quick-orange{
    background:rgba(249,115,22,.10);
    color:#fb923c;
}

.quick-purple{
    background:rgba(168,85,247,.10);
    color:#c084fc;
}

.quick-emerald{
    background:rgba(16,185,129,.10);
    color:#10b981;
}

.quick-yellow{
    background:rgba(245,158,11,.10);
    color:#f59e0b;
}

.quick-heading{
    font-family:'Manrope',sans-serif;
    font-size:.92rem;
    font-weight:700;
    color:var(--txt-primary);
}

.quick-sub{
    font-size:.76rem;
    color:var(--txt-muted);
    margin-top:3px;
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */

@media(max-width:1200px){

    .dashboard-grid{
        grid-template-columns:1fr;
    }

}

@media(max-width:992px){

    .stats-grid{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:575px){

    .stats-grid{
        grid-template-columns:1fr;
    }

    .section-title,
    .quick-title{
        font-size:1.35rem;
    }

}

</style>
@endpush

@section('content')

<div class="dashboard-grid">

    {{-- LEFT CONTENT --}}
    <div>

        {{-- =========================
            STATS
        ========================== --}}
        <div class="stats-grid">

            {{-- TOTAL MATCH --}}
            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon icon-accent">
                        🏆
                    </div>

                    <div class="badge-green">
                        {{ $totalWin }}W
                    </div>

                </div>

                <div class="stat-number">
                    {{ $totalMatch }}
                </div>

                <div class="stat-text">
                    Total Match
                </div>

            </div>

            {{-- WIN --}}
            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon icon-emerald">
                        ↗
                    </div>

                    <div class="badge-green">
                        {{ $winRate }}%
                    </div>

                </div>

                <div class="stat-number">
                    {{ $totalWin }}
                    <span>Win</span>
                </div>

                <div class="stat-text">
                    Menang
                </div>

            </div>

            {{-- LOSS --}}
            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon icon-red">
                        ↘
                    </div>

                    <div class="badge-red">
                        {{ $lossRate }}%
                    </div>

                </div>

                <div class="stat-number">
                    {{ $totalLoss }}
                    <span>Loss</span>
                </div>

                <div class="stat-text">
                    Kalah
                </div>

            </div>

            {{-- TEAM RATING --}}
            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon icon-yellow">
                        ⭐
                    </div>

                </div>

                <div class="stat-number">
                    {{ $teamRating }}
                </div>

                <div class="stat-text">
                    Team Rating
                </div>

            </div>

        </div>

        {{-- =========================
            UPCOMING MATCHES
        ========================== --}}
        <div>

            <div class="section-header">

                <h2 class="section-title">
                    Upcoming Matches
                </h2>

                {{-- VIEW ALL --}}
                <a href="{{ route('schedule.index') }}" class="view-all">
                    View All →
                </a>

            </div>

            <div class="empty-card">

                <div class="empty-icon">
                    ⚽
                </div>

                <div class="empty-text">
                    Belum ada pertandingan mendatang
                </div>

            </div>

        </div>

    </div>

    {{-- =========================
        RIGHT SIDEBAR
    ========================== --}}
    <div>

        <h2 class="quick-title">
            Aksi Cepat
        </h2>

        <div class="quick-actions">

            {{-- BUAT TIM --}}
            <a href="{{ route('team.create') }}" class="quick-card">

                <div class="quick-icon quick-green">
                    🛡
                </div>

                <div>
                    <div class="quick-heading">
                        Buat Tim
                    </div>

                    <div class="quick-sub">
                        Profil tim baru
                    </div>
                </div>

            </a>

            {{-- JADWAL BARU --}}
            <a href="{{ route('schedule.create') }}" class="quick-card">

                <div class="quick-icon quick-blue">
                    📅
                </div>

                <div>
                    <div class="quick-heading">
                        Jadwal Baru
                    </div>

                    <div class="quick-sub">
                        Input jadwal
                    </div>
                </div>

            </a>

            {{-- ATUR LOKASI --}}
            <a href="{{ route('location.index') }}" class="quick-card">

                <div class="quick-icon quick-orange">
                    📍
                </div>

                <div>
                    <div class="quick-heading">
                        Atur Lokasi
                    </div>

                    <div class="quick-sub">
                        Lokasi pertandingan
                    </div>
                </div>

            </a>

            {{-- CARI LAWAN --}}
            <a href="{{ route('opponent.search') }}" class="quick-card">

                <div class="quick-icon quick-purple">
                    👥
                </div>

                <div>
                    <div class="quick-heading">
                        Cari Lawan
                    </div>

                    <div class="quick-sub">
                        Temukan lawan tanding
                    </div>
                </div>

            </a>

            {{-- RIWAYAT --}}
            <a href="{{ route('match.history') }}" class="quick-card">

                <div class="quick-icon quick-emerald">
                    ☰
                </div>

                <div>
                    <div class="quick-heading">
                        Riwayat
                    </div>

                    <div class="quick-sub">
                        Semua pertandingan
                    </div>
                </div>

            </a>

            {{-- HITUNG BIAYA --}}
            <a href="{{ route('cost.calculator') }}" class="quick-card">

                <div class="quick-icon quick-yellow">
                    🧮
                </div>

                <div>
                    <div class="quick-heading">
                        Hitung Biaya
                    </div>

                    <div class="quick-sub">
                        Kalkulasi biaya main
                    </div>
                </div>

            </a>

        </div>
    </div>

</div>

@endsection