{{-- resources/views/user/matchmaking/incoming.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Tantangan Masuk — MATCHGO')
@section('page-title', 'Tantangan Masuk')

@push('styles')
<style>
    .mm-challenge-card {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        transition: border-color 0.2s;
        position: relative;
        overflow: hidden;
    }

    .mm-challenge-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--accent);
        border-radius: 3px 0 0 3px;
    }

    .mm-challenge-avatar {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: var(--accent-dim);
        border: 1.5px solid rgba(163,177,75,0.22);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 1rem;
        color: var(--accent);
        flex-shrink: 0;
    }

    .mm-challenge-body { flex: 1; min-width: 0; }

    .mm-challenge-team-name {
        font-family: 'Manrope', sans-serif;
        font-size: 0.925rem;
        font-weight: 700;
        color: var(--txt-primary);
        margin-bottom: 4px;
    }

    .mm-challenge-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.75rem;
        color: var(--txt-muted);
        margin-bottom: 10px;
    }

    .mm-challenge-meta span { display: flex; align-items: center; gap: 4px; }
    .mm-challenge-meta i { color: var(--accent); }

    .mm-challenge-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .mm-btn-accept {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.775rem; font-weight: 600;
        padding: 7px 14px; border-radius: 8px;
        background: var(--accent); color: var(--btn-primary-txt);
        border: none; cursor: pointer;
        transition: background 0.15s, transform 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .mm-btn-accept:hover { background: var(--accent-hover); transform: translateY(-1px); }

    .mm-btn-reject {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.775rem; font-weight: 600;
        padding: 7px 14px; border-radius: 8px;
        background: var(--surface-4); color: var(--txt-secondary);
        border: 1px solid var(--border-medium); cursor: pointer;
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .mm-btn-reject:hover {
        border-color: #f87171;
        color: #f87171;
        background: rgba(248,113,113,0.08);
    }

    /* Reject modal */
    .mm-reject-modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.65);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex; align-items: center; justify-content: center;
        padding: 1rem;
        opacity: 0; pointer-events: none;
        transition: opacity 0.2s;
    }

    .mm-reject-modal-backdrop.open {
        opacity: 1; pointer-events: all;
    }

    .mm-reject-modal {
        background: var(--surface-2);
        border: 1px solid var(--border-medium);
        border-radius: 20px;
        padding: 1.5rem;
        width: 100%; max-width: 400px;
        transform: translateY(10px) scale(0.98);
        transition: transform 0.2s;
    }

    .mm-reject-modal-backdrop.open .mm-reject-modal {
        transform: translateY(0) scale(1);
    }

    .mm-form-label {
        display: block;
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--txt-faint); margin-bottom: 6px;
    }

    .mm-form-input {
        width: 100%;
        background: var(--surface-3);
        border: 1px solid var(--border-medium);
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 0.85rem;
        color: var(--txt-primary);
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.15s;
        resize: vertical;
    }

    .mm-form-input:focus { border-color: var(--accent); }
</style>
@endpush

@section('content')

<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('matchmaking.index') }}">Matchmaking</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Tantangan Masuk</span></li>
</ul>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:10px;">
    <div>
        <h2 style="font-family:'Manrope',sans-serif; font-size:1.35rem; font-weight:800; color:var(--txt-primary); margin-bottom:4px;">
            <i class="bi bi-inbox-fill" style="color:var(--accent);"></i> Tantangan Masuk
        </h2>
        <p style="font-size:0.825rem; color:var(--txt-muted); margin:0;">
            Tim lain yang menantang <strong>{{ $myTeam->name }}</strong>
        </p>
    </div>
    <a href="{{ route('matchmaking.outgoing') }}" class="btn-outline-lime btn-sm">
        <i class="bi bi-send"></i> Tantangan Terkirim
    </a>
</div>

@if (session('success'))
    <div class="alert-matchgo alert-success mb-4">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-matchgo alert-error mb-4">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
@endif

@if ($challenges->isEmpty())
    <div style="text-align:center; padding:4rem 1rem;">
        <div style="width:64px;height:64px;border-radius:16px;background:var(--surface-3);border:1px solid var(--border-medium);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--txt-faint);margin:0 auto 1rem;">
            <i class="bi bi-inbox"></i>
        </div>
        <h4 style="font-family:'Manrope',sans-serif;font-weight:700;color:var(--txt-secondary);margin-bottom:6px;">
            Belum Ada Tantangan Masuk
        </h4>
        <p style="font-size:0.85rem;color:var(--txt-muted);">
            Saat tim lain menantangmu, tantangan akan muncul di sini.
        </p>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:12px;">
        @foreach ($challenges as $challenge)
            @php $challenger = $challenge->team; @endphp

            <div class="mm-challenge-card">
                <div class="mm-challenge-avatar">{{ strtoupper(substr($challenger->name, 0, 2)) }}</div>

                <div class="mm-challenge-body">
                    <div class="mm-challenge-team-name">{{ $challenger->name }}</div>
                    <div class="mm-challenge-meta">
                        <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($challenge->preferred_date)->translatedFormat('l, d M Y') }}</span>
                        <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($challenge->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($challenge->end_time)->format('H:i') }}</span>
                        @if ($challenger->city)
                            <span><i class="bi bi-geo-alt"></i> {{ $challenger->city }}</span>
                        @endif
                        @if ($challenger->level)
                            <span><i class="bi bi-trophy"></i> {{ ucfirst(str_replace('_',' ',$challenger->level)) }}</span>
                        @endif
                    </div>
                    <div class="mm-challenge-actions">
                        {{-- Accept --}}
                        <form action="{{ route('matchmaking.accept', $challenge) }}" method="POST">
                            @csrf
                            <button type="submit" class="mm-btn-accept">
                                <i class="bi bi-check-lg"></i> Terima
                            </button>
                        </form>

                        {{-- Reject (buka modal) --}}
                        <button
                            type="button"
                            class="mm-btn-reject"
                            onclick="document.getElementById('rejectModal_{{ $challenge->id }}').classList.add('open')"
                        >
                            <i class="bi bi-x-lg"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>

            {{-- Reject Modal --}}
            <div class="mm-reject-modal-backdrop" id="rejectModal_{{ $challenge->id }}"
                 onclick="if(event.target===this) this.classList.remove('open')">
                <div class="mm-reject-modal">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                        <div style="font-family:'Manrope',sans-serif;font-size:0.95rem;font-weight:800;color:var(--txt-primary);display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-x-circle-fill" style="color:#f87171;"></i> Tolak Tantangan
                        </div>
                        <button
                            onclick="document.getElementById('rejectModal_{{ $challenge->id }}').classList.remove('open')"
                            style="width:32px;height:32px;border-radius:8px;background:var(--surface-4);border:1px solid var(--border-subtle);color:var(--txt-muted);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:0.9rem;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <p style="font-size:0.82rem;color:var(--txt-muted);margin-bottom:1rem;">
                        Menolak tantangan dari <strong style="color:var(--txt-primary);">{{ $challenger->name }}</strong>.
                        Tim mereka akan mendapat notifikasi.
                    </p>

                    <form action="{{ route('matchmaking.reject', $challenge) }}" method="POST">
                        @csrf
                        <div style="margin-bottom:1rem;">
                            <label class="mm-form-label">Alasan penolakan (opsional)</label>
                            <textarea name="reject_reason" class="mm-form-input" rows="3"
                                placeholder="Contoh: Jadwal bentrok, lokasi terlalu jauh..."></textarea>
                        </div>
                        <button type="submit" style="width:100%;padding:10px;border-radius:10px;background:rgba(248,113,113,0.15);color:#f87171;border:1px solid rgba(248,113,113,0.30);font-weight:700;font-size:0.875rem;cursor:pointer;font-family:'Manrope',sans-serif;transition:background 0.15s;">
                            <i class="bi bi-x-circle"></i> Konfirmasi Penolakan
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:1.25rem;">
        {{ $challenges->links() }}
    </div>
@endif

@endsection