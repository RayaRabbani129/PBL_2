{{-- resources/views/user/team/form.blade.php --}}

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    {{-- ── Card: Informasi Tim ── --}}
    <div class="card-matchgo mb-4">
        <h6 class="font-display font-semi mb-4" style="font-size:.875rem;color:var(--txt-secondary);text-transform:uppercase;letter-spacing:.07em;">
            Informasi Tim
        </h6>

        {{-- Logo --}}
        <div class="form-group-mg mb-4">
            <label class="form-label-mg">Logo Tim</label>
            <div class="d-flex align-items-center gap-3">
                <div id="logoPreview"
                     style="width:64px;height:64px;border-radius:14px;background:var(--accent-dim);
                            border:1px solid rgba(163,177,75,0.25);display:flex;align-items:center;
                            justify-content:center;font-size:28px;overflow:hidden;flex-shrink:0;">
                    @if($team?->logo_path)
                        <img src="{{ Storage::url($team->logo_path) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        ⚽
                    @endif
                </div>
                <div>
                    <label for="logo_path" class="btn-matchgo-outline" style="cursor:pointer;margin-bottom:0;">
                        <i class="bi b-iupload"></i> Pilih Foto
                    </label>
                    <input type="file" id="logo_path" name="logo_path" accept="image/*"
                           style="display:none;" onchange="previewLogo(this)">
                    <p style="font-size:.75rem;color:var(--txt-faint);margin-top:6px;margin-bottom:0;">
                        JPG, PNG maks. 2MB
                    </p>
                </div>
            </div>
            @error('logo_path')
                <div class="field-error-mg">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-4">
            {{-- Nama Tim --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="name" class="form-label-mg">Nama Tim <span style="color:#f87171">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control-mg @error('name') is-invalid-mg @enderror"
                           value="{{ old('name', $team?->name) }}"
                           placeholder="cth. FC Malang Raya" required>
                    @error('name')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Level Tim --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="level" class="form-label-mg">Level Tim <span style="color:#f87171">*</span></label>
                    <select id="level" name="level"
                            class="form-control-mg @error('level') is-invalid-mg @enderror" required>
                        <option value="" disabled {{ old('level', $team?->level) ? '' : 'selected' }}>-- Pilih Level --</option>
                        @foreach(['casual','semipro','Semi-Pro','Profesional'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('level', $team?->level) === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </select>
                    @error('level')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Kota --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="city" class="form-label-mg">Kota <span style="color:#f87171">*</span></label>
                    <input type="text" id="city" name="city"
                           class="form-control-mg @error('city') is-invalid-mg @enderror"
                           value="{{ old('city', $team?->city) }}"
                           placeholder="cth. Malang" required>
                    @error('city')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Provinsi --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="province" class="form-label-mg">Provinsi <span style="color:#f87171">*</span></label>
                    <input type="text" id="province" name="province"
                           class="form-control-mg @error('province') is-invalid-mg @enderror"
                           value="{{ old('province', $team?->province) }}"
                           placeholder="cth. Jawa Timur" required>
                    @error('province')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="col-12">
                <div class="form-group-mg">
                    <label for="description" class="form-label-mg">Deskripsi Tim</label>
                    <textarea id="description" name="description"
                              class="form-control-mg @error('description') is-invalid-mg @enderror"
                              placeholder="Ceritakan sedikit tentang tim Anda...">{{ old('description', $team?->description) }}</textarea>
                    @error('description')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="status" class="form-label-mg">Status Tim <span style="color:#f87171">*</span></label>
                    <select id="status" name="status"
                            class="form-control-mg @error('status') is-invalid-mg @enderror" required>
                        <option value="active"   {{ old('status', $team?->status ?? 'active') === 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $team?->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ── Card: Lokasi ── --}}
    <div class="card-matchgo mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="font-display font-semi mb-0" style="font-size:.875rem;color:var(--txt-secondary);text-transform:uppercase;letter-spacing:.07em;">
                Lokasi Lapangan
            </h6>
            <button type="button" onclick="detectLocation()" class="btn-matchgo-outline" style="padding:7px 14px;font-size:.775rem;border-radius:8px;">
                <i class="bi bi-geo-alt"></i> Deteksi Lokasi
            </button>
        </div>
        <p style="font-size:.8rem;color:var(--txt-faint);margin-bottom:1.25rem;">
            Opsional — digunakan untuk mencocokkan lawan terdekat.
        </p>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="latitude" class="form-label-mg">Latitude <span style="color:#f87171">*</span></label>
                    <input type="text" id="latitude" name="latitude"
                           class="form-control-mg @error('latitude') is-invalid-mg @enderror"
                           value="{{ old('latitude', $team?->latitude) }}"
                           placeholder="cth. -7.9839" required>
                    @error('latitude')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="longitude" class="form-label-mg">Longitude <span style="color:#f87171">*</span></label>
                    <input type="text" id="longitude" name="longitude"
                           class="form-control-mg @error('longitude') is-invalid-mg @enderror"
                           value="{{ old('longitude', $team?->longitude) }}"
                           placeholder="cth. 112.6212" required>
                    @error('longitude')<div class="field-error-mg">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('team.index') }}" class="btn-matchgo-outline">Batal</a>
        <button type="submit" class="btn-matchgo-primary">
            <i class="bi bi-check-lg"></i> {{ $btnText }}
        </button>
    </div>

</form>

@push('styles')
<style>
    .is-invalid-mg {
        border-color: rgba(239,68,68,0.55) !important;
        background-color: rgba(239,68,68,0.04) !important;
    }
    .is-invalid-mg:focus {
        box-shadow: 0 0 0 3px rgba(239,68,68,0.12) !important;
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
</style>
@endpush

@push('scripts')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('logoPreview').innerHTML =
                    `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function detectLocation() {
        if (!navigator.geolocation) return alert('Browser tidak mendukung geolokasi.');
        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById('latitude').value  = pos.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = pos.coords.longitude.toFixed(6);
            },
            () => alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.')
        );
    }
</script>
@endpush