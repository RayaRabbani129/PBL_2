@extends('user.layouts.app')

@section('title', 'Jadwal Saya — MATCHGO')
@section('page-title', 'Jadwal Saya')

@php
$daysMap = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
@endphp

@push('styles')
<style>
.sched-hero {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    padding: 2rem 2rem 1.75rem;
    margin-bottom: 1.5rem;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
}

.sched-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at top left, var(--accent-dim) 0%, transparent 65%);
    pointer-events: none;
}

.sched-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(var(--border-subtle) 1px, transparent 1px),
        linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    opacity: 0.35;
}

.sched-hero-content {
    position: relative;
    z-index: 1;
}

.sched-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--accent);
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    border-radius: 99px;
    padding: 4px 12px;
    margin-bottom: 14px;
}

.sched-hero h2 {
    font-family: 'Manrope', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1.2;
    margin-bottom: 8px;
}

.sched-hero h2 span { color: var(--accent); }

.sched-hero p {
    font-size: 0.875rem;
    color: var(--txt-muted);
    max-width: 500px;
    margin-bottom: 0;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 1.5rem;
}

@media (max-width: 991px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px)  { .stats-row { grid-template-columns: 1fr 1fr; } }

.mini-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    transition: border-color 0.2s, background 0.3s;
}

.mini-stat:hover { border-color: rgba(163,177,75,0.25); }

.mini-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: var(--accent-dim);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    transition: background 0.3s, color 0.3s;
}

.mini-stat-val {
    font-family: 'Manrope', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--txt-primary);
    line-height: 1;
    transition: color 0.3s;
}

.mini-stat-label {
    font-size: 0.75rem;
    color: var(--txt-muted);
    font-weight: 500;
    margin-top: 3px;
    transition: color 0.3s;
}

.content-row {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 20px;
    align-items: start;
}

@media (max-width: 1199px) { .content-row { grid-template-columns: 1fr; } }

.cal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    gap: 8px;
}

.cal-title {
    font-family: 'Manrope', sans-serif;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--txt-primary);
    white-space: nowrap;
    transition: color 0.3s;
}

.cal-nav-btn {
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--txt-secondary);
    cursor: pointer;
    font-size: 0.75rem;
    transition: background 0.15s, color 0.15s, border-color 0.3s;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    padding: 0 8px;
    white-space: nowrap;
}

.cal-nav-btn:hover {
    background: var(--accent-dim);
    color: var(--accent);
    border-color: rgba(163,177,75,0.3);
}

.cal-dow {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    margin-bottom: 4px;
}

.cal-dow-cell {
    text-align: center;
    font-size: 0.6rem;
    font-weight: 700;
    color: var(--txt-faint);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 4px 0;
}

.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
}

.cal-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--txt-secondary);
    border-radius: 8px;
    cursor: default;
    transition: background 0.15s, color 0.15s;
    position: relative;
}

.cal-day.other-month { color: var(--txt-faint); }

.cal-day.today {
    background: var(--accent);
    color: var(--btn-primary-txt);
    font-weight: 700;
    border-radius: 50%;
}

.cal-day.has-event:not(.today) {
    background: var(--accent-dim);
    color: var(--accent);
    font-weight: 600;
    border-radius: 8px;
}

.cal-day.has-event::after {
    content: '';
    position: absolute;
    bottom: 3px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    background: var(--accent);
    border-radius: 50%;
}

.cal-day.today.has-event::after { background: var(--btn-primary-txt); }

.cal-legend {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--border-subtle);
}

.cal-legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    color: var(--txt-muted);
}

.cal-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    flex-shrink: 0;
}

.sched-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-subtle);
    flex-wrap: wrap;
    gap: 10px;
}

.sched-results-title {
    font-family: 'Manrope', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--txt-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.sched-count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    border-radius: 99px;
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.20);
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--accent);
    padding: 0 7px;
}
.filter-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.filter-tab {
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    padding: 6px 16px;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--txt-muted);
    cursor: pointer;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    font-family: 'Inter', sans-serif;
}

.filter-tab:hover {
    background: var(--accent-dim);
    color: var(--txt-primary);
    border-color: rgba(163,177,75,0.2);
}

.filter-tab.active {
    background: var(--accent-dim);
    color: var(--accent);
    border-color: rgba(163,177,75,0.35);
    font-weight: 600;
}

#scheduleList {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.schedule-item {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 16px;
    padding: 1rem 1.15rem;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: border-color 0.2s, background 0.15s, transform 0.15s;
    position: relative;
    overflow: hidden;
}

.schedule-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    border-radius: 3px 0 0 3px;
    background: var(--accent);
    opacity: 0;
    transition: opacity 0.2s;
}

.schedule-item:hover {
    border-color: rgba(163,177,75,0.28);
    background: var(--surface-3);
    transform: translateY(-1px);
}

.schedule-item:hover::before { opacity: 1; }

.schedule-time-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,0.18);
    border-radius: 10px;
    padding: 8px 12px;
    min-width: 62px;
    flex-shrink: 0;
    gap: 2px;
}

.time-val {
    font-family: 'Manrope', sans-serif;
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--accent);
    line-height: 1;
}

.time-sep {
    font-size: 0.55rem;
    color: var(--txt-faint);
    font-weight: 500;
}

.time-end {
    font-size: 0.7rem;
    color: var(--txt-muted);
    font-weight: 500;
}

.schedule-info {
    flex: 1;
    min-width: 0;
}

.schedule-title {
    font-family: 'Manrope', sans-serif;
    font-size: 0.925rem;
    font-weight: 700;
    color: var(--txt-primary);
    margin-bottom: 4px;
    transition: color 0.3s;
}

.schedule-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    font-size: 0.775rem;
    color: var(--txt-muted);
}

/* Status Pills */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: 99px;
}

.status-pill::before {
    content: '';
    width: 5px;
    height: 5px;
    background: currentColor;
    border-radius: 50%;
}

.status-pill.available {
    background: var(--alert-success-bg);
    color: var(--alert-success-txt);
    border: 1px solid var(--alert-success-bdr);
}

.status-pill.pending {
    background: var(--alert-warning-bg);
    color: var(--alert-warning-txt);
    border: 1px solid var(--alert-warning-bdr);
}

/* Action buttons */
.schedule-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.schedule-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: var(--surface-3);
    border: 1px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--txt-muted);
    cursor: pointer;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    font-size: 0.825rem;
    text-decoration: none;
}

.schedule-action-btn:hover {
    background: var(--accent-dim);
    color: var(--accent);
    border-color: rgba(163,177,75,0.3);
    text-decoration: none;
}

.schedule-action-btn.delete:hover {
    background: var(--alert-danger-bg);
    color: var(--alert-danger-txt);
    border-color: var(--alert-danger-bdr);
}

/* Empty state */
.schedule-empty {
    text-align: center;
    padding: 3.5rem 1rem;
    background: var(--surface-2);
    border: 1px dashed var(--border-medium);
    border-radius: 16px;
}

.schedule-empty-icon {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: var(--surface-4);
    border: 1px solid var(--border-medium);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.65rem;
    color: var(--txt-faint);
    margin: 0 auto 1.25rem;
}

.schedule-empty h5 {
    font-family: 'Manrope', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--txt-secondary);
    margin-bottom: 6px;
}

.schedule-empty p {
    font-size: 0.825rem;
    color: var(--txt-muted);
    max-width: 300px;
    margin: 0 auto 1.25rem;
}

/* ══════════════════════════════════════════
   DELETE MODAL
══════════════════════════════════════════ */
.mg-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
}

.mg-modal-backdrop.show {
    opacity: 1;
    pointer-events: all;
}

.mg-modal {
    background: var(--surface-2);
    border: 1px solid var(--border-medium);
    border-radius: 20px;
    padding: 2rem;
    width: 360px;
    max-width: 90vw;
    box-shadow: var(--shadow-md);
    transform: scale(0.95) translateY(8px);
    transition: transform 0.2s;
}

.mg-modal-backdrop.show .mg-modal {
    transform: scale(1) translateY(0);
}

.mg-modal h4 {
    font-family: 'Manrope', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--txt-primary);
    margin-bottom: 8px;
}

.mg-modal p {
    font-size: 0.875rem;
    color: var(--txt-muted);
    margin-bottom: 1.5rem;
}

.mg-modal-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ol class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li class="active">Jadwal Saya</li>
</ol>

{{-- HERO --}}
<div class="sched-hero">
    <div class="sched-hero-grid"></div>
    <div class="sched-hero-content d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="sched-hero-eyebrow">
                <i class="bi bi-calendar3"></i> Ketersediaan
            </div>
            <h2>Jadwal <span>Saya</span></h2>
            <p>Kelola ketersediaan jadwal kamu untuk matchmaking futsal</p>
        </div>
        <a href="{{ route('schedule.create') }}" class="btn-lime" style="margin-top: 4px; flex-shrink: 0;">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
        </a>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row">
    <div class="mini-stat">
        <div class="mini-stat-icon">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $schedules->count() }}</div>
            <div class="mini-stat-label">Total Jadwal</div>
        </div>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background: var(--alert-success-bg); color: var(--alert-success-txt);">
            <i class="bi bi-calendar2-event"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $schedules->where('is_available', true)->count() }}</div>
            <div class="mini-stat-label">Tersedia</div>
        </div>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background: rgba(59,130,246,0.10); color: #60a5fa;">
            <i class="bi bi-people"></i>
        </div>
        <div>
            <div class="mini-stat-val">-</div>
            <div class="mini-stat-label">Matched</div>
        </div>
    </div>

    <div class="mini-stat">
        <div class="mini-stat-icon" style="background: var(--alert-warning-bg); color: var(--alert-warning-txt);">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="mini-stat-val">{{ $schedules->where('is_available', false)->count() }}</div>
            <div class="mini-stat-label">Tidak Tersedia</div>
        </div>
    </div>
</div>

{{-- CONTENT ROW --}}
<div class="content-row">

    {{-- CALENDAR --}}
    <div class="card-matchgo">
        <div class="cal-header">
            <div class="d-flex align-items-center gap-2">
                <button class="cal-nav-btn" id="calPrev"><i class="bi bi-chevron-left"></i></button>
                <span class="cal-title" id="calMonthLabel"></span>
                <button class="cal-nav-btn" id="calNext"><i class="bi bi-chevron-right"></i></button>
            </div>
            <button class="cal-nav-btn" id="calToday">Hari ini</button>
        </div>

        <div class="cal-dow">
            <div class="cal-dow-cell">Min</div>
            <div class="cal-dow-cell">Sen</div>
            <div class="cal-dow-cell">Sel</div>
            <div class="cal-dow-cell">Rab</div>
            <div class="cal-dow-cell">Kam</div>
            <div class="cal-dow-cell">Jum</div>
            <div class="cal-dow-cell">Sab</div>
        </div>

        <div class="cal-grid" id="calGrid"></div>

        <div class="cal-legend">
            <div class="cal-legend-item">
                <div class="cal-legend-dot" style="background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.3);"></div>
                Ada jadwal
            </div>
            <div class="cal-legend-item">
                <div class="cal-legend-dot" style="background: var(--accent); border-radius: 50%;"></div>
                Hari ini
            </div>
        </div>
    </div>

    {{-- LIST --}}
    <div>
        {{-- Results header — pola identik mm-results-header --}}
        <div class="sched-results-header">
            <div class="sched-results-title">
                Daftar Jadwal
                <span class="sched-count-pill">{{ $schedules->count() }}</span>
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">Semua</button>
                <button class="filter-tab" data-filter="available">Tersedia</button>
                <button class="filter-tab" data-filter="inactive">Tidak Tersedia</button>
            </div>
        </div>

        <div id="scheduleList">
            @forelse ($schedules->sortBy('day_of_week') as $jadwal)
            <div
                class="schedule-item"
                data-status="{{ $jadwal->is_available ? 'available' : 'inactive' }}"
                data-day="{{ $jadwal->day_of_week }}"
            >
                <div class="schedule-time-badge">
                    <span class="time-val">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }}</span>
                    <span class="time-sep">s/d</span>
                    <span class="time-end">{{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</span>
                </div>

                <div class="schedule-info">
                    <div class="schedule-title">
                        {{ $daysMap[$jadwal->day_of_week] }}
                    </div>
                    <div class="schedule-meta">
                        <span>
                            <i class="bi bi-arrow-repeat" style="font-size: 0.7rem;"></i>
                            Setiap {{ $daysMap[$jadwal->day_of_week] }}
                        </span>
                        <span class="status-pill {{ $jadwal->is_available ? 'available' : 'pending' }}">
                            {{ $jadwal->is_available ? 'Tersedia' : 'Tidak tersedia' }}
                        </span>
                    </div>
                </div>

                <div class="schedule-actions">
                    <a href="{{ route('schedule.edit', $jadwal->id) }}" class="schedule-action-btn" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button
                        class="schedule-action-btn delete"
                        title="Hapus"
                        onclick="confirmDelete({{ $jadwal->id }}, '{{ $daysMap[$jadwal->day_of_week] }}')"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            @empty
            <div class="schedule-empty">
                <div class="schedule-empty-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h5>Belum ada jadwal</h5>
                <p>Tambahkan jadwal ketersediaanmu untuk mulai matchmaking</p>
                <a href="{{ route('schedule.create') }}" class="btn-lime btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Jadwal
                </a>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- DELETE MODAL --}}
<div class="mg-modal-backdrop" id="deleteModal">
    <div class="mg-modal">
        <h4>
            <i class="bi bi-exclamation-triangle" style="color: #f87171; margin-right: 8px;"></i>
            Hapus Jadwal?
        </h4>
        <p id="deleteModalDesc">Jadwal ini akan dihapus permanen dan tidak bisa dikembalikan.</p>
        <div class="mg-modal-actions">
            <button class="btn-outline-lime" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-matchgo-danger">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Schedule days (0=Sun … 6=Sat)
const scheduleDays = new Set([
    @foreach($schedules as $s) {{ $s->day_of_week }}, @endforeach
]);

// ── Calendar
let calYear, calMonth;

function initCal() {
    const now = new Date();
    calYear  = now.getFullYear();
    calMonth = now.getMonth();
    renderCalendar();
}

function renderCalendar() {
    const grid  = document.getElementById('calGrid');
    const label = document.getElementById('calMonthLabel');
    grid.innerHTML = '';

    const monthNames = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];
    label.textContent = monthNames[calMonth] + ' ' + calYear;

    const today       = new Date();
    const firstDay    = new Date(calYear, calMonth, 1).getDay();
    const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
    const prevDays    = new Date(calYear, calMonth, 0).getDate();

    // Prev month fillers
    for (let i = firstDay - 1; i >= 0; i--) {
        const el = document.createElement('div');
        el.className = 'cal-day other-month';
        el.textContent = prevDays - i;
        grid.appendChild(el);
    }

    // Current month days
    for (let d = 1; d <= daysInMonth; d++) {
        const date = new Date(calYear, calMonth, d);
        const el   = document.createElement('div');
        el.className = 'cal-day';
        el.textContent = d;

        if (
            d === today.getDate() &&
            calMonth === today.getMonth() &&
            calYear  === today.getFullYear()
        ) {
            el.classList.add('today');
        }

        if (scheduleDays.has(date.getDay())) {
            el.classList.add('has-event');
        }

        grid.appendChild(el);
    }

    // Next month fillers
    const total     = firstDay + daysInMonth;
    const remainder = total % 7 === 0 ? 0 : 7 - (total % 7);
    for (let d = 1; d <= remainder; d++) {
        const el = document.createElement('div');
        el.className = 'cal-day other-month';
        el.textContent = d;
        grid.appendChild(el);
    }
}

document.getElementById('calPrev').addEventListener('click', function () {
    calMonth--;
    if (calMonth < 0) { calMonth = 11; calYear--; }
    renderCalendar();
});

document.getElementById('calNext').addEventListener('click', function () {
    calMonth++;
    if (calMonth > 11) { calMonth = 0; calYear++; }
    renderCalendar();
});

document.getElementById('calToday').addEventListener('click', function () {
    const now = new Date();
    calYear  = now.getFullYear();
    calMonth = now.getMonth();
    renderCalendar();
});

initCal();

// ── Filter tabs
document.querySelectorAll('.filter-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-tab').forEach(function (b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');
        document.querySelectorAll('.schedule-item').forEach(function (item) {
            if (filter === 'all' || item.getAttribute('data-status') === filter) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// ── Delete modal
function confirmDelete(id, name) {
    document.getElementById('deleteForm').action = '/schedule/' + id;
    document.getElementById('deleteModalDesc').textContent =
        'Jadwal hari ' + name + ' akan dihapus permanen dan tidak bisa dikembalikan.';
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

document.getElementById('deleteModal').addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush