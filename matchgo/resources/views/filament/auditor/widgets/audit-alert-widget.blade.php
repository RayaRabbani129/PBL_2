{{-- resources/views/filament/auditor/widgets/audit-alert-widget.blade.php --}}

<x-filament-widgets::widget>
<style>
/* ═══ AUDIT ALERT WIDGET ═══ */
.aaw-wrap {
    --surface-1:      var(--mg-surface-1);
    --surface-2:      var(--mg-surface-2);
    --surface-3:      var(--mg-surface-3);
    --border-subtle:  var(--mg-border-subtle);
    --border-medium:  var(--mg-border-medium);
    --accent-dim:     var(--mg-accent-dim);
    --accent-border:  rgba(163,177,75,0.22);
    --accent-current: var(--mg-accent-current);
    --txt-primary:    var(--mg-txt-primary);
    --txt-secondary:  var(--mg-txt-secondary);
    --txt-muted:      var(--mg-txt-muted);
    --card-radius:    14px;

    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* ── Panel dasar ── */
.aaw-panel {
    background: var(--surface-1);
    border: 1px solid var(--border-subtle);
    border-radius: var(--card-radius);
    overflow: hidden;
}

/* ── Panel header ── */
.aaw-panel-header {
    display: flex; align-items: center; gap: 8px;
    padding: .85rem 1.1rem;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--surface-2);
}
.aaw-panel-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.aaw-panel-icon--red    { background: rgba(239,68,68,.1);  color: #dc2626; }
.aaw-panel-icon--yellow { background: rgba(245,158,11,.1); color: #d97706; }
.aaw-panel-title {
    font-family: 'Manrope', sans-serif;
    font-size: .82rem; font-weight: 700;
    color: var(--txt-primary);
}
.aaw-panel-badge {
    margin-left: auto;
    font-size: .66rem; font-weight: 700;
    padding: 2px 9px; border-radius: 99px;
}
.aaw-panel-badge--red    { background: rgba(239,68,68,.12);  color: #dc2626; }
.aaw-panel-badge--yellow { background: rgba(245,158,11,.12); color: #d97706; }
.aaw-panel-badge--gray   { background: rgba(107,114,128,.1); color: #6b7280; }

/* ── Panel body ── */
.aaw-panel-body { padding: .75rem 1.1rem; }

/* ── Flagged booking list ── */
.aaw-flag-list { display: flex; flex-direction: column; gap: 8px; }
.aaw-flag-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 11px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 10px;
    transition: border-color .15s, background .15s;
}
.aaw-flag-item:hover { border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.04); }
.aaw-flag-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(239,68,68,.12);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Manrope', sans-serif;
    font-size: .75rem; font-weight: 800;
    color: #dc2626; flex-shrink: 0;
}
.aaw-flag-info { flex: 1; min-width: 0; }
.aaw-flag-name {
    font-size: .8rem; font-weight: 600;
    color: var(--txt-primary);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.aaw-flag-field { font-size: .7rem; color: var(--txt-muted); }
.aaw-flag-action {
    display: inline-flex; align-items: center;
    font-size: .68rem; font-weight: 600;
    padding: 4px 10px; border-radius: 7px;
    text-decoration: none; flex-shrink: 0;
    background: rgba(239,68,68,.1); color: #dc2626;
    border: 1px solid rgba(239,68,68,.2);
    transition: background .15s, transform .12s;
}
.aaw-flag-action:hover { background: rgba(239,68,68,.18); transform: translateY(-1px); text-decoration: none; }

/* Empty state */
.aaw-empty {
    padding: 1.5rem; text-align: center;
    color: var(--txt-muted); font-size: .8rem;
}
.aaw-empty-icon { font-size: 1.5rem; margin-bottom: 6px; }

/* ── Alert metric grid ── */
.aaw-metrics { display: flex; flex-direction: column; gap: 10px; }
.aaw-metric {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid transparent;
    transition: transform .15s, border-color .15s;
}
.aaw-metric:hover { transform: translateX(3px); }
.aaw-metric--warn    { background: rgba(245,158,11,.07);  border-color: rgba(245,158,11,.18); }
.aaw-metric--danger  { background: rgba(239,68,68,.07);   border-color: rgba(239,68,68,.16); }
.aaw-metric--success { background: rgba(16,185,129,.07);  border-color: rgba(16,185,129,.15); }
.aaw-metric--info    { background: rgba(96,165,250,.07);  border-color: rgba(96,165,250,.15); }

.aaw-metric-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.aaw-metric--warn    .aaw-metric-icon { background: rgba(245,158,11,.15); color: #d97706; }
.aaw-metric--danger  .aaw-metric-icon { background: rgba(239,68,68,.15);  color: #dc2626; }
.aaw-metric--success .aaw-metric-icon { background: rgba(16,185,129,.15); color: #059669; }
.aaw-metric--info    .aaw-metric-icon { background: rgba(96,165,250,.15); color: #3b82f6; }

.aaw-metric-body { flex: 1; }
.aaw-metric-label { font-size: .75rem; color: var(--txt-muted); font-weight: 500; }
.aaw-metric-val {
    font-family: 'Manrope', sans-serif;
    font-size: 1.15rem; font-weight: 800;
    color: var(--txt-primary); line-height: 1.2;
}
.aaw-metric-note { font-size: .68rem; color: var(--txt-muted); }

.aaw-metric-status {
    font-size: .67rem; font-weight: 700;
    padding: 2px 9px; border-radius: 99px; flex-shrink: 0;
}
.aaw-status--alert { background: rgba(239,68,68,.12);  color: #dc2626; }
.aaw-status--warn  { background: rgba(245,158,11,.12); color: #d97706; }
.aaw-status--ok    { background: rgba(16,185,129,.12); color: #059669; }

/* Responsive */
@media(max-width:768px){
    .aaw-wrap { grid-template-columns: 1fr; }
}
</style>

<div class="aaw-wrap">

    {{-- ── Panel 1: Pending Audits ── --}}
    <div class="aaw-panel">
        <div class="aaw-panel-header">
            <div class="aaw-panel-icon aaw-panel-icon--red">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                    <line x1="4" y1="22" x2="4" y2="15"/>
                </svg>
            </div>
            <span class="aaw-panel-title">Match Menunggu Audit</span>
            @if($pendingAudit->count() > 0)
                <span class="aaw-panel-badge aaw-panel-badge--red">{{ $pendingAudit->count() }} pending</span>
            @else
                <span class="aaw-panel-badge aaw-panel-badge--gray">Selesai</span>
            @endif
        </div>

        <div class="aaw-panel-body">
            @if($pendingAudit->isEmpty())
                <div class="aaw-empty">
                    <div class="aaw-empty-icon">✅</div>
                    <div>Tidak ada match yang menunggu audit saat ini.</div>
                </div>
            @else
                <div class="aaw-flag-list">
                    @foreach($pendingAudit as $audit)
                        <div class="aaw-flag-item">
                            <div class="aaw-flag-avatar">
                                {{ strtoupper(substr($audit->match->homeTeam?->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="aaw-flag-info">
                                <div class="aaw-flag-name">{{ $audit->match->homeTeam?->name ?? 'Unknown' }} vs {{ $audit->match->awayTeam?->name ?? 'Unknown' }}</div>
                                <div class="aaw-flag-field">M-{{ $audit->match->match_code ?? '-' }} · {{ $audit->match->match_datetime?->format('d M Y') }}</div>
                            </div>
                            <a href="{{ filament()->getUrl() }}/auditor/match-audits/{{ $audit->id }}/edit" class="aaw-flag-action">
                                Audit
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Panel 2: Alert Metrics ── --}}
    <div class="aaw-panel">
        <div class="aaw-panel-header">
            <div class="aaw-panel-icon aaw-panel-icon--yellow">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <span class="aaw-panel-title">Indikator Match</span>
            @php
                $hasAlert = $stalePending > 0 || $highRiskAlert;
            @endphp
            @if($hasAlert)
                <span class="aaw-panel-badge aaw-panel-badge--yellow">Perlu Perhatian</span>
            @else
                <span class="aaw-panel-badge aaw-panel-badge--gray">Normal</span>
            @endif
        </div>

        <div class="aaw-panel-body">
            <div class="aaw-metrics">

                {{-- Verification stale --}}
                <div class="aaw-metric {{ $stalePending > 0 ? 'aaw-metric--warn' : 'aaw-metric--success' }}">
                    <div class="aaw-metric-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="aaw-metric-body">
                        <div class="aaw-metric-label">Verifikasi Pending > 1 Jam</div>
                        <div class="aaw-metric-val">{{ $stalePending }}</div>
                        <div class="aaw-metric-note">Perlu pengecekan verifikasi skor</div>
                    </div>
                    <span class="aaw-metric-status {{ $stalePending > 0 ? 'aaw-status--warn' : 'aaw-status--ok' }}">
                        {{ $stalePending > 0 ? 'Tindak' : 'OK' }}
                    </span>
                </div>

                {{-- Unverified matches --}}
                <div class="aaw-metric {{ $unverifiedToday > 0 ? 'aaw-metric--warn' : 'aaw-metric--success' }}">
                    <div class="aaw-metric-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="aaw-metric-body">
                        <div class="aaw-metric-label">Match Belum Diverifikasi Hari Ini</div>
                        <div class="aaw-metric-val">{{ $unverifiedToday }}</div>
                        <div class="aaw-metric-note">Gunakan verifikasi skor otomatis</div>
                    </div>
                    <span class="aaw-metric-status {{ $unverifiedToday > 0 ? 'aaw-status--warn' : 'aaw-status--ok' }}">
                        {{ $unverifiedToday > 0 ? 'Review' : 'OK' }}
                    </span>
                </div>

                {{-- High-risk matches --}}
                <div class="aaw-metric {{ $highRiskAlert ? 'aaw-metric--danger' : 'aaw-metric--success' }}">
                    <div class="aaw-metric-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                    <div class="aaw-metric-body">
                        <div class="aaw-metric-label">Match Berisiko (Selisih > 10 Gol)</div>
                        <div class="aaw-metric-val">{{ $highRiskMatches }}</div>
                        <div class="aaw-metric-note">Perlu audit detail untuk keakuratan</div>
                    </div>
                    <span class="aaw-metric-status {{ $highRiskAlert ? 'aaw-status--alert' : 'aaw-status--ok' }}">
                        {{ $highRiskAlert ? 'Alert!' : 'OK' }}
                    </span>
                </div>

                {{-- Low sportsmanship --}}
                <div class="aaw-metric {{ $lowSportMatches > 0 ? 'aaw-metric--warn' : 'aaw-metric--success' }}">
                    <div class="aaw-metric-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="1"/>
                            <path d="M12 1v6m4.22-4.22l-4.24 4.24M19 5v6m1.22 4.22l-4.24-4.24M23 12h-6m4.22 4.22l-4.24-4.24M12 23v-6m4.22 4.22l-4.24-4.24M5 19h6m1.22-4.22l-4.24 4.24"/>
                        </svg>
                    </div>
                    <div class="aaw-metric-body">
                        <div class="aaw-metric-label">Match Sportivitas Rendah (< 5)</div>
                        <div class="aaw-metric-val">{{ $lowSportMatches }}</div>
                        <div class="aaw-metric-note">Hari ini — perlu monitoring lebih</div>
                    </div>
                    <span class="aaw-metric-status {{ $lowSportMatches > 0 ? 'aaw-status--warn' : 'aaw-status--ok' }}">
                        {{ $lowSportMatches > 0 ? 'Review' : 'OK' }}
                    </span>
                </div>

            </div>
        </div>
    </div>

</div>
</x-filament-widgets::widget>