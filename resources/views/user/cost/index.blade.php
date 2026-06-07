@extends('user.layouts.app')

@section('title', 'Split Bill — MATCHGO')
@section('page-title', 'Split Bill')

@push('styles')
<style>
    /* ── Status pill ── */
    .pill-finalized {
        background: var(--accent-dim);
        color: var(--accent);
        border: 1px solid rgba(163,177,75,0.25);
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pill-draft {
        background: rgba(251,191,36,0.08);
        color: #fcd34d;
        border: 1px solid rgba(251,191,36,0.20);
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Summary cards ── */
    .summary-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    @media (max-width: 767px) { .summary-strip { grid-template-columns: 1fr; } }

    /* ── Action cell ── */
    .action-cell { display: flex; align-items: center; gap: 6px; }

    /* ── Cost highlight ── */
    .cost-value {
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        color: var(--txt-primary);
    }

    /* ── Table wrapper ── */
    .table-wrap {
        overflow-x: auto;
        border-radius: 14px;
        border: 1px solid var(--border-subtle);
    }

    .table-wrap .table-matchgo thead th:first-child { border-radius: 0; }
    .table-wrap .table-matchgo thead th:last-child  { border-radius: 0; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li class="active">Split Bill</li>
</ul>

{{-- Page header --}}
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1><i class="bi bi-cash-coin me-2 text-accent"></i>Split Bill</h1>
        <p>Kelola pembagian biaya lapangan untuk setiap pertandingan</p>
    </div>
    <a href="{{ route('match-cost.create') }}" class="btn-lime">
        <i class="bi bi-plus-lg"></i> Buat Split Bill
    </a>
</div>

{{-- Summary strip --}}
@php
    $totalAll  = $costs->sum('total_venue_cost');
    $countAll  = $costs->total();
    $finalized = $costs->where('is_finalized', true)->count();
@endphp

<div class="summary-strip">
    <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="stat-card-label">Total Biaya</span>
            <div class="stat-card-icon"><i class="bi bi-currency-dollar"></i></div>
        </div>
        <div class="stat-card-value">Rp {{ number_format($totalAll, 0, ',', '.') }}</div>
        <div class="stat-card-sub">Seluruh pertandingan</div>
    </div>

    <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="stat-card-label">Total Tagihan</span>
            <div class="stat-card-icon"><i class="bi bi-receipt"></i></div>
        </div>
        <div class="stat-card-value">{{ $countAll }}</div>
        <div class="stat-card-sub">Split bill terdaftar</div>
    </div>

    <div class="stat-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="stat-card-label">Sudah Final</span>
            <div class="stat-card-icon"><i class="bi bi-patch-check"></i></div>
        </div>
        <div class="stat-card-value">{{ $finalized }}</div>
        <div class="stat-card-sub">Dari {{ $countAll }} total</div>
    </div>
</div>

{{-- Table --}}
<div class="card-matchgo p-0 overflow-hidden">
    <div class="d-flex align-items-center justify-content-between" style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle);">
        <span class="font-semi" style="font-size:.9rem;">Daftar Split Bill</span>
        <span class="text-faint" style="font-size:.78rem;">{{ $costs->total() }} tagihan</span>
    </div>

    @if($costs->isEmpty())
        <div class="mg-empty">
            <div class="mg-empty-icon"><i class="bi bi-receipt-cutoff"></i></div>
            <h4>Belum ada split bill</h4>
            <p>Buat split bill pertama untuk pertandinganmu</p>
            <a href="{{ route('match-cost.create') }}" class="btn-lime mt-3">
                <i class="bi bi-plus-lg"></i> Buat Sekarang
            </a>
        </div>
    @else
        <div class="table-wrap" style="border:none; border-radius:0;">
            <table class="table-matchgo">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pertandingan</th>
                        <th>Total Biaya</th>
                        <th>Per Tim</th>
                        <th>Pemain</th>
                        <th>Per Pemain</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($costs as $i => $cost)
                    <tr>
                        <td class="text-faint">{{ $costs->firstItem() + $i }}</td>

                        <td>
                            <div class="font-semi" style="font-size:.85rem;">
                                {{ $cost->match->title ?? 'Match #' . $cost->match_id }}
                            </div>
                            <div class="text-faint" style="font-size:.72rem; margin-top:2px;">
                                {{ optional($cost->match->scheduled_at)->format('d M Y') ?? '-' }}
                            </div>
                        </td>

                        <td>
                            <span class="cost-value text-accent">
                                Rp {{ number_format($cost->total_venue_cost, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            <div style="font-size:.8rem;">
                                <span class="text-faint">🏠</span>
                                Rp {{ number_format($cost->home_team_cost, 0, ',', '.') }}
                            </div>
                            <div style="font-size:.8rem; margin-top:2px;">
                                <span class="text-faint">✈️</span>
                                Rp {{ number_format($cost->away_team_cost, 0, ',', '.') }}
                            </div>
                        </td>

                        <td>
                            <div style="font-size:.8rem;">
                                🏠 {{ $cost->home_team_players }} pemain
                            </div>
                            <div style="font-size:.8rem; margin-top:2px;">
                                ✈️ {{ $cost->away_team_players }} pemain
                            </div>
                        </td>

                        <td>
                            <div style="font-size:.8rem; font-weight:600;">
                                Rp {{ number_format($cost->home_cost_per_player, 0, ',', '.') }}
                            </div>
                            <div style="font-size:.72rem; color:var(--txt-faint);">per orang</div>
                        </td>

                        <td>
                            @if($cost->is_finalized)
                                <span class="pill-finalized">
                                    <i class="bi bi-patch-check-fill"></i> Final
                                </span>
                            @else
                                <span class="pill-draft">
                                    <i class="bi bi-pencil"></i> Draft
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-cell">
                                <a href="{{ route('match-cost.show', $cost) }}"
                                   class="mg-icon-btn" title="Lihat Detail" style="width:30px;height:30px;border-radius:7px;">
                                    <i class="bi bi-eye" style="font-size:.8rem;"></i>
                                </a>
                                @if(!$cost->is_finalized)
                                <a href="{{ route('match-cost.edit', $cost) }}"
                                   class="mg-icon-btn" title="Edit" style="width:30px;height:30px;border-radius:7px;">
                                    <i class="bi bi-pencil" style="font-size:.8rem;"></i>
                                </a>
                                <form method="POST" action="{{ route('match-cost.destroy', $cost) }}"
                                      onsubmit="return confirm('Hapus split bill ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="mg-icon-btn"
                                            title="Hapus"
                                            style="width:30px;height:30px;border-radius:7px;color:#f87171;border-color:rgba(239,68,68,.2);">
                                        <i class="bi bi-trash" style="font-size:.8rem;"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($costs->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-subtle);">
            {{ $costs->links() }}
        </div>
        @endif
    @endif
</div>

@endsection