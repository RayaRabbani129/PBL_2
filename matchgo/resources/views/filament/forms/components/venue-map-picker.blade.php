<div>
    <div
        id="venue-map"
        style="
            width: 100%;
            height: 420px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            z-index: 1;
        "
    ></div>
</div>

<script>
    setTimeout(() => {

        if (typeof L === 'undefined') {
            console.error('Leaflet gagal dimuat');
            return;
        }

        const latInput = document.querySelector('input[name="latitude"]');
        const lngInput = document.querySelector('input[name="longitude"]');

        if (!latInput || !lngInput) {
            console.error('Latitude / Longitude input tidak ditemukan');
            return;
        }

        const defaultLat = parseFloat(latInput.value || -7.257472);
        const defaultLng = parseFloat(lngInput.value || 112.752090);

        // Hindari duplicate map
        const existingMap = document.getElementById('venue-map');

        if (existingMap._leaflet_id) {
            existingMap._leaflet_id = null;
        }

        const map = L.map('venue-map').setView(
            [defaultLat, defaultLng],
            13
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(map);

        const marker = L.marker(
            [defaultLat, defaultLng],
            {
                draggable: true
            }
        ).addTo(map);

        function updateInputs(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);

            latInput.dispatchEvent(new Event('input'));
            lngInput.dispatchEvent(new Event('input'));
        }

        marker.on('dragend', function () {
            const pos = marker.getLatLng();

            updateInputs(pos.lat, pos.lng);
        });

        map.on('click', function (e) {

            marker.setLatLng(e.latlng);

            updateInputs(
                e.latlng.lat,
                e.latlng.lng
            );
        });

        setTimeout(() => {
            map.invalidateSize();
        }, 300);

    }, 500);
</script>