@extends('user.layouts.app')

@section('title', 'Detail Split Bill — MATCHGO')
@section('page-title', 'Detail Split Bill')

@push('styles')
<style>
    /* ── Big cost display ── */
    .cost-hero {
        text-align: center;
        padding: 2rem 1rem;
    }

    .cost-hero-label {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--txt-faint);
        margin-bottom: 8px;
    }

    .cost-hero-amount {
        font-family: 'Manrope', sans-serif;
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--accent);
        line-height: 1;
    }

    .cost-hero-sub {
        font-size: .8rem;
        color: var(--txt-muted);
        margin-top: 6px;
    }

    /* ── Team cost cards ── */
    .team-cost-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 24px;
    }

    @media (max-width: 576px) { .team-cost-grid { grid-template-columns: 1fr; } }

    .team-cost-card {
        background: var(--surface-3);
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        padding: 1.25rem;
        transition: border-color .2s;
    }

    .team-cost-card:hover { border-color: rgba(163,177,75,.25); }

    .team-cost-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .team-label {
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--txt-muted);
    }

    .team-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: var(--accent-dim);
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
    }

    .team-cost-total {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--txt-primary);
        margin-bottom: 4px;
    }

    .team-cost-per {
        font-size: .8rem;
        color: var(--txt-muted);
    }

    .team-cost-per strong {
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        color: var(--accent);
    }

    .team-cost-players {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border-subtle);
        font-size: .78rem;
        color: var(--txt-faint);
    }

    /* ── Player pill grid ── */
    .player-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .player-pill {
        background: var(--surface-4);
        border: 1px solid var(--border-subtle);
        border-radius: 99px;
        padding: 4px 12px;
        font-size: .72rem;
        font-weight: 600;
        color: var(--txt-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Info row ── */
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-subtle);
        font-size: .875rem;
    }

    .info-row:last-child { border-bottom: none; }

    .info-key {
        color: var(--txt-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-val {
        font-weight: 600;
        color: var(--txt-primary);
    }

    /* ── Status banner ── */
    .status-banner {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: .85rem;
        font-weight: 600;
    }

    .status-banner.finalized {
        background: var(--accent-dim);
        border: 1px solid rgba(163,177,75,.25);
        color: var(--accent);
    }

    .status-banner.draft {
        background: rgba(251,191,36,.08);
        border: 1px solid rgba(251,191,36,.20);
        color: #fcd34d;
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
    <li class="active">Detail</li>
</ul>

{{-- Page header --}}
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1><i class="bi bi-receipt me-2 text-accent"></i>Detail Split Bill</h1>
        <p>{{ $matchCost->match->title ?? 'Match #' . $matchCost->match_id }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(!$matchCost->is_finalized)
            <a href="{{ route('match-cost.edit', $matchCost) }}" class="btn-outline-lime btn-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form method="POST" action="{{ route('match-cost.finalize', $matchCost) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-lime btn-sm"
                        onclick="return confirm('Finalisasi split bill ini? Setelah final tidak bisa diubah.')">
                    <i class="bi bi-patch-check"></i> Finalisasi
                </button>
            </form>
        @endif
        <a href="{{ route('match-cost.index') }}" class="btn-outline-lime btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Status banner --}}
@if($matchCost->is_finalized)
    <div class="status-banner finalized">
        <i class="bi bi-patch-check-fill" style="font-size:1.1rem;"></i>
        Split bill ini sudah difinalisasi dan dikunci
    </div>
@else
    <div class="status-banner draft">
        <i class="bi bi-pencil-square" style="font-size:1.1rem;"></i>
        Status: Draft — Belum difinalisasi
    </div>
@endif

<div class="row g-4">
    {{-- Left: Cost breakdown --}}
    <div class="col-lg-8">

        {{-- Hero total --}}
        <div class="card-matchgo mb-4">
            <div class="cost-hero">
                <div class="cost-hero-label">💰 Total Biaya Lapangan</div>
                <div class="cost-hero-amount">
                    Rp {{ number_format($matchCost->total_venue_cost, 0, ',', '.') }}
                </div>
                <div class="cost-hero-sub">
                    Dibagi rata antara tim kandang &amp; tamu
                </div>
            </div>

            {{-- Team cost cards --}}
            <div class="team-cost-grid">
                {{-- Home team --}}
                <div class="team-cost-card">
                    <div class="team-cost-card-head">
                        <span class="team-label">🏠 Tim Kandang</span>
                        <div class="team-icon">🛡️</div>
                    </div>
                    <div class="team-cost-total">
                        Rp {{ number_format($matchCost->home_team_cost, 0, ',', '.') }}
                    </div>
                    <div class="team-cost-per">
                        <strong>Rp {{ number_format($matchCost->home_cost_per_player, 0, ',', '.') }}</strong>
                        per pemain
                    </div>
                    <div class="team-cost-players">
                        {{ $matchCost->home_team_players }} pemain
                        <div class="player-pills mt-1">
                            @for($i = 1; $i <= $matchCost->home_team_players; $i++)
                                <span class="player-pill">
                                    <i class="bi bi-person-fill"></i> P{{ $i }}
                                </span>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Away team --}}
                <div class="team-cost-card">
                    <div class="team-cost-card-head">
                        <span class="team-label">✈️ Tim Tamu</span>
                        <div class="team-icon">⚔️</div>
                    </div>
                    <div class="team-cost-total">
                        Rp {{ number_format($matchCost->away_team_cost, 0, ',', '.') }}
                    </div>
                    <div class="team-cost-per">
                        <strong>Rp {{ number_format($matchCost->away_cost_per_player, 0, ',', '.') }}</strong>
                        per pemain
                    </div>
                    <div class="team-cost-players">
                        {{ $matchCost->away_team_players }} pemain
                        <div class="player-pills mt-1">
                            @for($i = 1; $i <= $matchCost->away_team_players; $i++)
                                <span class="player-pill">
                                    <i class="bi bi-person-fill"></i> P{{ $i }}
                                </span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($matchCost->notes)
        <div class="card-matchgo">
            <div class="mg-section-label mb-3"><i class="bi bi-chat-left-text me-1"></i> Catatan</div>
            <p style="font-size:.875rem; color:var(--txt-secondary); line-height:1.7; margin:0;">
                {{ $matchCost->notes }}
            </p>
        </div>
        @endif
    </div>

    {{-- Right: Info --}}
    <div class="col-lg-4">
        <div class="card-matchgo">
            <div class="mg-section-label mb-2"><i class="bi bi-info-circle me-1"></i> Informasi</div>

            <div class="info-row">
                <span class="info-key"><i class="bi bi-calendar-event"></i> Pertandingan</span>
                <span class="info-val">{{ $matchCost->match->title ?? 'Match #' . $matchCost->match_id }}</span>
            </div>

            @if($matchCost->match && $matchCost->match->scheduled_at)
            <div class="info-row">
                <span class="info-key"><i class="bi bi-clock"></i> Jadwal</span>
                <span class="info-val">{{ $matchCost->match->scheduled_at->format('d M Y') }}</span>
            </div>
            @endif

            @if($matchCost->match && $matchCost->match->refereeRental)
            <div class="info-row">
                <span class="info-key"><i class="bi bi-people-fill"></i> Wasit</span>
                <span class="info-val">{{ optional($matchCost->match->refereeRental->referee)->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key"><i class="bi bi-cash-stack"></i> Biaya Wasit</span>
                <span class="info-val">Rp {{ number_format($matchCost->match->refereeRental->rental_cost, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="info-row">
                <span class="info-key"><i class="bi bi-people"></i> Total Pemain</span>
                <span class="info-val">
                    {{ $matchCost->home_team_players + $matchCost->away_team_players }} orang
                </span>
            </div>

            <div class="info-row">
                <span class="info-key"><i class="bi bi-patch-check"></i> Status</span>
                <span class="info-val">
                    @if($matchCost->is_finalized)
                        <span style="color:var(--accent);">Final</span>
                    @else
                        <span style="color:#fcd34d;">Draft</span>
                    @endif
                </span>
            </div>

            <div class="info-row">
                <span class="info-key"><i class="bi bi-calendar-plus"></i> Dibuat</span>
                <span class="info-val">{{ $matchCost->created_at->format('d M Y') }}</span>
            </div>

            <div class="info-row">
                <span class="info-key"><i class="bi bi-pencil"></i> Diupdate</span>
                <span class="info-val">{{ $matchCost->updated_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Delete (only draft) --}}
        @if(!$matchCost->is_finalized)
        <div class="card-matchgo mt-4" style="border-color:rgba(239,68,68,.15);">
            <div class="mg-section-label mb-2" style="color:#f87171;"><i class="bi bi-exclamation-triangle me-1"></i> Zona Bahaya</div>
            <p style="font-size:.8rem; color:var(--txt-muted); margin-bottom:12px;">
                Menghapus split bill ini tidak bisa dibatalkan.
            </p>
            <form method="POST" action="{{ route('match-cost.destroy', $matchCost) }}"
                  onsubmit="return confirm('Yakin ingin menghapus split bill ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-matchgo-danger w-100">
                    <i class="bi bi-trash"></i> Hapus Split Bill
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection