{{-- resources/views/user/matchmaking/outgoing.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Tantangan Terkirim — MATCHGO')
@section('page-title', 'Tantangan Terkirim')

@push('styles')
<style>
    .mm-out-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        overflow: hidden;
    }

    .mm-out-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        border-radius: 3px 0 0 3px;
    }

    .mm-out-card.status-pending::before  { background: #fcd34d; }
    .mm-out-card.status-matched::before  { background: var(--accent); }
    .mm-out-card.status-rejected::before { background: #f87171; }
    .mm-out-card.status-cancelled::before{ background: var(--txt-faint); }

    .mm-out-avatar {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--accent-dim);
        border: 1.5px solid rgba(163,177,75,0.22);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 800; font-size: 1rem;
        color: var(--accent); flex-shrink: 0;
    }

    .mm-out-body { flex: 1; min-width: 0; }

    .mm-out-team-name {
        font-family: 'Manrope', sans-serif;
        font-size: 0.925rem; font-weight: 700;
        color: var(--txt-primary); margin-bottom: 4px;
    }

    .mm-out-meta {
        display: flex; flex-wrap: wrap; gap: 10px;
        font-size: 0.75rem; color: var(--txt-muted);
        margin-bottom: 10px;
    }

    .mm-out-meta span { display: flex; align-items: center; gap: 4px; }
    .mm-out-meta i { color: var(--accent); }

    .mm-status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.7rem; font-weight: 700;
        padding: 3px 10px; border-radius: 99px;
    }

    .mm-badge-pending  { background:rgba(251,191,36,0.12); color:#fcd34d; border:1px solid rgba(251,191,36,0.25); }
    .mm-badge-matched  { background:var(--accent-dim); color:var(--accent); border:1px solid rgba(163,177,75,0.25); }
    .mm-badge-rejected { background:rgba(248,113,113,0.10); color:#f87171; border:1px solid rgba(248,113,113,0.25); }
    .mm-badge-cancelled{ background:var(--surface-4); color:var(--txt-faint); border:1px solid var(--border-subtle); }
</style>
@endpush

@section('content')

<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('matchmaking.index') }}">Matchmaking</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Tantangan Terkirim</span></li>
</ul>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:10px;">
    <div>
        <h2 style="font-family:'Manrope',sans-serif;font-size:1.35rem;font-weight:800;color:var(--txt-primary);margin-bottom:4px;">
            <i class="bi bi-send-fill" style="color:var(--accent);"></i> Tantangan Terkirim
        </h2>
        <p style="font-size:0.825rem;color:var(--txt-muted);margin:0;">
            Semua tantangan yang dikirim oleh <strong>{{ $myTeam->name }}</strong>
        </p>
    </div>
    <a href="{{ route('matchmaking.incoming') }}" class="btn-outline-lime btn-sm">
        <i class="bi bi-inbox"></i> Tantangan Masuk
    </a>
</div>

@if (session('success'))
    <div class="alert-matchgo alert-success mb-4">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if (session('info'))
    <div class="alert-matchgo alert-info mb-4">
        <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
    </div>
@endif

@if ($challenges->isEmpty())
    <div style="text-align:center;padding:4rem 1rem;">
        <div style="width:64px;height:64px;border-radius:16px;background:var(--surface-3);border:1px solid var(--border-medium);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--txt-faint);margin:0 auto 1rem;">
            <i class="bi bi-send"></i>
        </div>
        <h4 style="font-family:'Manrope',sans-serif;font-weight:700;color:var(--txt-secondary);margin-bottom:6px;">
            Belum Ada Tantangan Terkirim
        </h4>
        <p style="font-size:0.85rem;color:var(--txt-muted);">
            Temukan lawan dan kirim tantangan dari halaman matchmaking.
        </p>
        <a href="{{ route('matchmaking.index') }}" class="mm-search-btn" style="display:inline-flex;margin-top:1rem;width:auto;padding:10px 20px;">
            <i class="bi bi-search"></i> Cari Lawan
        </a>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:12px;">
        @foreach ($challenges as $challenge)
            @php $opponent = $challenge->matchedTeam; @endphp

            <div class="mm-out-card status-{{ $challenge->status }}">
                <div class="mm-out-avatar">{{ $opponent ? strtoupper(substr($opponent->name, 0, 2)) : '?' }}</div>

                <div class="mm-out-body">
                    <div class="mm-out-team-name">{{ $opponent->name ?? 'Tim tidak ditemukan' }}</div>
                    <div class="mm-out-meta">
                        <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($challenge->preferred_date)->translatedFormat('l, d M Y') }}</span>
                        <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($challenge->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($challenge->end_time)->format('H:i') }}</span>
                    </div>

                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        @php
                            $statusMap = [
                                'pending'   => ['class' => 'mm-badge-pending',   'icon' => 'bi-hourglass-split', 'label' => 'Menunggu Respons'],
                                'matched'   => ['class' => 'mm-badge-matched',   'icon' => 'bi-check-circle-fill','label' => 'Diterima'],
                                'rejected'  => ['class' => 'mm-badge-rejected',  'icon' => 'bi-x-circle-fill',   'label' => 'Ditolak'],
                                'cancelled' => ['class' => 'mm-badge-cancelled', 'icon' => 'bi-slash-circle',    'label' => 'Dibatalkan'],
                            ];
                            $st = $statusMap[$challenge->status] ?? $statusMap['pending'];
                        @endphp

                        <span class="mm-status-badge {{ $st['class'] }}">
                            <i class="bi {{ $st['icon'] }}"></i> {{ $st['label'] }}
                        </span>

                        {{-- Tombol batalkan hanya jika masih pending --}}
                        @if ($challenge->status === 'pending')
                            <form action="{{ route('matchmaking.cancel', $challenge) }}" method="POST"
                                  onsubmit="return confirm('Batalkan tantangan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="font-size:0.72rem;font-weight:600;padding:3px 10px;border-radius:8px;background:transparent;border:1px solid var(--border-medium);color:var(--txt-muted);cursor:pointer;transition:all 0.15s;display:inline-flex;align-items:center;gap:4px;"
                                    onmouseover="this.style.borderColor='#f87171';this.style.color='#f87171';"
                                    onmouseout="this.style.borderColor='var(--border-medium)';this.style.color='var(--txt-muted)';">
                                    <i class="bi bi-trash3"></i> Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:1.25rem;">
        {{ $challenges->links() }}
    </div>
@endif

@endsection