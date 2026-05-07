{{-- resources/views/user/team/form.blade.php --}}

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="teamForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    {{-- ══════════════════════════════════════════
         SECTION 1 — IDENTITAS TIM
    ══════════════════════════════════════════ --}}
    <div class="mg-section mb-4">

        {{-- Section label --}}
        <div class="mg-section-header">
            <div class="mg-section-num">01</div>
            <div>
                <h6 class="mg-section-title">Identitas Tim</h6>
                <p class="mg-section-sub">Informasi dasar yang tampil di profil tim Anda.</p>
            </div>
        </div>

        <div class="mg-section-body">

            {{-- ── Logo Upload ── --}}
            <div class="form-group-mg mb-5">
                <label class="form-label-mg">Logo Tim</label>
                <div class="logo-upload-zone">
                    <div id="logoPreview" class="logo-preview-box">
                        @if($team?->logo_path)
                            <img src="{{ Storage::url($team->logo_path) }}" style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                        @else
                            <div class="logo-placeholder">
                                <span class="logo-placeholder-icon">⚽</span>
                                <span class="logo-placeholder-text">Upload Logo</span>
                            </div>
                        @endif
                    </div>
                    <div class="logo-upload-info">
                        <label for="logo_path" class="btn-matchgo-outline logo-upload-btn">
                            <i class="bi bi-cloud-upload-fill"></i> Pilih Gambar
                        </label>
                        <input type="file" id="logo_path" name="logo_path" accept="image/*"
                               style="display:none;" onchange="previewLogo(this)">
                        <p class="logo-upload-hint">Mendukung JPG dan PNG<br>Ukuran maksimal <strong>2 MB</strong></p>
                        @error('logo_path')
                            <div class="field-error-mg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Grid fields ── --}}
            <div class="row g-4">

                {{-- Nama Tim --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label for="name" class="form-label-mg">
                            Nama Tim <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-shield-fill input-icon"></i>
                            <input type="text" id="name" name="name"
                                   class="form-control-mg with-icon @error('name') is-invalid-mg @enderror"
                                   value="{{ old('name', $team?->name) }}"
                                   placeholder="cth. FC Malang Raya" required>
                        </div>
                        @error('name')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Level Tim --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label for="level" class="form-label-mg">
                            Level Tim <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-bar-chart-fill input-icon"></i>
                            <select id="level" name="level"
                                    class="form-control-mg with-icon @error('level') is-invalid-mg @enderror" required>
                                <option value="" disabled {{ old('level', $team?->level) ? '' : 'selected' }}>-- Pilih Level --</option>
                                @foreach(['casual','semi_pro','competitive'] as $lvl)
                                    <option value="{{ $lvl }}" {{ old('level', $team?->level) === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('level')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Kota --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label for="city" class="form-label-mg">
                            Kota <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-building input-icon"></i>
                            <input type="text" id="city" name="city"
                                   class="form-control-mg with-icon @error('city') is-invalid-mg @enderror"
                                   value="{{ old('city', $team?->city) }}"
                                   placeholder="cth. Malang" required>
                        </div>
                        @error('city')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Provinsi --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label for="province" class="form-label-mg">
                            Provinsi <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-map input-icon"></i>
                            <input type="text" id="province" name="province"
                                   class="form-control-mg with-icon @error('province') is-invalid-mg @enderror"
                                   value="{{ old('province', $team?->province) }}"
                                   placeholder="cth. Jawa Timur" required>
                        </div>
                        @error('province')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="col-12">
                    <div class="form-group-mg">
                        <label for="description" class="form-label-mg">Deskripsi Tim</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control-mg @error('description') is-invalid-mg @enderror"
                                  placeholder="Ceritakan sedikit tentang tim Anda — gaya bermain, sejarah, atau visi tim...">{{ old('description', $team?->description) }}</textarea>
                        <div class="char-count-wrap">
                            <span id="charCount">0</span> karakter
                        </div>
                        @error('description')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label class="form-label-mg">Status Tim <span class="required-star">*</span></label>
                        <div class="status-toggle-group">
                            <label class="status-toggle-option">
                                <input type="radio" name="status" value="active"
                                       {{ old('status', $team?->status ?? 'active') === 'active' ? 'checked' : '' }}>
                                <span class="status-toggle-card status-toggle-active">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Aktif</span>
                                </span>
                            </label>
                            <label class="status-toggle-option">
                                <input type="radio" name="status" value="inactive"
                                       {{ old('status', $team?->status) === 'inactive' ? 'checked' : '' }}>
                                <span class="status-toggle-card status-toggle-inactive">
                                    <i class="bi bi-pause-circle-fill"></i>
                                    <span>Tidak Aktif</span>
                                </span>
                            </label>
                        </div>
                        @error('status')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SECTION 2 — LOKASI LAPANGAN
    ══════════════════════════════════════════ --}}
    <div class="mg-section mb-4">

        <div class="mg-section-header">
            <div class="mg-section-num">02</div>
            <div>
                <h6 class="mg-section-title">Lokasi Lapangan</h6>
                <p class="mg-section-sub">Klik peta atau cari alamat untuk menentukan koordinat tim Anda.</p>
            </div>
        </div>

        <div class="mg-section-body">

            {{-- Koordinat + Deteksi --}}
            <div class="row g-3 mb-4">
                <div class="col-lg-5">
                    <div class="form-group-mg">
                        <label for="latitude" class="form-label-mg">
                            Latitude <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-geo input-icon"></i>
                            <input type="text" id="latitude" name="latitude"
                                   class="form-control-mg with-icon @error('latitude') is-invalid-mg @enderror"
                                   value="{{ old('latitude', $team?->latitude) }}"
                                   placeholder="-7.983900" required readonly>
                        </div>
                        @error('latitude')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group-mg">
                        <label for="longitude" class="form-label-mg">
                            Longitude <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-geo input-icon"></i>
                            <input type="text" id="longitude" name="longitude"
                                   class="form-control-mg with-icon @error('longitude') is-invalid-mg @enderror"
                                   value="{{ old('longitude', $team?->longitude) }}"
                                   placeholder="112.621200" required readonly>
                        </div>
                        @error('longitude')<div class="field-error-mg">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                    <button type="button" onclick="detectLocation()" class="btn-matchgo-outline w-100"
                            style="height:46px;justify-content:center;gap:6px;border-radius:12px;">
                        <i class="bi bi-crosshair2"></i>
                        <span class="d-none d-xl-inline">GPS</span>
                    </button>
                </div>
            </div>

            {{-- ── Map Container ── --}}
            <div class="map-container-outer">

                {{-- Search bar di atas peta --}}
                <div class="map-search-bar">
                    <div class="map-search-inner">
                        <i class="bi bi-search map-search-icon"></i>
                        <input type="text" id="mapSearchInput"
                               placeholder="Cari alamat, nama jalan, atau landmark..."
                               class="map-search-input"
                               autocomplete="off">
                        <button type="button" id="mapSearchBtn" class="map-search-btn">
                            Cari
                        </button>
                    </div>
                    <div id="mapSearchResults" class="map-search-dropdown" style="display:none;"></div>
                </div>

                {{-- Peta --}}
                <div id="teamMap" class="team-map"></div>

                {{-- Overlay info klik --}}
                <div class="map-hint-overlay" id="mapHint">
                    <i class="bi bi-cursor-fill"></i> Klik pada peta untuk menentukan titik lokasi
                </div>

                {{-- Badge koordinat terpilih --}}
                <div class="map-coord-badge" id="mapCoordBadge" style="display:none;">
                    <i class="bi bi-pin-map-fill"></i>
                    <span id="mapCoordText">—</span>
                </div>

            </div>

        </div>
    </div>

    {{-- ── Actions ── --}}
    <div class="form-actions">
        <a href="{{ route('team.index') }}" class="btn-matchgo-outline form-action-cancel">
            <i class="bi bi-x-lg"></i> Batal
        </a>
        <button type="submit" class="btn-matchgo-primary form-action-submit">
            <i class="bi bi-check-lg"></i> {{ $btnText }}
        </button>
    </div>

</form>


{{-- ══════════════════════════════════════════
     STYLES
══════════════════════════════════════════ --}}
@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
/* ─────────────────────────────────────
   SECTION LAYOUT
───────────────────────────────────── */
.mg-section {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 20px;
    overflow: hidden;
    transition: border-color .3s;
}

.mg-section-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem 1.75rem;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--surface-3);
}

.mg-section-num {
    font-size: 1.5rem;
    font-family: 'Manrope', sans-serif;
    font-weight: 900;
    color: var(--accent);
    opacity: .35;
    line-height: 1;
    flex-shrink: 0;
    margin-top: 2px;
}

.mg-section-title {
    font-family: 'Manrope', sans-serif;
    font-size: .85rem;
    font-weight: 800;
    color: var(--txt-primary);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 3px;
}

.mg-section-sub {
    font-size: .78rem;
    color: var(--txt-faint);
    margin: 0;
    line-height: 1.5;
}

.mg-section-body {
    padding: 1.75rem;
}

/* ─────────────────────────────────────
   LOGO UPLOAD
───────────────────────────────────── */
.logo-upload-zone {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: var(--surface-3);
    border: 1.5px dashed var(--border-subtle);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    transition: border-color .2s, background .2s;
}

.logo-upload-zone:hover {
    border-color: rgba(163,177,75,.4);
    background: var(--accent-dim);
}

.logo-preview-box {
    width: 80px;
    height: 80px;
    border-radius: 18px;
    background: var(--surface-4);
    border: 1.5px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.logo-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.logo-placeholder-icon {
    font-size: 26px;
    line-height: 1;
}

.logo-placeholder-text {
    font-size: 9px;
    color: var(--txt-faint);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.logo-upload-btn {
    margin-bottom: 0;
    cursor: pointer;
}

.logo-upload-hint {
    font-size: .75rem;
    color: var(--txt-faint);
    margin: 8px 0 0;
    line-height: 1.6;
}

/* ─────────────────────────────────────
   INPUT WITH ICON
───────────────────────────────────── */
.input-icon-wrap {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--txt-faint);
    font-size: 13px;
    pointer-events: none;
    z-index: 2;
}

.form-control-mg.with-icon {
    padding-left: 38px !important;
}

/* ─────────────────────────────────────
   REQUIRED STAR
───────────────────────────────────── */
.required-star {
    color: #f87171;
    margin-left: 2px;
}

/* ─────────────────────────────────────
   CHAR COUNT
───────────────────────────────────── */
.char-count-wrap {
    margin-top: 6px;
    font-size: .72rem;
    color: var(--txt-faint);
    text-align: right;
}

/* ─────────────────────────────────────
   STATUS TOGGLE (radio card)
───────────────────────────────────── */
.status-toggle-group {
    display: flex;
    gap: 10px;
}

.status-toggle-option {
    flex: 1;
    cursor: pointer;
    margin: 0;
}

.status-toggle-option input[type="radio"] {
    display: none;
}

.status-toggle-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    border-radius: 12px;
    border: 1.5px solid var(--border-subtle);
    background: var(--surface-3);
    font-size: .82rem;
    font-weight: 600;
    color: var(--txt-muted);
    transition: all .18s;
    cursor: pointer;
    user-select: none;
}

.status-toggle-option input:checked + .status-toggle-active {
    border-color: rgba(163,177,75,.55);
    background: var(--accent-dim);
    color: var(--accent);
    box-shadow: 0 0 0 3px rgba(163,177,75,.08);
}

.status-toggle-option input:checked + .status-toggle-inactive {
    border-color: rgba(239,68,68,.4);
    background: rgba(239,68,68,.06);
    color: #f87171;
    box-shadow: 0 0 0 3px rgba(239,68,68,.06);
}

.status-toggle-card:hover {
    border-color: var(--accent);
    background: var(--accent-dim);
}

/* ─────────────────────────────────────
   INVALID STATE
───────────────────────────────────── */
.is-invalid-mg {
    border-color: rgba(239,68,68,.55) !important;
    background-color: rgba(239,68,68,.04) !important;
}

.is-invalid-mg:focus {
    box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important;
}

.field-error-mg {
    margin-top: 5px;
    font-size: 0.775rem;
    color: #f87171;
    display: flex;
    align-items: center;
    gap: 5px;
}

.field-error-mg::before {
    content: '\F33A';
    font-family: 'Bootstrap-icons';
    font-size: .8rem;
}

/* ─────────────────────────────────────
   MAP
───────────────────────────────────── */
.map-container-outer {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    border: 1.5px solid var(--border-subtle);
    box-shadow: 0 4px 24px rgba(0,0,0,.12);
}

/* Search bar floating di atas peta */
.map-search-bar {
    position: absolute;
    top: 14px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    width: min(520px, calc(100% - 2rem));
}

.map-search-inner {
    display: flex;
    align-items: center;
    background: var(--surface-0, #fff);
    border: 1.5px solid var(--border-subtle);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    backdrop-filter: blur(8px);
}

.map-search-icon {
    color: var(--txt-faint);
    font-size: 14px;
    padding: 0 10px 0 14px;
    flex-shrink: 0;
}

.map-search-input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: .84rem;
    color: var(--txt-primary, #111);
    padding: 11px 8px;
    min-width: 0;
}

.map-search-input::placeholder {
    color: var(--txt-faint, #aaa);
}

.map-search-btn {
    background: var(--accent, #a3b14b);
    color: #fff;
    border: none;
    padding: 0 18px;
    height: 44px;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s;
    flex-shrink: 0;
}

.map-search-btn:hover {
    background: var(--accent-dark, #8a9a30);
}

/* Dropdown hasil pencarian */
.map-search-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: var(--surface-0, #fff);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    box-shadow: 0 12px 40px rgba(0,0,0,.18);
    max-height: 260px;
    overflow-y: auto;
    z-index: 1001;
}

.map-search-item {
    padding: 10px 16px;
    font-size: .82rem;
    color: var(--txt-primary, #111);
    cursor: pointer;
    border-bottom: 1px solid var(--border-subtle);
    display: flex;
    align-items: flex-start;
    gap: 10px;
    line-height: 1.45;
    transition: background .1s;
}

.map-search-item:last-child {
    border-bottom: none;
}

.map-search-item:hover {
    background: var(--accent-dim, rgba(163,177,75,.1));
}

.map-search-item i {
    color: var(--accent, #a3b14b);
    font-size: 14px;
    flex-shrink: 0;
    margin-top: 2px;
}

.map-search-loading,
.map-search-empty {
    padding: 14px 16px;
    font-size: .82rem;
    color: var(--txt-faint, #aaa);
    text-align: center;
}

/* Peta utama */
.team-map {
    width: 100%;
    height: 420px;
    display: block;
}

/* Hint overlay */
.map-hint-overlay {
    position: absolute;
    bottom: 60px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,.65);
    color: #fff;
    font-size: .75rem;
    padding: 7px 14px;
    border-radius: 99px;
    z-index: 1000;
    pointer-events: none;
    backdrop-filter: blur(6px);
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: opacity .4s;
}

/* Badge koordinat */
.map-coord-badge {
    position: absolute;
    bottom: 14px;
    left: 14px;
    background: var(--surface-2, #fff);
    border: 1px solid var(--border-subtle);
    border-radius: 10px;
    padding: 7px 12px;
    font-size: .75rem;
    color: var(--txt-secondary, #555);
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
    font-family: 'Courier New', monospace;
}

.map-coord-badge i {
    color: var(--accent, #a3b14b);
}

/* ─────────────────────────────────────
   FORM ACTIONS
───────────────────────────────────── */
.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    padding: 1.5rem 0 .5rem;
}

.form-action-cancel {
    padding: 11px 22px;
    border-radius: 12px;
}

.form-action-submit {
    padding: 11px 28px;
    border-radius: 12px;
    font-size: .9rem;
}

/* ─────────────────────────────────────
   RESPONSIVE
───────────────────────────────────── */
@media (max-width: 767px) {
    .mg-section-body { padding: 1.25rem; }
    .mg-section-header { padding: 1.1rem 1.25rem; }
    .logo-upload-zone { flex-direction: column; text-align: center; }
    .team-map { height: 320px; }
    .status-toggle-group { flex-direction: column; }
    .form-actions { flex-direction: column-reverse; }
    .form-action-cancel,
    .form-action-submit { width: 100%; justify-content: center; }
}
</style>
@endpush


{{-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ --}}
@push('scripts')
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/* ──────────────────────────────────────
   LOGO PREVIEW
────────────────────────────────────── */
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('logoPreview').innerHTML =
                `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:18px;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ──────────────────────────────────────
   CHAR COUNT
────────────────────────────────────── */
const descTA = document.getElementById('description');
const charCount = document.getElementById('charCount');
function updateChar() {
    charCount.textContent = descTA.value.length;
}
descTA.addEventListener('input', updateChar);
updateChar();

/* ──────────────────────────────────────
   GPS DETECT
────────────────────────────────────── */
function detectLocation() {
    if (!navigator.geolocation) return alert('Browser tidak mendukung geolokasi.');
    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);
            setCoords(lat, lng, true);
        },
        () => alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.')
    );
}

/* ──────────────────────────────────────
   MAP INIT
────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {

    // Nilai awal dari field (jika edit)
    const initLat  = parseFloat('{{ old('latitude', $team?->latitude ?? '') }}') || -2.5;
    const initLng  = parseFloat('{{ old('longitude', $team?->longitude ?? '') }}') || 118.0;
    const initZoom = ('{{ $team?->latitude }}' !== '') ? 13 : 5;

    const map = L.map('teamMap', {
        center: [initLat, initLng],
        zoom: initZoom,
        zoomControl: false,
    });

    // Tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    // Zoom control posisi kanan atas
    L.control.zoom({ position: 'topright' }).addTo(map);

    // Custom marker icon
    const markerIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:36px;height:36px;
            border-radius:50% 50% 50% 0;
            background:var(--accent,#a3b14b);
            border:3px solid #fff;
            box-shadow:0 4px 14px rgba(0,0,0,.3);
            transform:rotate(-45deg);
            display:flex;align-items:center;justify-content:center;
        "><span style="transform:rotate(45deg);font-size:14px;margin-top:2px;">📍</span></div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -40],
    });

    let marker = null;

    // Jika ada koordinat awal, pasang marker
    if ('{{ $team?->latitude }}' !== '') {
        marker = L.marker([initLat, initLng], { icon: markerIcon }).addTo(map);
        showCoordBadge(initLat, initLng);
        document.getElementById('mapHint').style.opacity = '0';
    }

    // Klik pada peta
    map.on('click', function (e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        placeMarker(lat, lng);
    });

    function placeMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
        }
        map.panTo([lat, lng]);
        setCoords(lat, lng, false);
        showCoordBadge(lat, lng);
        document.getElementById('mapHint').style.opacity = '0';
    }

    function showCoordBadge(lat, lng) {
        const badge = document.getElementById('mapCoordBadge');
        badge.style.display = 'flex';
        document.getElementById('mapCoordText').textContent = `${lat}, ${lng}`;
    }

    /* ── Search fungsionalitas ── */
    const searchInput  = document.getElementById('mapSearchInput');
    const searchBtn    = document.getElementById('mapSearchBtn');
    const searchResults = document.getElementById('mapSearchResults');

    let searchTimeout = null;

    function doSearch(query) {
        if (!query.trim()) return;
        searchResults.style.display = 'block';
        searchResults.innerHTML = '<div class="map-search-loading"><i class="bi bi-hourglass-split"></i> Mencari...</div>';

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=7&addressdetails=1`, {
            headers: { 'Accept-Language': 'id,en' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                searchResults.innerHTML = '<div class="map-search-empty">Tidak ada hasil ditemukan.</div>';
                return;
            }
            searchResults.innerHTML = data.map(item => `
                <div class="map-search-item" data-lat="${item.lat}" data-lon="${item.lon}">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>${item.display_name}</span>
                </div>
            `).join('');

            // Bind klik hasil
            searchResults.querySelectorAll('.map-search-item').forEach(el => {
                el.addEventListener('click', function () {
                    const lat = parseFloat(this.dataset.lat).toFixed(6);
                    const lng = parseFloat(this.dataset.lon).toFixed(6);
                    placeMarker(lat, lng);
                    map.setView([lat, lng], 15);
                    searchResults.style.display = 'none';
                    searchInput.value = this.querySelector('span').textContent;
                });
            });
        })
        .catch(() => {
            searchResults.innerHTML = '<div class="map-search-empty">Gagal mengambil hasil. Coba lagi.</div>';
        });
    }

    searchBtn.addEventListener('click', () => doSearch(searchInput.value));
    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(searchInput.value); }
    });

    // Autocomplete dengan debounce
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        const q = searchInput.value.trim();
        if (q.length < 3) { searchResults.style.display = 'none'; return; }
        searchTimeout = setTimeout(() => doSearch(q), 400);
    });

    // Tutup dropdown klik luar
    document.addEventListener('click', e => {
        if (!e.target.closest('.map-search-bar')) {
            searchResults.style.display = 'none';
        }
    });

    // Expose placeMarker untuk GPS
    window._mapPlaceMarker = placeMarker;
});

/* ──────────────────────────────────────
   SET KOORDINAT FIELDS
────────────────────────────────────── */
function setCoords(lat, lng, panMap) {
    document.getElementById('latitude').value  = lat;
    document.getElementById('longitude').value = lng;
    if (panMap && window._mapPlaceMarker) {
        window._mapPlaceMarker(lat, lng);
    }
}
</script>
@endpush