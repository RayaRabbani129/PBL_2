@extends('user.layouts.app')

@section('title', 'Dashboard — MATCHGO')

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="mb-1">Halo, {{ Auth::user()->name }}! 👋</h1>
        <p>Kelola tim, cari lawan, dan atur pertandingan futsalmu di sini.</p>
    </div>
    <a href="{{ '#' ?? '#' }}" class="btn btn-lime btn-sm px-3">
        <i class="bi bi-search me-2"></i>Cari Lawan
    </a>
</div>

{{-- ── Statistik Tim ─────────────────────────────────────────── --}}
@if($myTeam ?? null)
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size:.75rem;font-weight:600;color:var(--text-muted,#94a3b8);text-transform:uppercase;letter-spacing:.05em;">Total Match</span>
                <div style="width:34px;height:34px;background:rgba(163,230,53,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--lime,#a3e635);">
                    <i class="bi bi-trophy"></i>
                </div>
            </div>
            <div style="font-size:2rem;font-weight:700;color:var(--text-primary,#f1f5f9);line-height:1;">{{ $myTeam->total_matches ?? 0 }}</div>
            <div style="font-size:.78rem;color:var(--text-muted,#64748b);margin-top:.4rem;">Pertandingan dimainkan</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size:.75rem;font-weight:600;color:var(--text-muted,#94a3b8);text-transform:uppercase;letter-spacing:.05em;">Kemenangan</span>
                <div style="width:34px;height:34px;background:rgba(163,230,53,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--lime,#a3e635);">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div style="font-size:2rem;font-weight:700;color:var(--lime,#a3e635);line-height:1;">{{ $myTeam->wins ?? 0 }}</div>
            <div style="font-size:.78rem;color:var(--text-muted,#64748b);margin-top:.4rem;">Total menang</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size:.75rem;font-weight:600;color:var(--text-muted,#94a3b8);text-transform:uppercase;letter-spacing:.05em;">Kekalahan</span>
                <div style="width:34px;height:34px;background:rgba(239,68,68,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#f87171;">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
            <div style="font-size:2rem;font-weight:700;color:#f87171;line-height:1;">{{ $myTeam->losses ?? 0 }}</div>
            <div style="font-size:.78rem;color:var(--text-muted,#64748b);margin-top:.4rem;">Total kalah</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="font-size:.75rem;font-weight:600;color:var(--text-muted,#94a3b8);text-transform:uppercase;letter-spacing:.05em;">Gol Dicetak</span>
                <div style="width:34px;height:34px;background:rgba(34,211,238,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--cyan,#22d3ee);">
                    <i class="bi bi-dribbble"></i>
                </div>
            </div>
            <div style="font-size:2rem;font-weight:700;color:var(--cyan,#22d3ee);line-height:1;">{{ $myTeam->total_goals ?? 0 }}</div>
            @php
                $total   = $myTeam->total_matches ?? 0;
                $winRate = $total > 0 ? round(($myTeam->wins ?? 0) / $total * 100) : 0;
            @endphp
            <div style="margin-top:.6rem;">
                <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--text-muted,#64748b);margin-bottom:4px;">
                    <span>Win Rate</span><span style="color:var(--cyan,#22d3ee);">{{ $winRate }}%</span>
                </div>
                <div style="height:4px;background:rgba(255,255,255,.08);border-radius:4px;overflow:hidden;">
                    <div style="height:100%;width:{{ $winRate }}%;background:var(--cyan,#22d3ee);border-radius:4px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Row Utama ─────────────────────────────────────────────── --}}
<div class="row g-4">

    {{-- Profil Tim --}}
    <div class="col-lg-4">
        <div class="card-matchgo h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin:0;">
                    <i class="bi bi-shield-fill me-2" style="color:#a78bfa;"></i>Profil Tim
                </h2>
                <a href="#" style="font-size:.8rem;color:var(--lime,#a3e635);text-decoration:none;">Edit <i class="bi bi-pencil"></i></a>
            </div>

            @if($myTeam ?? null)
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;background:rgba(163,230,53,.1);border:2px solid rgba(163,230,53,.2);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto .75rem;">⚽</div>
                    <p style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin:0;">{{ $myTeam->name }}</p>
                    <p style="font-size:.8rem;color:var(--text-muted,#94a3b8);margin:0;"><i class="bi bi-geo-alt me-1"></i>{{ $myTeam->location ?? 'Lokasi belum diisi' }}</p>
                </div>

                <div class="text-center mb-4">
                    @php
                        $levelColor = match($myTeam->level ?? '') {
                            'competitive' => ['bg' => 'rgba(239,68,68,.12)',   'color' => '#f87171',                  'label' => 'Competitive'],
                            'semi_pro'    => ['bg' => 'rgba(251,191,36,.12)',   'color' => '#fcd34d',                  'label' => 'Semi-Pro'],
                            default       => ['bg' => 'rgba(163,230,53,.12)',   'color' => 'var(--lime,#a3e635)',       'label' => 'Casual'],
                        };
                    @endphp
                    <span style="display:inline-block;background:{{ $levelColor['bg'] }};color:{{ $levelColor['color'] }};font-size:.78rem;font-weight:700;padding:4px 14px;border-radius:20px;">
                        <i class="bi bi-star-fill me-1" style="font-size:.65rem;"></i>{{ $levelColor['label'] }}
                    </span>
                </div>

                <div style="display:flex;flex-direction:column;gap:.5rem;">
                    <div style="display:flex;justify-content:space-between;font-size:.85rem;padding:.5rem .75rem;background:rgba(255,255,255,.02);border-radius:8px;">
                        <span style="color:var(--text-muted,#94a3b8);">Anggota</span>
                        <span style="color:var(--text-primary,#f1f5f9);font-weight:600;">{{ $myTeam->members_count ?? 0 }} orang</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.85rem;padding:.5rem .75rem;background:rgba(255,255,255,.02);border-radius:8px;">
                        <span style="color:var(--text-muted,#94a3b8);">Role Saya</span>
                        <span style="color:var(--lime,#a3e635);font-weight:600;">{{ ucfirst($myTeam->pivot->role ?? 'member') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center" style="padding:2rem 1rem;">
                    <div style="font-size:2.5rem;opacity:.4;margin-bottom:.75rem;">⚽</div>
                    <p style="font-size:.875rem;color:var(--text-muted,#64748b);margin-bottom:1rem;">Kamu belum memiliki tim. Buat tim untuk mulai bermain!</p>
                    <a href="{{ '#' ?? '#' }}" class="btn btn-lime btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Buat Tim Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Pertandingan Mendatang + Jadwal + Cost Split --}}
    <div class="col-lg-8">
        <div class="row g-4">

            {{-- Pertandingan Mendatang --}}
            <div class="col-12">
                <div class="card-matchgo">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin:0;">
                            <i class="bi bi-calendar-event me-2" style="color:var(--lime,#a3e635);"></i>Pertandingan Mendatang
                        </h2>
                        <a href="#" style="font-size:.8rem;color:var(--lime,#a3e635);text-decoration:none;">Semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.65rem;">
                        @forelse($upcomingMatches ?? [] as $match)
                            <div style="display:flex;align-items:center;gap:1rem;padding:.85rem 1rem;background:rgba(163,230,53,.04);border:1px solid rgba(163,230,53,.1);border-radius:10px;">
                                <div style="width:42px;height:42px;background:rgba(163,230,53,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--lime,#a3e635);flex-shrink:0;">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-size:.875rem;font-weight:600;color:var(--text-primary,#f1f5f9);margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        vs {{ $match->opponent_team_name ?? $match->name }}
                                    </p>
                                    <div style="display:flex;gap:1rem;font-size:.75rem;color:var(--text-muted,#94a3b8);margin-top:2px;flex-wrap:wrap;">
                                        <span><i class="bi bi-clock me-1"></i>{{ $match->scheduled_at->format('d M Y, H:i') }}</span>
                                        @if($match->venue_name ?? null)
                                            <span><i class="bi bi-geo-alt me-1"></i>{{ $match->venue_name }}</span>
                                        @endif
                                        @if($match->cost_per_player ?? null)
                                            <span><i class="bi bi-wallet2 me-1"></i>Rp {{ number_format($match->cost_per_player, 0, ',', '.') }}/orang</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="#" style="font-size:.75rem;font-weight:600;background:rgba(163,230,53,.12);color:var(--lime,#a3e635);padding:4px 12px;border-radius:20px;text-decoration:none;white-space:nowrap;flex-shrink:0;">Detail</a>
                            </div>
                        @empty
                            <div style="text-align:center;padding:1.5rem 1rem;">
                                <div style="font-size:2rem;opacity:.35;margin-bottom:.5rem;">🏟️</div>
                                <p style="font-size:.875rem;color:var(--text-muted,#64748b);margin-bottom:.75rem;">Tidak ada pertandingan mendatang</p>
                                <a href="#" class="btn btn-outline-lime btn-sm"><i class="bi bi-search me-1"></i>Cari Lawan Sekarang</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Jadwal Bermain --}}
            <div class="col-md-6">
                <div class="card-matchgo h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin:0;">
                            <i class="bi bi-clock me-2" style="color:var(--cyan,#22d3ee);"></i>Jadwal Saya
                        </h2>
                        <a href="#" style="font-size:.8rem;color:var(--lime,#a3e635);text-decoration:none;"><i class="bi bi-plus"></i> Tambah</a>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        @forelse($mySchedules ?? [] as $schedule)
                            <div style="display:flex;align-items:center;gap:.75rem;padding:.6rem .85rem;background:rgba(34,211,238,.04);border:1px solid rgba(34,211,238,.08);border-radius:8px;">
                                <i class="bi bi-calendar3" style="color:var(--cyan,#22d3ee);flex-shrink:0;"></i>
                                <div style="flex:1;">
                                    <p style="font-size:.82rem;font-weight:600;color:var(--text-primary,#f1f5f9);margin:0;">{{ $schedule->day_name ?? $schedule->day }}</p>
                                    <p style="font-size:.75rem;color:var(--text-muted,#94a3b8);margin:0;">{{ $schedule->time_start }} – {{ $schedule->time_end }}</p>
                                </div>
                                <span style="font-size:.7rem;background:rgba(34,211,238,.1);color:var(--cyan,#22d3ee);padding:2px 8px;border-radius:12px;">Tersedia</span>
                            </div>
                        @empty
                            <div style="text-align:center;padding:1.25rem .5rem;">
                                <p style="font-size:.82rem;color:var(--text-muted,#64748b);margin-bottom:.75rem;">Belum ada jadwal tersedia</p>
                                <a href="#" class="btn btn-outline-lime btn-sm" style="font-size:.78rem;"><i class="bi bi-plus me-1"></i>Tambah Jadwal</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Smart Cost Split Preview --}}
            <div class="col-md-6">
                <div class="card-matchgo h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin:0;">
                            <i class="bi bi-calculator me-2" style="color:#fb923c;"></i>Cost Split
                        </h2>
                        <a href="#" style="font-size:.8rem;color:var(--lime,#a3e635);text-decoration:none;">Hitung <i class="bi bi-arrow-right"></i></a>
                    </div>
                    @if($latestCostSplit ?? null)
                        <div style="display:flex;flex-direction:column;gap:.45rem;">
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.45rem .7rem;background:rgba(255,255,255,.02);border-radius:7px;">
                                <span style="color:var(--text-muted,#94a3b8);">Sewa Lapangan</span>
                                <span style="color:var(--text-primary,#f1f5f9);font-weight:600;">Rp {{ number_format($latestCostSplit->venue_cost ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.45rem .7rem;background:rgba(255,255,255,.02);border-radius:7px;">
                                <span style="color:var(--text-muted,#94a3b8);">Jumlah Pemain</span>
                                <span style="color:var(--text-primary,#f1f5f9);font-weight:600;">{{ $latestCostSplit->player_count ?? 0 }} orang</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.55rem .7rem;background:rgba(251,146,60,.08);border:1px solid rgba(251,146,60,.15);border-radius:7px;margin-top:.25rem;">
                                <span style="color:#fb923c;font-weight:600;">Per Orang</span>
                                <span style="color:#fb923c;font-weight:700;">Rp {{ number_format($latestCostSplit->cost_per_player ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @else
                        <div style="text-align:center;padding:1.25rem .5rem;">
                            <div style="font-size:2rem;opacity:.35;margin-bottom:.5rem;">💰</div>
                            <p style="font-size:.82rem;color:var(--text-muted,#64748b);margin-bottom:.75rem;">Belum ada data pembagian biaya</p>
                            <a href="#" class="btn btn-sm" style="background:rgba(251,146,60,.1);border:1px solid rgba(251,146,60,.2);color:#fb923c;border-radius:8px;font-size:.78rem;padding:.35rem .9rem;">
                                <i class="bi bi-calculator me-1"></i>Hitung Biaya
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Riwayat Pertandingan --}}
    <div class="col-lg-8">
        <div class="card-matchgo">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin:0;">
                    <i class="bi bi-clock-history me-2" style="color:var(--cyan,#22d3ee);"></i>Riwayat Pertandingan
                </h2>
                <a href="#" style="font-size:.8rem;color:var(--lime,#a3e635);text-decoration:none;">Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div style="display:flex;flex-direction:column;gap:.65rem;">
                @forelse($recentMatches ?? [] as $match)
                    @php
                        $isWin  = ($match->result ?? '') === 'win';
                        $isLoss = ($match->result ?? '') === 'loss';
                    @endphp
                    <div style="display:flex;align-items:center;gap:1rem;padding:.85rem 1rem;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.05);border-radius:10px;">
                        <div style="width:42px;height:42px;background:{{ $isWin ? 'rgba(163,230,53,.1)' : ($isLoss ? 'rgba(239,68,68,.1)' : 'rgba(255,255,255,.05)') }};border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                            {{ $isWin ? '🏆' : ($isLoss ? '😔' : '⚽') }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:.875rem;font-weight:600;color:var(--text-primary,#f1f5f9);margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                vs {{ $match->opponent_team_name ?? $match->name }}
                            </p>
                            <div style="display:flex;gap:1rem;font-size:.75rem;color:var(--text-muted,#94a3b8);margin-top:2px;flex-wrap:wrap;">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $match->played_at->format('d M Y') }}</span>
                                @if($match->venue_name ?? null)
                                    <span><i class="bi bi-geo-alt me-1"></i>{{ $match->venue_name }}</span>
                                @endif
                                @if($match->score ?? null)
                                    <span style="font-weight:600;color:{{ $isWin ? 'var(--lime,#a3e635)' : '#f87171' }};">{{ $match->score }}</span>
                                @endif
                            </div>
                        </div>
                        @if(isset($match->result))
                            <span style="font-size:.72rem;font-weight:700;background:{{ $isWin ? 'rgba(163,230,53,.12)' : 'rgba(239,68,68,.12)' }};color:{{ $isWin ? 'var(--lime,#a3e635)' : '#f87171' }};padding:4px 12px;border-radius:20px;flex-shrink:0;">
                                {{ $isWin ? 'Menang' : 'Kalah' }}
                            </span>
                        @endif
                    </div>
                @empty
                    <div style="text-align:center;padding:1.5rem 1rem;">
                        <div style="font-size:2rem;opacity:.35;margin-bottom:.5rem;">⚽</div>
                        <p style="font-size:.875rem;color:var(--text-muted,#64748b);margin:0;">Belum ada riwayat pertandingan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="card-matchgo h-100">
            <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary,#f1f5f9);margin-bottom:1.25rem;">
                <i class="bi bi-lightning-charge me-2" style="color:var(--lime,#a3e635);"></i>Aksi Cepat
            </h2>
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                @php
                    $actions = [
                        ['icon'=>'bi-search',       'color'=>'var(--lime,#a3e635)', 'bg'=>'rgba(163,230,53,.05)', 'border'=>'rgba(163,230,53,.12)', 'label'=>'Cari Lawan Tanding'],
                        ['icon'=>'bi-geo-alt',       'color'=>'var(--cyan,#22d3ee)', 'bg'=>'rgba(34,211,238,.05)', 'border'=>'rgba(34,211,238,.1)',  'label'=>'Rekomendasi Venue'],
                        ['icon'=>'bi-calendar-plus', 'color'=>'#a78bfa',             'bg'=>'rgba(167,139,250,.05)','border'=>'rgba(167,139,250,.1)', 'label'=>'Atur Jadwal Bermain'],
                        ['icon'=>'bi-calculator',    'color'=>'#fb923c',             'bg'=>'rgba(251,146,60,.05)', 'border'=>'rgba(251,146,60,.1)',  'label'=>'Smart Cost Split'],
                        ['icon'=>'bi-people',        'color'=>'var(--text-muted,#94a3b8)', 'bg'=>'rgba(255,255,255,.02)', 'border'=>'rgba(255,255,255,.06)', 'label'=>'Kelola Anggota Tim'],
                        ['icon'=>'bi-person-circle', 'color'=>'var(--text-muted,#94a3b8)', 'bg'=>'rgba(255,255,255,.02)', 'border'=>'rgba(255,255,255,.06)', 'label'=>'Edit Profil Tim'],
                    ];
                @endphp
                @foreach($actions as $a)
                    <a href="#" style="display:flex;align-items:center;gap:.85rem;padding:.8rem 1rem;background:{{ $a['bg'] }};border:1px solid {{ $a['border'] }};border-radius:10px;text-decoration:none;"
                       onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                        <i class="bi {{ $a['icon'] }}" style="color:{{ $a['color'] }};font-size:1rem;width:18px;text-align:center;"></i>
                        <span style="font-size:.855rem;font-weight:600;color:var(--text-primary,#f1f5f9);">{{ $a['label'] }}</span>
                        <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted,#64748b);font-size:.72rem;"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection