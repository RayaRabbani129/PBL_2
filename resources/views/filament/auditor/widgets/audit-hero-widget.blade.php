{{-- resources/views/filament/auditor/widgets/audit-hero-widget.blade.php --}}

<x-filament-widgets::widget>

<style>
.ahw-wrap {
    --surface-1:      var(--mg-surface-1);
    --surface-2:      var(--mg-surface-2);
    --surface-3:      var(--mg-surface-3);
    --surface-4:      var(--mg-surface-3);
    --border-subtle:  var(--mg-border-subtle);
    --border-medium:  var(--mg-border-medium);
    --accent-dim:     var(--mg-accent-dim);
    --accent-border:  rgba(163,177,75,0.22);
    --accent-current: var(--mg-accent-current);
    --txt-primary:    var(--mg-txt-primary);
    --txt-secondary:  var(--mg-txt-secondary);
    --txt-muted:      var(--mg-txt-muted);

    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ahw-card{
    position:relative;
    border-radius:20px;
    overflow:hidden;
    padding:1.75rem 2rem 1.5rem;
    background:var(--surface-2);
    border:1px solid var(--border-subtle);
}

.ahw-card::before{
    content:'';
    position:absolute;
    inset:0;
    background:radial-gradient(
        ellipse at top left,
        var(--accent-dim) 0%,
        transparent 65%
    );
    pointer-events:none;
}

.ahw-grid{
    position:absolute;
    inset:0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size:32px 32px;
    pointer-events:none;
    opacity:.35;
}

.ahw-circle-1{
    position:absolute;
    top:-70px;
    right:-70px;
    width:240px;
    height:240px;
    border-radius:50%;
    background:var(--accent-dim);
    opacity:.5;
}

.ahw-circle-2{
    position:absolute;
    bottom:-90px;
    right:110px;
    width:170px;
    height:170px;
    border-radius:50%;
    background:var(--accent-dim);
    opacity:.3;
}

.ahw-inner{
    position:relative;
    z-index:1;
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:1.5rem;
    flex-wrap:wrap;
    margin-bottom:1.5rem;
}

.ahw-left{
    flex:1;
    min-width:200px;
}

.ahw-eyebrow{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:.67rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.12em;
    color:var(--accent-current);
    background:var(--accent-dim);
    border:1px solid var(--accent-border);
    border-radius:99px;
    padding:3px 12px;
    margin-bottom:10px;
}

.ahw-eyebrow-dot{
    width:6px;
    height:6px;
    border-radius:50%;
    background:var(--accent-current);
}

.ahw-title{
    font-family:'Manrope',sans-serif;
    font-size:1.5rem;
    font-weight:800;
    color:var(--txt-primary);
    line-height:1.25;
    margin-bottom:6px;
}

.ahw-title-name{
    color:var(--accent-current);
}

.ahw-subtitle{
    font-size:.83rem;
    color:var(--txt-muted);
    margin-bottom:1.1rem;
}

.ahw-actions{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.ahw-btn{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:.72rem;
    font-weight:600;
    padding:7px 15px;
    border-radius:9px;
    text-decoration:none;
    transition:.15s;
    white-space:nowrap;
}

.ahw-btn:hover{
    transform:translateY(-1px);
}

.ahw-btn--primary{
    background:var(--accent-dim);
    border:1px solid var(--accent-border);
    color:var(--accent-current);
}

.ahw-btn--secondary{
    background:var(--surface-3);
    border:1px solid var(--border-medium);
    color:var(--txt-secondary);
}

.ahw-stats{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:8px;
    min-width:280px;
}

.ahw-stat{
    background:var(--surface-3);
    border:1px solid var(--border-subtle);
    border-radius:12px;
    padding:12px 14px;
}

.ahw-stat--green{
    background:rgba(16,185,129,.08);
    border-color:rgba(16,185,129,.18);
}

.ahw-stat--yellow{
    background:rgba(245,158,11,.08);
    border-color:rgba(245,158,11,.18);
}

.ahw-stat--red{
    background:rgba(239,68,68,.08);
    border-color:rgba(239,68,68,.16);
}

.ahw-stat-val{
    font-size:1.4rem;
    font-weight:800;
    color:var(--txt-primary);
}

.ahw-stat-label{
    font-size:.68rem;
    color:var(--txt-muted);
}

.ahw-progress{
    position:relative;
    z-index:1;
}

.ahw-progress-labels{
    display:flex;
    justify-content:space-between;
    font-size:.7rem;
    color:var(--txt-muted);
    margin-bottom:6px;
}

.ahw-progress-track{
    height:6px;
    background:var(--surface-4);
    border-radius:99px;
    overflow:hidden;
}

.ahw-progress-fill{
    height:100%;
    border-radius:99px;
}

.ahw-fill-high{
    background:linear-gradient(90deg,#10b981,#34d399);
}

.ahw-fill-mid{
    background:linear-gradient(90deg,#f59e0b,#fbbf24);
}

.ahw-fill-low{
    background:linear-gradient(90deg,#60a5fa,#93c5fd);
}

.ahw-tip{
    display:flex;
    align-items:flex-start;
    gap:9px;
    background:var(--surface-2);
    border:1px solid var(--border-subtle);
    border-radius:12px;
    padding:11px 15px;
    font-size:.78rem;
    color:var(--txt-secondary);
}

.ahw-revenue-badge{
    display:inline-flex;
    align-items:center;
    gap:5px;
    font-size:.69rem;
    font-weight:700;
    border-radius:99px;
    padding:2px 9px;
}

.ahw-revenue-badge--up{
    background:rgba(16,185,129,.12);
    color:#059669;
}

.ahw-revenue-badge--down{
    background:rgba(239,68,68,.12);
    color:#dc2626;
}

.ahw-revenue-badge--flat{
    background:rgba(107,114,128,.1);
    color:#6b7280;
}
</style>

@php
    $auditFillClass = $auditRate >= 70
        ? 'ahw-fill-high'
        : ($auditRate >= 40 ? 'ahw-fill-mid' : 'ahw-fill-low');

    $verifyFillClass = $verificationRate >= 70
        ? 'ahw-fill-high'
        : ($verificationRate >= 40 ? 'ahw-fill-mid' : 'ahw-fill-low');

    $growthClass = $matchGrowth > 0
        ? 'ahw-revenue-badge--up'
        : ($matchGrowth < 0
            ? 'ahw-revenue-badge--down'
            : 'ahw-revenue-badge--flat');

    $growthIcon = $matchGrowth > 0
        ? '↑'
        : ($matchGrowth < 0 ? '↓' : '→');

    $growthLabel = ($matchGrowth > 0 ? '+' : '') . $matchGrowth . '% vs bulan lalu';
@endphp

<div class="ahw-wrap">

    <div class="ahw-card">

        <div class="ahw-grid"></div>
        <div class="ahw-circle-1"></div>
        <div class="ahw-circle-2"></div>

        <div class="ahw-inner">

            <div class="ahw-left">

                <div class="ahw-eyebrow">
                    <span class="ahw-eyebrow-dot"></span>
                    Panel Audit Match & Monitoring
                </div>

                <h2 class="ahw-title">
                    Selamat datang,
                    <span class="ahw-title-name">
                        {{ $userName }}
                    </span>
                </h2>

                <p class="ahw-subtitle">
                    Pantau audit match, verifikasi skor,
                    dan performa tim hari ini.
                </p>

                <div class="ahw-actions">

                    {{-- BUTTON AUDIT --}}
                    <a
                        href="{{ filament()->getUrl() . '/auditor/match-audits' }}"
                        class="ahw-btn ahw-btn--primary"
                    >
                        Lihat Audit
                    </a>

                    {{-- BUTTON VERIFIKASI --}}
                    <a
                        href="{{ filament()->getUrl() . '/auditor/match-verifications' }}"
                        class="ahw-btn ahw-btn--secondary"
                    >
                        Verifikasi
                    </a>

                    {{-- BUTTON COMPLETED --}}
                    <a
                        href="{{ filament()->getUrl() . '/auditor/matches?tableFilters[status][value]=completed' }}"
                        class="ahw-btn ahw-btn--secondary"
                    >
                        Completed

                        @if($totalMatchesToday > 0)
                            <span
                                style="
                                    background:rgba(16,185,129,.2);
                                    color:#059669;
                                    border-radius:99px;
                                    padding:1px 7px;
                                    font-size:.65rem;
                                "
                            >
                                {{ $totalMatchesToday }}
                            </span>
                        @endif

                    </a>

                </div>
            </div>

            {{-- STATS --}}
            <div class="ahw-stats">

                <div class="ahw-stat">
                    <div class="ahw-stat-val">
                        {{ $totalMatchesToday }}
                    </div>

                    <div class="ahw-stat-label">
                        Match Hari Ini
                    </div>
                </div>

                <div class="ahw-stat ahw-stat--green">
                    <div class="ahw-stat-val">
                        {{ $auditedToday }}
                    </div>

                    <div class="ahw-stat-label">
                        Audit Selesai
                    </div>
                </div>

                <div class="ahw-stat ahw-stat--yellow">
                    <div class="ahw-stat-val">
                        {{ $pendingAudit }}
                    </div>

                    <div class="ahw-stat-label">
                        Menunggu Audit
                    </div>
                </div>

                <div class="ahw-stat ahw-stat--red">
                    <div class="ahw-stat-val">
                        {{ $pendingVerification }}
                    </div>

                    <div class="ahw-stat-label">
                        Menunggu Verifikasi
                    </div>
                </div>

            </div>
        </div>

        {{-- PROGRESS AUDIT --}}
        <div class="ahw-progress" style="margin-bottom:12px;">

            <div class="ahw-progress-labels">

                <span>
                    Audit completion:
                    {{ $auditedToday }}
                    dari
                    {{ $auditRate }}%
                    selesai

                    <span class="ahw-revenue-badge {{ $growthClass }}">
                        {{ $growthIcon }}
                        {{ $growthLabel }}
                    </span>
                </span>

                <span>
                    {{ $auditRate }}%
                </span>

            </div>

            <div class="ahw-progress-track">
                <div
                    class="ahw-progress-fill {{ $auditFillClass }}"
                    style="width:{{ $auditRate }}%;"
                ></div>
            </div>

        </div>

        {{-- PROGRESS VERIF --}}
        <div class="ahw-progress">

            <div class="ahw-progress-labels">

                <span>
                    Verification:
                    {{ $verifiedToday }}
                    terverifikasi
                    ({{ $verificationRate }}%)
                </span>

                <span>
                    {{ $verificationRate }}%
                </span>

            </div>

            <div class="ahw-progress-track">
                <div
                    class="ahw-progress-fill {{ $verifyFillClass }}"
                    style="width:{{ $verificationRate }}%;"
                ></div>
            </div>

        </div>

    </div>

    {{-- TIP --}}
    <div class="ahw-tip">

        <span>
            <strong>Panduan Audit Match:</strong>
            Pastikan semua match completed telah di-audit
            dengan sportsmanship rating dan game review.
        </span>

    </div>

</div>

</x-filament-widgets::widget>