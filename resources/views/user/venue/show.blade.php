{{-- resources/views/user/venue/show.blade.php --}}
@extends('user.layouts.app')

@section('title', $venue->name . ' — MATCHGO')
@section('page-title', 'Detail Lapangan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .vshow-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .vshow-layout { grid-template-columns: 1fr; }
    }

    /* ── Header card ── */
    .vshow-header {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .vshow-header-top {
        position: relative; padding: 1.75rem 2rem;
        border-bottom: 1px solid var(--border-subtle); overflow: hidden;
    }

    .vshow-header-top::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at bottom left, var(--accent-dim) 0%, transparent 60%);
        pointer-events: none;
    }

    .vshow-header-content { position: relative; z-index: 1; }

    .vshow-venue-name {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: var(--txt-primary); margin-bottom: 6px;
    }

    .vshow-venue-address {
        font-size: 0.85rem; color: var(--txt-muted);
        display: flex; align-items: center; gap: 5px; margin-bottom: 12px;
    }

    .vshow-meta-pills {
        display: flex; flex-wrap: wrap; gap: 8px;
    }

    .vshow-meta-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.75rem; font-weight: 600;
        padding: 4px 12px; border-radius: 99px;
        background: var(--surface-4); color: var(--txt-secondary);
        border: 1px solid var(--border-medium);
    }

    .vshow-meta-pill i { color: var(--accent); }
    .vshow-meta-pill.highlight {
        background: var(--accent-dim); color: var(--accent);
        border-color: rgba(163,177,75,0.25);
    }

    /* ── Venue map (small) ── */
    #venueDetailMap {
        width: 100%; height: 220px;
        background: var(--surface-4);
    }

    /* ── Section ── */
    .vshow-section {
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
        border-radius: 16px; overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .vshow-section-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex; align-items: center; gap: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 0.875rem; font-weight: 700; color: var(--txt-primary);
    }

    .vshow-section-header i { color: var(--accent); }

    .vshow-section-body { padding: 1.1rem 1.25rem; }

    /* ── Available slots grid ── */
    .slots-date-group { margin-bottom: 1rem; }
    .slots-date-group:last-child { margin-bottom: 0; }

    .slots-date-label {
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--txt-faint); margin-bottom: 7px;
        display: flex; align-items: center; gap: 6px;
    }

    .slots-grid {
        display: flex; flex-wrap: wrap; gap: 6px;
    }

    .slot-chip {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.75rem; font-weight: 600;
        padding: 5px 12px; border-radius: 9px;
        background: var(--accent-dim); color: var(--accent);
        border: 1px solid rgba(163,177,75,0.22);
    }

    /* ── Detail rows ── */
    .detail-row {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 12px;
        padding: 8px 0; border-bottom: 1px solid var(--border-subtle);
        font-size: 0.825rem;
    }

    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-row:first-child { padding-top: 0; }
    .detail-row-label { color: var(--txt-muted); font-weight: 500; flex-shrink: 0; }
    .detail-row-val   { color: var(--txt-primary); font-weight: 600; text-align: right; }

    /* ── Status badge ── */
    .venue-status-active   { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(163,177,75,0.20); }
    .venue-status-inactive { background: var(--surface-4); color: var(--txt-muted); border: 1px solid var(--border-subtle); }

    .vshow-status-badge {
        font-size: 0.68rem; font-weight: 700;
        padding: 2px 9px; border-radius: 99px;
        display: inline-flex; align-items: center; gap: 4px;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><a href="{{ route('venues.index') }}">Lapangan</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">{{ Str::limit($venue->name, 28) }}</span></li>
</ul>

{{-- Venue Header --}}
<div class="vshow-header">
    <div class="vshow-header-top">
        <div class="vshow-header-content">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                <div>
                    <div class="vshow-venue-name">{{ $venue->name }}</div>
                    @if ($venue->address)
                        <div class="vshow-venue-address">
                            <i class="bi bi-geo-alt" style="color:var(--accent);"></i>
                            {{ $venue->address }}@if($venue->city), {{ $venue->city }}@endif
                        </div>
                    @endif
                </div>
                <span class="vshow-status-badge {{ $venue->is_available && $venue->status === 'active' ? 'venue-status-active' : 'venue-status-inactive' }}">
                    <i class="bi {{ $venue->is_available ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                    {{ $venue->is_available && $venue->status === 'active' ? 'Tersedia' : 'Tidak Tersedia' }}
                </span>
            </div>

            <div class="vshow-meta-pills">
                @if ($venue->price_per_hour)
                    <span class="vshow-meta-pill highlight">
                        <i class="bi bi-cash-coin"></i>
                        Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam
                    </span>
                @endif
                @if ($venue->capacity)
                    <span class="vshow-meta-pill"><i class="bi bi-people"></i> Maks {{ $venue->capacity }} orang</span>
                @endif
                @if ($venue->phone)
                    <span class="vshow-meta-pill"><i class="bi bi-telephone"></i> {{ $venue->phone }}</span>
                @endif
                @if ($venue->city)
                    <span class="vshow-meta-pill"><i class="bi bi-pin-map"></i> {{ $venue->city }}@if($venue->province), {{ $venue->province }}@endif</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Mini map --}}
    @if ($venue->latitude && $venue->longitude)
        <div id="venueDetailMap"></div>
    @endif
</div>

{{-- Main layout --}}
<div class="vshow-layout">

    {{-- LEFT --}}
    <div>

        {{-- Available Schedules --}}
        <div class="vshow-section">
            <div class="vshow-section-header">
                <i class="bi bi-calendar2-check"></i> Jadwal Tersedia
                @if ($available->count() > 0)
                    <span style="font-size:0.72rem; color:var(--txt-muted); margin-left:auto; font-weight:500;">
                        {{ $available->count() }} slot untuk {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                    </span>
                @endif
            </div>
            <div class="vshow-section-body">
                {{-- Date picker --}}
                <form method="GET" action="{{ route('venues.show', $venue) }}" style="margin-bottom:1rem;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="date" name="date"
                               value="{{ $date }}"
                               min="{{ today()->format('Y-m-d') }}"
                               class="form-control-mg"
                               style="max-width:200px; color-scheme:dark;"
                               onchange="this.form.submit()">
                        <span style="font-size:0.8rem; color:var(--txt-muted);">Pilih tanggal untuk lihat slot</span>
                    </div>
                </form>

                @if ($available->isEmpty())
                    <div style="text-align:center; padding:1.5rem; color:var(--txt-faint); font-size:0.82rem;">
                        <i class="bi bi-calendar-x" style="font-size:1.5rem; display:block; margin-bottom:8px;"></i>
                        Tidak ada slot tersedia untuk tanggal ini
                    </div>
                @else
                    <div class="slots-grid">
                        @foreach ($available as $slot)
                            <div class="slot-chip">
                                <i class="bi bi-clock-fill"></i>
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Upcoming dates overview --}}
                @if ($upcomingDates->count() > 0)
                    <div style="margin-top:1.25rem;">
                        <div style="font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--txt-faint); margin-bottom:10px;">14 Hari Ke Depan</div>
                        <div style="display:flex; flex-wrap:wrap; gap:5px;">
                            @foreach ($upcomingDates as $d => $slots)
                                <a href="{{ route('venues.show', $venue) }}?date={{ $d }}"
                                   class="slot-chip" style="{{ $d === $date ? 'border-color:var(--accent); box-shadow:0 0 0 2px var(--accent-dim);' : 'background:var(--surface-4); color:var(--txt-muted); border-color:var(--border-subtle);' }}">
                                    {{ \Carbon\Carbon::parse($d)->format('D d/m') }}
                                    <span style="font-size:0.6rem; opacity:0.8;">({{ $slots->count() }} slot)</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        @if ($venue->description)
            <div class="vshow-section">
                <div class="vshow-section-header"><i class="bi bi-info-circle"></i> Tentang Lapangan</div>
                <div class="vshow-section-body">
                    <p style="font-size:0.875rem; color:var(--txt-secondary); line-height:1.7; margin:0;">
                        {{ $venue->description }}
                    </p>
                </div>
            </div>
        @endif

    </div>

    {{-- RIGHT: Detail + actions ── --}}
    <div>

        <div class="vshow-section">
            <div class="vshow-section-header"><i class="bi bi-list-ul"></i> Informasi Lapangan</div>
            <div class="vshow-section-body">
                <div class="detail-row">
                    <span class="detail-row-label">Nama</span>
                    <span class="detail-row-val">{{ $venue->name }}</span>
                </div>
                @if ($venue->city)
                    <div class="detail-row">
                        <span class="detail-row-label">Kota</span>
                        <span class="detail-row-val">{{ $venue->city }}</span>
                    </div>
                @endif
                @if ($venue->province)
                    <div class="detail-row">
                        <span class="detail-row-label">Provinsi</span>
                        <span class="detail-row-val">{{ $venue->province }}</span>
                    </div>
                @endif
                @if ($venue->address)
                    <div class="detail-row">
                        <span class="detail-row-label">Alamat</span>
                        <span class="detail-row-val" style="max-width:180px;">{{ $venue->address }}</span>
                    </div>
                @endif
                <div class="detail-row">
                    <span class="detail-row-label">Harga/Jam</span>
                    <span class="detail-row-val">
                        {{ $venue->price_per_hour ? 'Rp ' . number_format($venue->price_per_hour, 0, ',', '.') : '—' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-row-label">Kapasitas</span>
                    <span class="detail-row-val">{{ $venue->capacity ? $venue->capacity . ' orang' : '—' }}</span>
                </div>
                @if ($venue->phone)
                    <div class="detail-row">
                        <span class="detail-row-label">Telepon</span>
                        <span class="detail-row-val">{{ $venue->phone }}</span>
                    </div>
                @endif
                <div class="detail-row">
                    <span class="detail-row-label">Status</span>
                    <span class="detail-row-val">{{ $venue->is_available ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                </div>
            </div>
        </div>

        {{-- CTA ── --}}
        <div class="vshow-section">
            <div class="vshow-section-header"><i class="bi bi-lightning"></i> Aksi</div>
            <div class="vshow-section-body" style="display:flex; flex-direction:column; gap:8px;">
                @if ($venue->phone)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $venue->phone) }}" target="_blank"
                       class="btn-lime" style="justify-content:center;">
                        <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                    </a>
                @endif
                <a href="{{ route('venues.index') }}" class="btn-outline-lime" style="justify-content:center;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Rekomendasi
                </a>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const lat = {{ $venue->latitude ?? 'null' }};
    const lng = {{ $venue->longitude ?? 'null' }};
    if (!lat || !lng) return;

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const map    = L.map('venueDetailMap', { zoomControl: false, scrollWheelZoom: false });

    L.tileLayer(
        isDark
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
        { attribution: '© CARTO', subdomains: 'abcd', maxZoom: 19 }
    ).addTo(map);

    const icon = L.divIcon({
        className: '',
        html: `<div style="
            width:36px; height:36px; border-radius:10px;
            background:#A3B14B; border:3px solid #fff;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 3px 10px rgba(0,0,0,0.4); font-size:1.1rem;
        ">⚽</div>`,
        iconSize: [36, 36], iconAnchor: [18, 18],
    });

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup(`<strong>{{ $venue->name }}</strong>`)
        .openPopup();

    map.setView([lat, lng], 15);
})();
</script>
@endpush