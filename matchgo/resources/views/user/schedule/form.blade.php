<div class="space-y-6">

    {{-- Day of Week --}}
    <div class="form-group-mg">
        <label for="day_of_week" class="form-label-mg">
            Hari
        </label>
        <select
            name="day_of_week"
            id="day_of_week"
            class="form-control-mg @error('day_of_week') is-invalid @enderror"
        >
            <option value="" disabled {{ old('day_of_week', $schedule->day_of_week ?? '') === '' ? 'selected' : '' }}>
                — Pilih Hari —
            </option>
            @foreach ([0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'] as $value => $label)
                <option value="{{ $value }}"
                    {{ (string) old('day_of_week', $schedule->day_of_week ?? '') === (string) $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('day_of_week')
            <small class="text-danger-mg mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    {{-- Time Range --}}
    <div class="row g-3">

        {{-- Start Time --}}
        <div class="col-md-6">
            <div class="form-group-mg">
                <label for="start_time" class="form-label-mg">
                    Waktu Mulai
                </label>
                <input
                    type="time"
                    name="start_time"
                    id="start_time"
                    value="{{ old('start_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}"
                    class="form-control-mg @error('start_time') is-invalid @enderror"
                >

                @error('start_time')
                    <small class="text-danger-mg mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>
        </div>

        {{-- End Time --}}
        <div class="col-md-6">
            <div class="form-group-mg">
                <label for="end_time" class="form-label-mg">
                    Waktu Selesai
                </label>
                <input
                    type="time"
                    name="end_time"
                    id="end_time"
                    value="{{ old('end_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}"
                    class="form-control-mg @error('end_time') is-invalid @enderror"
                >

                @error('end_time')
                    <small class="text-danger-mg mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>
        </div>

    </div>

    {{-- Is Available --}}
    <div class="form-group-mg">
        <label class="form-label-mg">Status Ketersediaan</label>

        <label class="mg-toggle-label">
            <input type="hidden" name="is_available" value="0">

            <input
                type="checkbox"
                name="is_available"
                class="mg-toggle-input"
                value="1"
                {{ old('is_available', $schedule->is_available ?? true) ? 'checked' : '' }}
            >

            <div class="mg-toggle-track">
                <div class="mg-toggle-thumb"></div>
            </div>

            <span class="mg-toggle-text">
                Tim tersedia pada jadwal ini
            </span>
        </label>
    </div>

</div>