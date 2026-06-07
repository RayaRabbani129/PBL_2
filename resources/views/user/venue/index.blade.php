{{-- resources/views/user/venue/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Rekomendasi Lapangan — MATCHGO')
@section('page-title', 'Auto Venue')

@push('styles')
<style>
    /* ── Hero ── */
    .venue-hero {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
        background: var(--surface-2);
        border: 1px solid var(--border-subtle);
    }

    .venue-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at top right, var(--accent-dim) 0%, transparent 60%);
        pointer-events: none;
    }

    .venue-hero-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(var(--border-subtle) 1px, transparent 1px),
            linear-gradient(90deg, var(--border-subtle) 1px, transparent 1px);
        background-size: 28px 28px;
        opacity: 0.3; pointer-events: none;
    }

    .venue-hero-content { position: relative; z-index: 1; }

    .venue-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--accent);
        background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20);
        border-radius: 99px; padding: 4px 12px; margin-bottom: 12px;
    }

    .venue-hero h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: var(--txt-primary); margin-bottom: 6px; line-height: 1.2;
    }

    .venue-hero h2 span { color: var(--accent); }
    .venue-hero p { font-size: 0.85rem; color: var(--txt-muted); margin: 0; }

    /* ── Midpoint badge ── */
    .midpoint-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--surface-3); border: 1px solid var(--border-medium);
        border-radius: 12px; padding: 8px 14px; margin-bottom: 1.5rem;
        font-size: 0.8rem; color: var(--txt-secondary); flex-wrap: wrap;
    }

    .midpoint-badge i { color: var(--accent); }

    .midpoint-team-pin {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.75rem; font-weight: 600;
    }

    .midpoint-dot {
        width: 8px; height: 8px; border-radius: 50%;
    }

    /* ── Main layout ── */
    .venue-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .venue-layout { grid-template-columns: 1fr; }
    }

    /* ── Map container ── */
    .venue-map-wrap {
        background: var(--surface-3);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.25rem;
        position: relative;
    }

    .venue-map-header {
        padding: 0.75rem 1.1rem;
        border-bottom: 1px solid var(--border-subtle);
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.78rem; font-weight: 600; color: var(--txt-secondary);
    }

    .venue-map-header i { color: var(--accent); margin-right: 5px; }

    #venueMap {
        width: 100%; height: 340px;
        background: var(--surface-4);
        display: flex; align-items: center; justify-content: center;
        color: var(--txt-faint); font-size: 0.82rem;
    }

    .map-no-coords {
        text-align: center; padding: 2rem;
    }

    .map-no-coords i { font-size: 2rem; color: var(--txt-faint); display: block; margin-bottom: 8px; }

    /* ── Results header ── */
    .venue-results-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1rem; padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .venue-results-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1rem; font-weight: 700; color: var(--txt-primary);
        display: flex; align-items: center; gap: 8px;
    }

    .venue-count-pill {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 22px; height: 22px; border-radius: 99px;
        background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20);
        font-size: 0.7rem; font-weight: 700; color: var(--accent); padding: 0 7px;
    }

    /* ── Active filter tags ── */
    .venue-active-filters {
        display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 1rem;
    }

    .venue-filter-tag {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.7rem; font-weight: 600;
        padding: 3px 10px; border-radius: 99px;
        background: var(--surface-4); color: var(--txt-secondary);
        border: 1px solid var(--border-medium);
    }

    .venue-filter-tag i { color: var(--accent); }

    /* ── Idle state ── */
    .venue-idle {
        text-align: center; padding: 3.5rem 1rem;
        background: var(--surface-2); border: 1px solid var(--border-subtle);
        border-radius: 16px;
    }

    .venue-idle-icon {
        width: 64px; height: 64px; border-radius: 18px;
        background: var(--accent-dim); border: 1px solid rgba(163,177,75,0.20);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: var(--accent); margin: 0 auto 1rem;
    }

    .venue-idle h4 {
        font-family: 'Manrope', sans-serif; font-size: 1rem;
        font-weight: 700; color: var(--txt-secondary); margin-bottom: 5px;
    }

    .venue-idle p { font-size: 0.83rem; color: var(--txt-muted); max-width: 280px; margin: 0 auto; }
</style>

{{-- Leaflet CSS (map) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

{{-- Breadcrumb --}}
<ul class="breadcrumb-matchgo">
    <li><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
    <li><span class="separator"><i class="bi bi-chevron-right"></i></span></li>
    <li><span class="active">Rekomendasi Lapangan</span></li>
</ul>

{{-- Hero --}}
<div class="venue-hero">
    <div class="venue-hero-grid"></div>
    <div class="venue-hero-content d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="venue-hero-eyebrow"><i class="bi bi-geo-alt-fill"></i> Auto Venue</div>
            <h2>Lapangan <span>Terbaik</span> untuk Matchmu</h2>
            <p>Rekomendasi otomatis berdasarkan titik tengah lokasi kedua tim dan jadwal kosong lapangan.</p>
        </div>
        <div style="display:flex; flex-direction:column; gap:5px; margin-top:4px;">
            <div style="font-size:0.775rem; color:var(--txt-muted); display:flex; align-items:center; gap:5px;">
                <i class="bi bi-building" style="color:var(--accent);"></i>
                Lapangan terdaftar: <strong style="color:var(--txt-primary)">{{ \App\Models\Venue::where('status','active')->count() }}</strong>
            </div>
        </div>
    </div>
</div>

{{-- Midpoint info --}}
@if ($midpoint)
    <div class="midpoint-badge">
        <i class="bi bi-bullseye"></i>
        <strong>Titik Tengah:</strong>
        <span class="midpoint-team-pin">
            <span class="midpoint-dot" style="background:var(--accent);"></span>
            {{ $myTeam->name }}
            @if ($myTeam->city)({{ $myTeam->city }})@endif
        </span>
        @if ($opponentTeam)
            <span style="color:var(--txt-faint);">↔</span>
            <span class="midpoint-team-pin">
                <span class="midpoint-dot" style="background:#67e8f9;"></span>
                {{ $opponentTeam->name }}
                @if ($opponentTeam->city)({{ $opponentTeam->city }})@endif
            </span>
            @if ($midpoint['is_midpoint'])
                <span style="color:var(--txt-faint); font-size:0.72rem;">— koordinat tengah dihitung otomatis</span>
            @endif
        @endif
    </div>
@endif

{{-- Main layout --}}
<div class="venue-layout">

    {{-- LEFT: Filter --}}
    @include('user.venue.filter', [
        'filters'      => $filters,
        'myTeam'       => $myTeam,
        'opponentTeam' => $opponentTeam,
    ])

    {{-- RIGHT: Map + Results --}}
    <div>

        {{-- MAP --}}
        <div class="venue-map-wrap">
            <div class="venue-map-header">
                <span><i class="bi bi-map"></i> Peta Lapangan</span>
                @if ($searched && $venues->count() > 0)
                    <span style="color:var(--txt-muted);">{{ $venues->count() }} lapangan ditemukan</span>
                @endif
            </div>
            <div id="venueMap">
                @if (!$myTeam->latitude || !$myTeam->longitude)
                    <div class="map-no-coords">
                        <i class="bi bi-geo"></i>
                        Lengkapi koordinat tim kamu untuk mengaktifkan peta.
                    </div>
                @endif
            </div>
        </div>

        {{-- Results --}}
        <div class="venue-results-header">
            <div class="venue-results-title">
                Rekomendasi Lapangan
                @if ($searched)
                    <span class="venue-count-pill">{{ $venues->count() }}</span>
                @endif
            </div>
            @if ($searched && $venues->count() > 0)
                <span style="font-size:0.775rem; color:var(--txt-muted);">
                    Diurutkan: {{ match($filters['sort_by'] ?? 'score') { 'distance' => 'Terdekat', 'price' => 'Termurah', default => 'Skor tertinggi' } }}
                </span>
            @endif
        </div>

        {{-- Active filters --}}
        @if ($searched && (!empty($filters['date']) || !empty($filters['max_price']) || !empty($filters['max_distance'])))
            <div class="venue-active-filters">
                <span style="font-size:0.68rem; color:var(--txt-faint); font-weight:700; text-transform:uppercase; letter-spacing:0.08em;">Filter aktif:</span>
                @if (!empty($filters['date']))
                    <span class="venue-filter-tag"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($filters['date'])->format('d M Y') }}</span>
                @endif
                @if (!empty($filters['start_time']) && !empty($filters['end_time']))
                    <span class="venue-filter-tag"><i class="bi bi-clock"></i> {{ $filters['start_time'] }}–{{ $filters['end_time'] }}</span>
                @endif
                @if (!empty($filters['max_price']))
                    <span class="venue-filter-tag"><i class="bi bi-cash"></i> Maks Rp {{ number_format($filters['max_price'], 0, ',', '.') }}/jam</span>
                @endif
                @if (!empty($filters['max_distance']))
                    <span class="venue-filter-tag"><i class="bi bi-geo"></i> {{ $filters['max_distance'] }} km</span>
                @endif
            </div>
        @endif

        {{-- States --}}
        @if (!$searched)
            <div class="venue-idle">
                <div class="venue-idle-icon"><i class="bi bi-geo-alt"></i></div>
                <h4>Atur Filter & Cari Lapangan</h4>
                <p>Pilih tanggal, waktu, dan jarak maksimal untuk mendapatkan rekomendasi terbaik.</p>
            </div>
        @elseif ($venues->isEmpty())
            <div class="venue-idle">
                <div class="venue-idle-icon" style="background:var(--surface-4); border-color:var(--border-medium); color:var(--txt-faint);">
                    <i class="bi bi-building-x"></i>
                </div>
                <h4>Tidak Ada Lapangan Ditemukan</h4>
                <p>Coba perluas jarak atau ubah tanggal dan waktu pencarian.</p>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach ($venues as $item)
                    @include('user.venue.card', [
                        'venue'          => $item['venue'],
                        'score'          => $item['score'],
                        'score_label'    => $item['score_label'],
                        'score_color'    => $item['score_color'],
                        'distance_km'    => $item['distance_km'],
                        'available_slots'=> $item['available_slots'],
                        'rank'           => $loop->iteration,
                    ])
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

    // ── Map data dari PHP ──────────────────────────────────────
    const myTeam = {
        lat:  {{ $myTeam->latitude  ?? 'null' }},
        lng:  {{ $myTeam->longitude ?? 'null' }},
        name: @json($myTeam->name),
    };

    const midpoint = @json($midpoint);

    const venues = @json($venuesFormatted);

    if (!myTeam.lat || !myTeam.lng) return; // Tidak ada koordinat

    // ── Init map ───────────────────────────────────────────────
    const map = L.map('venueMap', { zoomControl: true });

    // Tile layer — CartoDB dark/light
    const tileUrl = isDark
        ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
        : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

    L.tileLayer(tileUrl, {
        attribution: '© <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd', maxZoom: 19,
    }).addTo(map);

    const bounds = [];

    // ── My team marker ─────────────────────────────────────────
    const myIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:32px; height:32px; border-radius:50%;
            background:#A3B14B; border:3px solid #fff;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.4);
            font-family:'Manrope',sans-serif; font-weight:800;
            font-size:0.65rem; color:#0C0C0C;
        ">${myTeam.name.substring(0,2).toUpperCase()}</div>`,
        iconSize: [32, 32], iconAnchor: [16, 16],
    });

    L.marker([myTeam.lat, myTeam.lng], { icon: myIcon })
        .addTo(map)
        .bindPopup(`<strong>${myTeam.name}</strong><br><small>Tim Saya</small>`);

    bounds.push([myTeam.lat, myTeam.lng]);

    // ── Midpoint marker ────────────────────────────────────────
    if (midpoint && midpoint.is_midpoint) {
        const midIcon = L.divIcon({
            className: '',
            html: `<div style="
                width:20px; height:20px; border-radius:50%;
                background:#fcd34d; border:2px solid #fff;
                box-shadow:0 2px 6px rgba(0,0,0,0.4);
            "></div>`,
            iconSize: [20, 20], iconAnchor: [10, 10],
        });

        L.marker([midpoint.lat, midpoint.lng], { icon: midIcon })
            .addTo(map)
            .bindPopup('<strong>Titik Tengah</strong><br><small>Acuan pencarian lapangan</small>');

        bounds.push([midpoint.lat, midpoint.lng]);

        // Circle radius ~10km dari midpoint
        L.circle([midpoint.lat, midpoint.lng], {
            radius: 10000, color: '#A3B14B', fillColor: '#A3B14B',
            fillOpacity: 0.05, weight: 1.5, dashArray: '5,5',
        }).addTo(map);
    }

    // ── Venue markers ──────────────────────────────────────────
    venues.forEach((v, i) => {
        if (!v.lat || !v.lng) return;

        const venueIcon = L.divIcon({
            className: '',
            html: `<div style="
                width:28px; height:28px; border-radius:8px;
                background:${i === 0 ? '#fcd34d' : '#1C1C1C'};
                border:2px solid ${i === 0 ? '#fff' : '#A3B14B'};
                display:flex; align-items:center; justify-content:center;
                box-shadow:0 2px 8px rgba(0,0,0,0.5);
                font-family:'Manrope',sans-serif; font-weight:800;
                font-size:0.6rem; color:${i === 0 ? '#0C0C0C' : '#A3B14B'};
            ">#${i + 1}</div>`,
            iconSize: [28, 28], iconAnchor: [14, 14],
        });

        const priceStr = v.price
            ? 'Rp ' + Number(v.price).toLocaleString('id-ID') + '/jam'
            : 'Harga belum tercantum';

        const distStr = v.distance_km ? `${v.distance_km} km dari titik tengah` : '';

        L.marker([v.lat, v.lng], { icon: venueIcon })
            .addTo(map)
            .bindPopup(`
                <div style="min-width:160px;">
                    <strong style="font-size:0.85rem;">${v.name}</strong><br>
                    <small style="color:#888;">${v.address}</small><br>
                    <div style="margin-top:5px; font-size:0.75rem;">
                        💰 ${priceStr}<br>
                        ${distStr ? '📍 ' + distStr : ''}
                        <br>⭐ Skor: ${v.score}/100
                    </div>
                </div>
            `);

        bounds.push([v.lat, v.lng]);
    });

    // ── Fit map to all markers ─────────────────────────────────
    if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [40, 40] });
    } else if (bounds.length === 1) {
        map.setView(bounds[0], 13);
    }

    // ── Re-render map on theme toggle ─────────────────────────
    document.getElementById('themeToggle')?.addEventListener('click', () => {
        setTimeout(() => map.invalidateSize(), 350);
    });
})();
</script>
@endpush