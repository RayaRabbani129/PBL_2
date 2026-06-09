{{-- resources/views/filament/field-admin/widgets/schedule-availability-widget.blade.php --}}

<x-filament-widgets::widget>
<style>
/* ═══ SCHEDULE AVAILABILITY WIDGET ═══ */
.saw-wrap {
    background: var(--surface-1);
    border: 1px solid var(--border-subtle);
    border-radius: 18px;
    padding: 1.25rem 1.35rem;
    box-shadow: var(--card-shadow);
    transition: border-color .2s;
}
.saw-wrap:hover { border-color: var(--border-medium); }

/* Header */
.saw-header {
    display: flex; align-items: center;
    justify-content: space-between; gap: 10px;
    flex-wrap: wrap; margin-bottom: 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--border-subtle);
}
.saw-title {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: .9rem; font-weight: 700;
    color: var(--txt-primary); margin: 0;
}
.saw-title svg { color: var(--accent-current); }

/* Filter tabs */
.saw-filters { display: flex; gap: 5px; flex-wrap: wrap; }
.saw-filter-btn {
    font-size: .72rem; font-weight: 600;
    padding: 5px 14px; border-radius: 20px;
    border: 1px solid var(--border-subtle);
    background: transparent; cursor: pointer;
    color: var(--txt-muted);
    font-family: 'Inter', sans-serif;
    transition: all .15s; line-height: 1;
}
.saw-filter-btn:hover {
    border-color: var(--accent-border);
    color: var(--accent-current);
}
.saw-filter-btn.active {
    background: var(--accent-current);
    border-color: var(--accent-current);
    color: #fff;
}
html.dark .saw-filter-btn.active { color: #fff; }

/* Progress bar */
.saw-progress { margin-bottom: 1rem; }
.saw-progress-labels {
    display: flex; justify-content: space-between;
    font-size: .7rem; color: var(--txt-muted); margin-bottom: 5px;
}
.saw-progress-track {
    height: 6px; background: var(--surface-4);
    border-radius: 99px; overflow: hidden;
}
.saw-progress-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }
.saw-fill-high { background: linear-gradient(90deg,#10b981,#34d399); }
.saw-fill-mid  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.saw-fill-low  { background: linear-gradient(90deg,#60a5fa,#93c5fd); }

/* Table */
.saw-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
.saw-table th {
    text-align: left; padding: 8px 10px;
    font-size: .68rem; font-weight: 600;
    color: var(--txt-faint);
    text-transform: uppercase; letter-spacing: .07em;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--surface-2);
    white-space: nowrap;
}
.saw-table th:first-child { border-radius: 8px 0 0 0; }
.saw-table th:last-child  { border-radius: 0 8px 0 0; }
.saw-table td {
    padding: 10px; border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle; color: var(--txt-secondary);
}
.saw-table tr:last-child td { border-bottom: none; }
.saw-table tr:hover td { background: var(--accent-dim); }

/* Field badge */
.saw-field-badge {
    display: inline-flex; align-items: center;
    font-family: 'Manrope', sans-serif;
    font-size: .72rem; font-weight: 700;
    padding: 3px 9px; border-radius: 7px;
    background: var(--accent-dim);
    color: var(--accent-current);
    border: 1px solid var(--accent-border);
}

/* Time display */
.saw-time {
    display: flex; align-items: center; gap: 4px;
    color: var(--txt-primary); font-weight: 600;
    font-family: 'Manrope', sans-serif;
    white-space: nowrap; font-size: .82rem;
}
.saw-time-sep { color: var(--txt-faint); font-size: .7rem; }

/* Status pills */
.saw-status {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .65rem; font-weight: 600;
    padding: 3px 9px; border-radius: 99px;
}
.saw-status::before {
    content: ''; width: 5px; height: 5px;
    background: currentColor; border-radius: 50%;
}
.saw-status--available {
    background: rgba(16,185,129,.10); color: #059669;
    border: 1px solid rgba(16,185,129,.20);
}
.saw-status--booked {
    background: rgba(239,68,68,.10); color: #dc2626;
    border: 1px solid rgba(239,68,68,.18);
}
html.dark .saw-status--available { background: rgba(16,185,129,.15); color: #6ee7b7; border-color: rgba(16,185,129,.28); }
html.dark .saw-status--booked    { background: rgba(239,68,68,.12);  color: #fca5a5; border-color: rgba(239,68,68,.20); }

/* Available text */
.saw-avail-yes { font-size: .72rem; color: var(--accent-current); font-weight: 600; }
.saw-avail-no  { font-size: .72rem; color: var(--txt-faint); }

/* Toggle */
.saw-toggle {
    position: relative; display: inline-block;
    width: 40px; height: 22px;
    cursor: pointer; flex-shrink: 0;
}
.saw-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.saw-toggle-track {
    position: absolute; inset: 0;
    border-radius: 11px; background: var(--surface-4);
    border: 1px solid var(--border-medium);
    transition: background .2s;
}
.saw-toggle input:checked ~ .saw-toggle-track {
    background: var(--accent-current);
    border-color: var(--accent-current);
}
.saw-toggle-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 14px; height: 14px; border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
    transition: left .2s;
}
.saw-toggle input:checked ~ .saw-toggle-thumb { left: 21px; }

/* Empty */
.saw-empty {
    text-align: center; padding: 2.5rem 1rem;
    background: var(--surface-2);
    border: 1px dashed var(--border-medium);
    border-radius: 13px; margin-top: 4px;
    color: var(--txt-muted); font-size: .83rem;
}
.saw-empty svg { margin: 0 auto .5rem; display: block; opacity: .35; color: var(--txt-faint); }
.saw-empty-title {
    font-family: 'Manrope', sans-serif;
    font-size: .875rem; font-weight: 700;
    color: var(--txt-secondary); margin-bottom: 4px;
}
</style>

<div class="saw-wrap">

    {{-- Header --}}
    <div class="saw-header">
        <h2 class="saw-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Jadwal Hari Ini
        </h2>
        <div class="saw-filters">
            <button class="saw-filter-btn {{ $filter === 'all'       ? 'active' : '' }}" wire:click="setFilter('all')">Semua</button>
            <button class="saw-filter-btn {{ $filter === 'available' ? 'active' : '' }}" wire:click="setFilter('available')">Tersedia</button>
            <button class="saw-filter-btn {{ $filter === 'booked'    ? 'active' : '' }}" wire:click="setFilter('booked')">Terisi</button>
        </div>
    </div>

    {{-- Progress Bar --}}
    @php
        $fillClass = $occupancyRate >= 70 ? 'saw-fill-high' : ($occupancyRate >= 40 ? 'saw-fill-mid' : 'saw-fill-low');
    @endphp
    <div class="saw-progress">
        <div class="saw-progress-labels">
            <span>Tingkat keterisian hari ini</span>
            <span>{{ $todayBooked }} dari {{ $todayTotal }} slot terisi ({{ $occupancyRate }}%)</span>
        </div>
        <div class="saw-progress-track">
            <div class="saw-progress-fill {{ $fillClass }}" style="width:{{ $occupancyRate }}%"></div>
        </div>
    </div>

    {{-- Table --}}
    @if($schedules->isEmpty())
    <div class="saw-empty">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <div class="saw-empty-title">Tidak ada jadwal</div>
        <div>Belum ada jadwal untuk hari ini.</div>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="saw-table">
            <thead>
                <tr>
                    <th>Lapangan</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Ketersediaan</th>
                    <th style="text-align:center">Buka/Tutup</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                <tr>
                    <td>
                        <span class="saw-field-badge">{{ $schedule->field?->name ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="saw-time">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            <span class="saw-time-sep">–</span>
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </span>
                    </td>
                    <td>
                        @if($schedule->is_available)
                            <span class="saw-status saw-status--available">Tersedia</span>
                        @else
                            <span class="saw-status saw-status--booked">Terisi</span>
                        @endif
                    </td>
                    <td>
                        @if($schedule->is_available)
                            <span class="saw-avail-yes">✓ Bisa dipesan</span>
                        @else
                            <span class="saw-avail-no">— Tidak tersedia</span>
                        @endif
                    </td>
                    <td style="text-align:center">
                        <label class="saw-toggle" title="{{ $schedule->is_available ? 'Tutup slot ini' : 'Buka slot ini' }}">
                            <input type="checkbox"
                                {{ $schedule->is_available ? 'checked' : '' }}
                                wire:click="toggleAvailable({{ $schedule->id }})">
                            <span class="saw-toggle-track"></span>
                            <span class="saw-toggle-thumb"></span>
                        </label>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
</x-filament-widgets::widget>