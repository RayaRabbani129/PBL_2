@extends('user.layouts.app')

@section('title', 'Dashboard — MATCHGO')

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="mb-1">
            Halo, {{ Auth::user()->name }}!
            <span style="font-size: 1.2rem;">👋</span>
        </h1>
        <p>Berikut ringkasan aktivitas tim kamu hari ini.</p>
    </div>
    <a href="#" class="btn btn-lime btn-sm px-3">
        <i class="bi bi-search me-2"></i>Cari Lawan
    </a>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted, #94a3b8); text-transform: uppercase; letter-spacing: 0.05em;">Total Match</span>
                <div style="width: 34px; height: 34px; background: rgba(163,230,53,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--lime, #a3e635); font-size: 1rem;">
                    <i class="bi bi-trophy"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary, #f1f5f9); line-height: 1;">
                {{ $totalMatches ?? 0 }}
            </div>
            <div style="font-size: 0.78rem; color: var(--text-muted, #64748b); margin-top: 0.4rem;">
                Pertandingan dimainkan
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted, #94a3b8); text-transform: uppercase; letter-spacing: 0.05em;">Kemenangan</span>
                <div style="width: 34px; height: 34px; background: rgba(163,230,53,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--lime, #a3e635); font-size: 1rem;">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--lime, #a3e635); line-height: 1;">
                {{ $wins ?? 0 }}
            </div>
            <div style="font-size: 0.78rem; color: var(--text-muted, #64748b); margin-top: 0.4rem;">
                Total menang
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted, #94a3b8); text-transform: uppercase; letter-spacing: 0.05em;">Kekalahan</span>
                <div style="width: 34px; height: 34px; background: rgba(239,68,68,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f87171; font-size: 1rem;">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: #f87171; line-height: 1;">
                {{ $losses ?? 0 }}
            </div>
            <div style="font-size: 0.78rem; color: var(--text-muted, #64748b); margin-top: 0.4rem;">
                Total kalah
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted, #94a3b8); text-transform: uppercase; letter-spacing: 0.05em;">Win Rate</span>
                <div style="width: 34px; height: 34px; background: rgba(34,211,238,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--cyan, #22d3ee); font-size: 1rem;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--cyan, #22d3ee); line-height: 1;">
                @php
                    $total = $totalMatches ?? 0;
                    $winRate = $total > 0 ? round(($wins ?? 0) / $total * 100) : 0;
                @endphp
                {{ $winRate }}%
            </div>
            {{-- Win rate bar --}}
            <div style="margin-top: 0.6rem;">
                <div style="height: 4px; background: rgba(255,255,255,0.08); border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $winRate }}%; background: var(--cyan, #22d3ee); border-radius: 4px; transition: width 1s ease;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content Grid --}}
<div class="row g-4">

    {{-- Pertandingan Mendatang --}}
    <div class="col-lg-6">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-primary, #f1f5f9); margin: 0;">
                    <i class="bi bi-calendar-event me-2" style="color: var(--lime, #a3e635);"></i>
                    Pertandingan Mendatang
                </h2>
                <a href="#" style="font-size: 0.8rem; color: var(--lime, #a3e635); text-decoration: none;">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($upcomingMatches ?? [] as $match)
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; background: rgba(163,230,53,0.04); border: 1px solid rgba(163,230,53,0.1); border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: rgba(163,230,53,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--lime, #a3e635); font-size: 1.1rem; flex-shrink: 0;">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $match->name }}
                            </p>
                            <p style="font-size: 0.775rem; color: var(--text-muted, #94a3b8); margin: 0;">
                                <i class="bi bi-clock me-1"></i>{{ $match->scheduled_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span style="font-size: 0.72rem; font-weight: 600; background: rgba(163,230,53,0.12); color: var(--lime, #a3e635); padding: 3px 10px; border-radius: 20px; white-space: nowrap; flex-shrink: 0;">
                            Akan Datang
                        </span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2.5rem 1rem;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4;">🏟️</div>
                        <p style="font-size: 0.875rem; color: var(--text-muted, #64748b); margin: 0;">
                            Tidak ada pertandingan mendatang
                        </p>
                        <a href="#" class="btn btn-outline-lime btn-sm mt-3">
                            <i class="bi bi-search me-1"></i>Cari Lawan Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pertandingan Terbaru --}}
    <div class="col-lg-6">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-primary, #f1f5f9); margin: 0;">
                    <i class="bi bi-clock-history me-2" style="color: var(--cyan, #22d3ee);"></i>
                    Pertandingan Terbaru
                </h2>
                <a href="#" style="font-size: 0.8rem; color: var(--lime, #a3e635); text-decoration: none;">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentMatches ?? [] as $match)
                    @php
                        $isWin = isset($match->result) && $match->result === 'win';
                        $isLoss = isset($match->result) && $match->result === 'loss';
                    @endphp
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: {{ $isWin ? 'rgba(163,230,53,0.1)' : ($isLoss ? 'rgba(239,68,68,0.1)' : 'rgba(255,255,255,0.05)') }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            @if($isWin) 🏆
                            @elseif($isLoss) 😔
                            @else ⚽
                            @endif
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $match->name }}
                            </p>
                            <p style="font-size: 0.775rem; color: var(--text-muted, #94a3b8); margin: 0;">
                                <i class="bi bi-calendar3 me-1"></i>{{ $match->played_at->format('d M Y') }}
                            </p>
                        </div>
                        @if(isset($match->result))
                            <span style="font-size: 0.72rem; font-weight: 700; background: {{ $isWin ? 'rgba(163,230,53,0.12)' : 'rgba(239,68,68,0.12)' }}; color: {{ $isWin ? 'var(--lime, #a3e635)' : '#f87171' }}; padding: 3px 10px; border-radius: 20px; flex-shrink: 0;">
                                {{ $isWin ? 'Menang' : 'Kalah' }}
                            </span>
                        @endif
                    </div>
                @empty
                    <div style="text-align: center; padding: 2.5rem 1rem;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4;">⚽</div>
                        <p style="font-size: 0.875rem; color: var(--text-muted, #64748b); margin: 0;">
                            Belum ada riwayat pertandingan
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tim Saya --}}
    <div class="col-lg-8">
        <div class="card-matchgo">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-primary, #f1f5f9); margin: 0;">
                    <i class="bi bi-people me-2" style="color: var(--purple, #a78bfa);"></i>
                    Tim Saya
                </h2>
                <a href="#" class="btn btn-outline-lime btn-sm" style="font-size: 0.78rem; padding: 0.3rem 0.8rem;">
                    <i class="bi bi-plus me-1"></i>Buat Tim
                </a>
            </div>

            @forelse(Auth::user()->teams ?? [] as $team)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(167,139,250,0.04); border: 1px solid rgba(167,139,250,0.1); border-radius: 10px; margin-bottom: 0.75rem;">
                    <div style="width: 44px; height: 44px; background: rgba(167,139,250,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        ⚽
                    </div>
                    <div style="flex: 1;">
                        <p style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary, #f1f5f9); margin: 0;">{{ $team->name }}</p>
                        <p style="font-size: 0.775rem; color: var(--text-muted, #94a3b8); margin: 0;">
                            {{ $team->members_count ?? 0 }} anggota
                            &bull; {{ ucfirst($team->pivot->role ?? 'member') }}
                        </p>
                    </div>
                    <a href="#" style="font-size: 0.8rem; color: var(--lime, #a3e635); text-decoration: none; white-space: nowrap;">
                        Lihat <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @empty
                <div style="text-align: center; padding: 2rem 1rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4;">👥</div>
                    <p style="font-size: 0.875rem; color: var(--text-muted, #64748b); margin-bottom: 1rem;">
                        Kamu belum bergabung dengan tim manapun
                    </p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="#" class="btn btn-lime btn-sm">
                            <i class="bi bi-plus me-1"></i>Buat Tim Baru
                        </a>
                        <a href="#" class="btn btn-outline-lime btn-sm">
                            <i class="bi bi-search me-1"></i>Cari Tim
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="card-matchgo h-100">
            <h2 style="font-size: 1rem; font-weight: 700; color: var(--text-primary, #f1f5f9); margin-bottom: 1.25rem;">
                <i class="bi bi-lightning-charge me-2" style="color: var(--lime, #a3e635);"></i>
                Aksi Cepat
            </h2>

            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                <a href="#" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; background: rgba(163,230,53,0.05); border: 1px solid rgba(163,230,53,0.12); border-radius: 10px; text-decoration: none; transition: border-color 0.2s, background 0.2s;"
                   onmouseover="this.style.borderColor='rgba(163,230,53,0.3)'; this.style.background='rgba(163,230,53,0.09)'"
                   onmouseout="this.style.borderColor='rgba(163,230,53,0.12)'; this.style.background='rgba(163,230,53,0.05)'">
                    <i class="bi bi-search" style="color: var(--lime, #a3e635); font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9);">Cari Lawan Tanding</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted, #64748b); font-size: 0.75rem;"></i>
                </a>

                <a href="#" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; text-decoration: none; transition: border-color 0.2s, background 0.2s;"
                   onmouseover="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.background='rgba(255,255,255,0.05)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)'">
                    <i class="bi bi-geo-alt" style="color: var(--cyan, #22d3ee); font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9);">Booking Venue</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted, #64748b); font-size: 0.75rem;"></i>
                </a>

                <a href="#" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; text-decoration: none; transition: border-color 0.2s, background 0.2s;"
                   onmouseover="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.background='rgba(255,255,255,0.05)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)'">
                    <i class="bi bi-people" style="color: var(--purple, #a78bfa); font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9);">Kelola Tim</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted, #64748b); font-size: 0.75rem;"></i>
                </a>

                <a href="#" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; text-decoration: none; transition: border-color 0.2s, background 0.2s;"
                   onmouseover="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.background='rgba(255,255,255,0.05)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)'">
                    <i class="bi bi-calculator" style="color: #fb923c; font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9);">Split Biaya</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted, #64748b); font-size: 0.75rem;"></i>
                </a>

                <a href="#" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; text-decoration: none; transition: border-color 0.2s, background 0.2s;"
                   onmouseover="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.background='rgba(255,255,255,0.05)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)'">
                    <i class="bi bi-person-circle" style="color: var(--text-muted, #94a3b8); font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary, #f1f5f9);">Edit Profil</span>
                    <i class="bi bi-chevron-right ms-auto" style="color: var(--text-muted, #64748b); font-size: 0.75rem;"></i>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection