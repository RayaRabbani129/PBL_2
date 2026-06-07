@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endonce

<div wire:ignore class="venue-map-wrapper">
    <div class="venue-map-search">
        <input
            type="text"
            id="venue-map-search-input"
            placeholder="Cari alamat / nama tempat..."
        >
        <button type="button" id="venue-map-search-button">
            Cari
        </button>
    </div>

    <div id="venue-map"></div>
</div>

<style>
    .venue-map-wrapper {
        position: relative;
        width: 100%;
        height: 460px;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid var(--border-subtle, #27272a);
        background: #111;
        box-shadow: 0 8px 24px rgba(0,0,0,.25);
    }

    #venue-map {
        width: 100%;
        height: 460px;
        min-height: 460px;
        z-index: 1;
    }

    .venue-map-search {
        position: absolute;
        top: 16px;
        left: 16px;
        right: 16px;
        z-index: 999;
        display: flex;
        gap: 8px;
        max-width: 560px;
    }

    .venue-map-search input {
        flex: 1;
        height: 42px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,.12);
        background: rgba(17,17,17,.92);
        color: #fff;
        padding: 0 14px;
        font-size: 14px;
        outline: none;
    }

    .venue-map-search input::placeholder {
        color: #a1a1aa;
    }

    .venue-map-search button {
        height: 42px;
        border-radius: 12px;
        border: 1px solid rgba(163,177,75,.35);
        background: rgba(163,177,75,.18);
        color: #A3B14B;
        padding: 0 18px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .venue-map-search button:hover {
        background: rgba(163,177,75,.28);
    }

    .leaflet-container {
        background: #111 !important;
        font-family: Inter, system-ui, sans-serif;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initVenueMapPicker();
    });

    document.addEventListener('livewire:navigated', function () {
        initVenueMapPicker();
    });

    setTimeout(() => {
        initVenueMapPicker();
    }, 800);

    function findFilamentInput(field) {
        return document.querySelector(`[wire\\:model="data.${field}"]`)
            || document.querySelector(`[wire\\:model\\.live="data.${field}"]`)
            || document.querySelector(`[wire\\:model\\.blur="data.${field}"]`)
            || document.querySelector(`[wire\\:model\\.defer="data.${field}"]`)
            || document.querySelector(`input[id$="-${field}"]`)
            || document.querySelector(`input[name="data.${field}"]`)
            || document.querySelector(`input[name="${field}"]`);
    }

    function initVenueMapPicker() {
        setTimeout(() => {
            if (typeof L === 'undefined') {
                console.error('Leaflet gagal dimuat');
                return;
            }

            const mapElement = document.getElementById('venue-map');

            if (!mapElement || mapElement.dataset.initialized === 'true') {
                return;
            }

            mapElement.dataset.initialized = 'true';

            const latInput = findFilamentInput('latitude');
            const lngInput = findFilamentInput('longitude');

            if (!latInput || !lngInput) {
                console.error('Input latitude / longitude tidak ditemukan.');
                return;
            }

            const defaultLat = parseFloat(latInput.value || -7.257472);
            const defaultLng = parseFloat(lngInput.value || 112.752090);

            const map = L.map(mapElement).setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            const marker = L.marker([defaultLat, defaultLng], {
                draggable: true,
            }).addTo(map);

            function updateInputs(lat, lng) {
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);

                latInput.dispatchEvent(new Event('input', { bubbles: true }));
                lngInput.dispatchEvent(new Event('input', { bubbles: true }));

                latInput.dispatchEvent(new Event('change', { bubbles: true }));
                lngInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            function moveMarker(lat, lng, zoom = 16) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], zoom);
                updateInputs(lat, lng);
            }

            marker.on('dragend', function () {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            map.on('click', function (e) {
                moveMarker(e.latlng.lat, e.latlng.lng);
            });

            const searchInput = document.getElementById('venue-map-search-input');
            const searchButton = document.getElementById('venue-map-search-button');

            async function searchLocation() {
                const keyword = searchInput.value.trim();

                if (!keyword) {
                    return;
                }

                searchButton.disabled = true;
                searchButton.textContent = 'Mencari...';

                try {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(keyword)}&limit=5&countrycodes=id`;

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const results = await response.json();

                    if (!results.length) {
                        alert('Alamat tidak ditemukan.');
                        return;
                    }

                    const selected = results[0];

                    moveMarker(
                        parseFloat(selected.lat),
                        parseFloat(selected.lon),
                        17
                    );

                    searchInput.value = selected.display_name;
                } catch (error) {
                    console.error(error);
                    alert('Gagal mencari alamat.');
                } finally {
                    searchButton.disabled = false;
                    searchButton.textContent = 'Cari';
                }
            }

            searchButton.addEventListener('click', searchLocation);

            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchLocation();
                }
            });

            setTimeout(() => {
                map.invalidateSize();
            }, 500);
        }, 500);
    }
</script>