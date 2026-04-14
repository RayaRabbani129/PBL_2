@extends('user.layouts.app')

@section('title', 'Jadwal Saya — MATCHGO')
@section('page-title', 'Jadwal Saya')

@php
$daysMap = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
@endphp

@section('content')

<ol class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="separator"><i class="bi bi-chevron-right"></i></li>
    <li class="active">Jadwal Saya</li>
</ol>

<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1>Jadwal Saya</h1>
        <p>Kelola ketersediaan jadwal kamu untuk matchmaking futsal</p>
    </div>
    <a href="{{ route('schedule.create') }}" class="btn-lime">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </a>
</div>

{{-- STATS --}}
<div class="row g-3 mb-5">
    <div class="col-lg-3 col-6">
        <div class="mini-stat">
            <div class="mini-stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="mini-stat-val">{{ $schedules->count() }}</div>
                <div class="mini-stat-label">Total Jadwal</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="mini-stat">
            <div class="mini-stat-icon"><i class="bi bi-calendar2-event"></i></div>
            <div>
                <div class="mini-stat-val">{{ $schedules->where('is_available', true)->count() }}</div>
                <div class="mini-stat-label">Tersedia</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:rgba(59,130,246,0.10); color:#60a5fa;">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="mini-stat-val">-</div>
                <div class="mini-stat-label">Matched</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:var(--alert-warning-bg); color:var(--alert-warning-txt);">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="mini-stat-val">{{ $schedules->where('is_available', false)->count() }}</div>
                <div class="mini-stat-label">Tidak Tersedia</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- CALENDAR --}}
    <div class="col-lg-4">
        <div class="card-matchgo">
            <div class="cal-header">
                <div class="d-flex align-items-center gap-2">
                    <button class="cal-nav-btn" id="calPrev"><i class="bi bi-chevron-left"></i></button>
                    <span class="cal-title" id="calMonthLabel"></span>
                    <button class="cal-nav-btn" id="calNext"><i class="bi bi-chevron-right"></i></button>
                </div>
                <button class="cal-nav-btn" id="calToday">Hari ini</button>
            </div>

            <div class="cal-grid" id="calGrid"></div>
        </div>
    </div>

    {{-- LIST --}}
    <div class="col-lg-8">

        <div class="filter-tabs mb-3">
            <button class="filter-tab active" data-filter="all">Semua</button>
            <button class="filter-tab" data-filter="available">Tersedia</button>
            <button class="filter-tab" data-filter="inactive">Tidak</button>
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
                    <span class="time-end">{{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</span>
                </div>

                <div class="schedule-info">
                    <div class="schedule-title">
                        {{ $daysMap[$jadwal->day_of_week] }}
                    </div>

                    <div class="schedule-meta">
                        <span>Setiap {{ $daysMap[$jadwal->day_of_week] }}</span>

                        <span class="status-pill {{ $jadwal->is_available ? 'available' : 'pending' }}">
                            {{ $jadwal->is_available ? 'Tersedia' : 'Tidak tersedia' }}
                        </span>
                    </div>
                </div>

                <div class="schedule-actions">
                    <a href="{{ route('schedule.edit', $jadwal->id) }}" class="schedule-action-btn">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <button class="schedule-action-btn delete"
                        onclick="confirmDelete({{ $jadwal->id }}, 'Jadwal {{ $daysMap[$jadwal->day_of_week] }}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            @empty
            <div class="schedule-empty">
                <h5>Belum ada jadwal</h5>
                <a href="{{ route('schedule.create') }}" class="btn-lime btn-sm">Tambah</a>
            </div>
            @endforelse
        </div>

    </div>
</div>

{{-- DELETE --}}
<div class="mg-modal-backdrop" id="deleteModal">
    <div class="mg-modal">
        <h4>Hapus Jadwal?</h4>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <button class="btn-matchgo-danger">Hapus</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const scheduleDays = new Set([
    @foreach($schedules as $s)
        {{ $s->day_of_week }},
    @endforeach
]);

function renderCalendar() {
    const grid = document.getElementById('calGrid');
    grid.innerHTML = '';

    let now = new Date();
    let year = now.getFullYear();
    let month = now.getMonth();

    let daysInMonth = new Date(year, month+1, 0).getDate();

    for(let i=1;i<=daysInMonth;i++){
        let date = new Date(year, month, i);
        let el = document.createElement('div');
        el.className = 'cal-day';

        if(scheduleDays.has(date.getDay())){
            el.classList.add('has-event');
        }

        el.innerText = i;
        grid.appendChild(el);
    }
}
renderCalendar();

function confirmDelete(id){
    document.getElementById('deleteForm').action = '/user/schedule/' + id;
    document.getElementById('deleteModal').classList.add('show');
}
</script>
@endpush