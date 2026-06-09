{{-- resources/views/filament/field-admin/widgets/hero-widget.blade.php --}}

<x-filament-widgets::widget>
<style>
/* ═══ HERO WIDGET ═══ */
.hw-wrap {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 0;
}

/* Hero card — identik dengan .dash-hero di dashboard user */
.hw-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    padding: 1.75rem 2rem 1.5rem;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
}
.hw-card::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
    pointer-events: none;
}
.hw-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none; z-index: 0; opacity: .35;
}
.hw-circle-1 {
    position: absolute; top: -70px; right: -70px;
    width: 240px; height: 240px; border-radius: 50%;
    background: var(--accent-dim); pointer-events: none; z-index: 0; opacity: .5;
}
.hw-circle-2 {
    position: absolute; bottom: -90px; right: 110px;
    width: 170px; height: 170px; border-radius: 50%;
    background: var(--accent-dim); pointer-events: none; z-index: 0; opacity: .3;
}

/* Inner layout */
.hw-inner {
    position: relative; z-index: 1;
    display: flex; align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem; flex-wrap: wrap;
    margin-bottom: 1.5rem;
}
.hw-left { flex: 1; min-width: 200px; }

/* Eyebrow */
.hw-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .67rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .12em;
    color: var(--accent-current);
    background: var(--accent-dim); border: 1px solid var(--accent-border);
    border-radius: 99px; padding: 3px 12px; margin-bottom: 10px;
}
.hw-eyebrow-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--accent-current); flex-shrink: 0;
}

/* Title */
.hw-title {
    font-family: 'Manrope', sans-serif;
    font-size: 1.5rem; font-weight: 800;
    color: var(--txt-primary); line-height: 1.25;
    margin-bottom: 6px; letter-spacing: -.01em;
}
.hw-title-name { color: var(--accent-current); }
.hw-subtitle { font-size: .83rem; color: var(--txt-muted); margin-bottom: 1.1rem; }

/* Action buttons — identik dengan .dash-hero-link */
.hw-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.hw-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 600;
    padding: 7px 15px; border-radius: 9px;
    text-decoration: none;
    transition: background .15s, transform .12s;
    white-space: nowrap;
}
.hw-btn:hover { transform: translateY(-1px); text-decoration: none; }
.hw-btn--primary {
    background: var(--accent-dim);
    border: 1px solid var(--accent-border);
    color: var(--accent-current);
}
.hw-btn--secondary {
    background: var(--surface-3);
    border: 1px solid var(--border-medium);
    color: var(--txt-secondary);
}

/* Stats grid — menggunakan surface variables seperti .mini-stat di dashboard user */
.hw-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px; flex-shrink: 0; min-width: 260px;
}
.hw-stat {
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    border-radius: 12px; padding: 12px 14px;
    display: flex; flex-direction: column; gap: 4px;
    transition: border-color .2s, transform .15s;
}
.hw-stat:hover { border-color: var(--accent-border); transform: translateY(-1px); }
.hw-stat--green  { background: rgba(16,185,129,.08); border-color: rgba(16,185,129,.18); }
.hw-stat--yellow { background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.18); }
.hw-stat--blue   { background: rgba(96,165,250,.08); border-color: rgba(96,165,250,.18); }
.hw-stat--red    { background: rgba(239,68,68,.08);  border-color: rgba(239,68,68,.16); }
.hw-stat--orange { background: rgba(251,146,60,.08); border-color: rgba(251,146,60,.16); }
.hw-stat-icon { color: var(--accent-current); line-height: 1; }
.hw-stat--green  .hw-stat-icon { color: #059669; }
.hw-stat--yellow .hw-stat-icon { color: #d97706; }
.hw-stat--blue   .hw-stat-icon { color: #3b82f6; }
.hw-stat--red    .hw-stat-icon { color: #dc2626; }
.hw-stat--orange .hw-stat-icon { color: #ea580c; }
.hw-stat-val {
    font-family: 'Manrope', sans-serif;
    font-size: 1.5rem; font-weight: 800;
    color: var(--txt-primary); line-height: 1.1;
}
.hw-stat-label { font-size: .68rem; color: var(--txt-muted); font-weight: 500; }

/* Progress */
.hw-progress { position: relative; z-index: 1; }
.hw-progress-labels {
    display: flex; justify-content: space-between;
    font-size: .7rem; color: var(--txt-muted); margin-bottom: 6px;
}
.hw-progress-track {
    height: 6px; background: var(--surface-4);
    border-radius: 99px; overflow: hidden;
}
.hw-progress-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }
.hw-fill-high { background: linear-gradient(90deg,#10b981,#34d399); }
.hw-fill-mid  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.hw-fill-low  { background: linear-gradient(90deg,#60a5fa,#93c5fd); }

/* Tip box — mengikuti --surface variables */
.hw-tip {
    display: flex; align-items: flex-start; gap: 9px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 12px; padding: 11px 15px;
    font-size: .78rem; color: var(--txt-secondary); line-height: 1.55;
}
.hw-tip svg { flex-shrink: 0; margin-top: 1px; color: var(--accent-current); }
.hw-tip strong { color: var(--txt-primary); }
.hw-tip em { color: var(--accent-current); font-style: normal; font-weight: 600; }

/* Responsive */
@media(max-width:900px){
    .hw-card { padding: 1.35rem; }
    .hw-inner { flex-direction: column; gap: 1.25rem; }
    .hw-stats { width: 100%; grid-template-columns: repeat(4,1fr); min-width: unset; }
}
@media(max-width:640px){
    .hw-stats { grid-template-columns: repeat(2,1fr); }
    .hw-title { font-size: 1.2rem; }
    .hw-stat-val { font-size: 1.2rem; }
    .hw-card { border-radius: 16px; padding: 1.25rem; }
}
</style>

<div class="hw-wrap">

    {{-- ── Hero Card ── --}}
    <div class="hw-card">
        <div class="hw-grid"></div>
        <div class="hw-circle-1"></div>
        <div class="hw-circle-2"></div>

        <div class="hw-inner">

            {{-- Kiri: teks --}}
            <div class="hw-left">
                <div class="hw-eyebrow">
                    <span class="hw-eyebrow-dot"></span>
                    Panel Manajemen Lapangan
                </div>
                <h2 class="hw-title">
                    Selamat datang, <span class="hw-title-name">{{ $userName }}</span>!
                </h2>
                <p class="hw-subtitle">Pantau dan kelola lapangan serta jadwal Anda hari ini.</p>

                <div class="hw-actions">
                    <a href="{{ filament()->getUrl() }}/fields" class="hw-btn hw-btn--primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                        </svg>
                        Kelola Lapangan
                    </a>
                    <a href="{{ filament()->getUrl() }}/venue-schedules" class="hw-btn hw-btn--secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Lihat Jadwal
                    </a>
                </div>
            </div>

            {{-- Kanan: stats --}}
            <div class="hw-stats">

                <div class="hw-stat">
                    <div class="hw-stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <div class="hw-stat-val">{{ $totalFields }}</div>
                    <div class="hw-stat-label">Total Lapangan</div>
                </div>

                <div class="hw-stat hw-stat--green">
                    <div class="hw-stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div class="hw-stat-val">{{ $activeFields }}</div>
                    <div class="hw-stat-label">Lapangan Aktif</div>
                </div>

                <div class="hw-stat hw-stat--yellow">
                    <div class="hw-stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="hw-stat-val">{{ $todaySchedules }}</div>
                    <div class="hw-stat-label">Slot Hari Ini</div>
                </div>

                @php
                    $occClass  = $occupancyRate >= 70 ? 'hw-stat--red' : ($occupancyRate >= 40 ? 'hw-stat--orange' : 'hw-stat--blue');
                    $fillClass = $occupancyRate >= 70 ? 'hw-fill-high' : ($occupancyRate >= 40 ? 'hw-fill-mid' : 'hw-fill-low');
                @endphp

                <div class="hw-stat {{ $occClass }}">
                    <div class="hw-stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <div class="hw-stat-val">{{ $occupancyRate }}%</div>
                    <div class="hw-stat-label">Occupancy Rate</div>
                </div>

            </div>
        </div>

        {{-- Progress bar --}}
        <div class="hw-progress">
            <div class="hw-progress-labels">
                <span>Keterisian hari ini: {{ $bookedSchedules }} dari {{ $todaySchedules }} slot terisi</span>
                <span>{{ $occupancyRate }}%</span>
            </div>
            <div class="hw-progress-track">
                <div class="hw-progress-fill {{ $fillClass }}" style="width:{{ $occupancyRate }}%"></div>
            </div>
        </div>
    </div>

    {{-- Tip box --}}
    <div class="hw-tip">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>
            <strong>Panduan:</strong>
            Toggle <em>Buka/Tutup Sementara</em> menutup slot tanpa mengubah status permanen.
            Gunakan tombol <em>Nonaktifkan</em> untuk menonaktifkan lapangan secara permanen.
        </span>
    </div>

</div>
</x-filament-widgets::widget>