{{-- resources/views/user/schedule/form.blade.php --}}

<div class="space-y-0">

    {{-- ══════════════════════════════════════════
         SECTION 1 — HARI
    ══════════════════════════════════════════ --}}
    <div class="mg-section mb-4">

        <div class="mg-section-header">
            <div class="mg-section-num">01</div>
            <div>
                <h6 class="mg-section-title">Hari Jadwal</h6>
                <p class="mg-section-sub">Pilih hari dalam seminggu untuk ketersediaan tim Anda.</p>
            </div>
        </div>

        <div class="mg-section-body">

            {{-- Day Picker Grid --}}
            <div class="form-group-mg">
                <label class="form-label-mg">
                    Hari <span class="required-star">*</span>
                </label>

                {{-- Hidden select untuk value submission --}}
                <select name="day_of_week" id="day_of_week" style="display:none;">
                    <option value=""></option>
                    @foreach([0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'] as $value => $label)
                        <option value="{{ $value }}"
                            {{ (string) old('day_of_week', $schedule->day_of_week ?? '') === (string) $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <div class="day-picker-grid">
                    @foreach([0=>'Min',1=>'Sen',2=>'Sel',3=>'Rab',4=>'Kam',5=>'Jum',6=>'Sab'] as $value => $short)
                        @php $fullNames = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu']; @endphp
                        <button type="button"
                            class="day-pill {{ (string) old('day_of_week', $schedule->day_of_week ?? '') === (string) $value ? 'day-pill--active' : '' }}"
                            data-value="{{ $value }}"
                            data-full="{{ $fullNames[$value] }}">
                            <span class="day-pill-short">{{ $short }}</span>
                            <span class="day-pill-full">{{ $fullNames[$value] }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Label hari terpilih --}}
                <div class="day-selected-label" id="daySelectedLabel">
                    @php
                        $selectedDay = old('day_of_week', $schedule->day_of_week ?? '');
                        $dayNames = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
                    @endphp
                    @if($selectedDay !== '')
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ $dayNames[(int)$selectedDay] }} dipilih</span>
                    @else
                        <i class="bi bi-info-circle"></i>
                        <span>Belum ada hari yang dipilih</span>
                    @endif
                </div>

                @error('day_of_week')
                    <div class="field-error-mg">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SECTION 2 — WAKTU
    ══════════════════════════════════════════ --}}
    <div class="mg-section mb-4">

        <div class="mg-section-header">
            <div class="mg-section-num">02</div>
            <div>
                <h6 class="mg-section-title">Rentang Waktu</h6>
                <p class="mg-section-sub">Tentukan jam mulai dan selesai sesi latihan atau pertandingan.</p>
            </div>
        </div>

        <div class="mg-section-body">

            <div class="row g-4">

                {{-- Start Time --}}
                <div class="col-md-6">
                    <div class="form-group-mg">
                        <label for="start_time" class="form-label-mg">
                            Waktu Mulai <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-clock input-icon"></i>
                            <input
                                type="time"
                                name="start_time"
                                id="start_time"
                                value="{{ old('start_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}"
                                class="form-control-mg with-icon @error('start_time') is-invalid-mg @enderror"
                            >
                        </div>
                        @error('start_time')
                            <div class="field-error-mg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- End Time --}}
                <div class="col-md-6">
                    <div class="form-group-mg">
                        <label for="end_time" class="form-label-mg">
                            Waktu Selesai <span class="required-star">*</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-clock-history input-icon"></i>
                            <input
                                type="time"
                                name="end_time"
                                id="end_time"
                                value="{{ old('end_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}"
                                class="form-control-mg with-icon @error('end_time') is-invalid-mg @enderror"
                            >
                        </div>
                        @error('end_time')
                            <div class="field-error-mg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Duration preview --}}
            <div class="duration-preview" id="durationPreview" style="display:none;">
                <i class="bi bi-hourglass-split"></i>
                <span id="durationText">—</span>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SECTION 3 — STATUS
    ══════════════════════════════════════════ --}}
    <div class="mg-section mb-4">

        <div class="mg-section-header">
            <div class="mg-section-num">03</div>
            <div>
                <h6 class="mg-section-title">Status Ketersediaan</h6>
                <p class="mg-section-sub">Tandai apakah tim aktif tersedia pada jadwal ini.</p>
            </div>
        </div>

        <div class="mg-section-body">
            <div class="form-group-mg mb-0">

                <div class="availability-toggle-group">

                    {{-- Available --}}
                    <label class="availability-option">
                        <input type="radio" name="is_available" value="1"
                            {{ old('is_available', $schedule->is_available ?? true) ? 'checked' : '' }}>
                        <span class="availability-card availability-card--yes">
                            <span class="availability-card-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                            <span class="availability-card-body">
                                <strong>Tersedia</strong>
                                <span>Tim siap bertanding pada jadwal ini</span>
                            </span>
                        </span>
                    </label>

                    {{-- Not Available --}}
                    <label class="availability-option">
                        <input type="radio" name="is_available" value="0"
                            {{ !old('is_available', $schedule->is_available ?? true) ? 'checked' : '' }}>
                        <span class="availability-card availability-card--no">
                            <span class="availability-card-icon">
                                <i class="bi bi-x-circle-fill"></i>
                            </span>
                            <span class="availability-card-body">
                                <strong>Tidak Tersedia</strong>
                                <span>Tim tidak bisa ditemui pada jadwal ini</span>
                            </span>
                        </span>
                    </label>

                </div>

            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
/* ─────────────────────────────────────
   SECTION LAYOUT (reuse from team/form)
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
   REQUIRED STAR
───────────────────────────────────── */
.required-star {
    color: #f87171;
    margin-left: 2px;
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
   DAY PICKER
───────────────────────────────────── */
.day-picker-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    margin-bottom: 12px;
}

.day-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    padding: 10px 4px;
    border-radius: 12px;
    border: 1.5px solid var(--border-subtle);
    background: var(--surface-3);
    cursor: pointer;
    transition: all .18s;
    font-family: 'Manrope', sans-serif;
    color: var(--txt-muted);
    user-select: none;
}

.day-pill:hover {
    border-color: rgba(163,177,75,.45);
    background: var(--accent-dim);
    color: var(--accent);
    transform: translateY(-2px);
}

.day-pill--active {
    border-color: var(--accent) !important;
    background: var(--accent-dim) !important;
    color: var(--accent) !important;
    box-shadow: 0 0 0 3px rgba(163,177,75,.12), 0 4px 12px rgba(163,177,75,.18);
    transform: translateY(-2px);
}

.day-pill-short {
    font-size: .8rem;
    font-weight: 800;
    line-height: 1;
}

.day-pill-full {
    font-size: .6rem;
    font-weight: 600;
    opacity: .6;
    line-height: 1;
    display: none;
}

.day-selected-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .78rem;
    color: var(--txt-faint);
    padding: 8px 12px;
    background: var(--surface-3);
    border-radius: 9px;
    border: 1px solid var(--border-subtle);
    transition: all .2s;
}

.day-selected-label i {
    font-size: .85rem;
    flex-shrink: 0;
}

.day-selected-label.has-value {
    color: var(--accent);
    background: var(--accent-dim);
    border-color: rgba(163,177,75,.35);
}

/* ─────────────────────────────────────
   DURATION PREVIEW
───────────────────────────────────── */
.duration-preview {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: var(--accent-dim);
    border: 1px solid rgba(163,177,75,.3);
    border-radius: 10px;
    font-size: .8rem;
    color: var(--accent);
    font-weight: 600;
}

.duration-preview i {
    font-size: .9rem;
    flex-shrink: 0;
}

/* ─────────────────────────────────────
   AVAILABILITY TOGGLE
───────────────────────────────────── */
.availability-toggle-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.availability-option {
    cursor: pointer;
    display: block;
    margin: 0;
}

.availability-option input[type="radio"] {
    display: none;
}

.availability-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    border-radius: 14px;
    border: 1.5px solid var(--border-subtle);
    background: var(--surface-3);
    transition: all .18s;
    cursor: pointer;
}

.availability-card:hover {
    border-color: var(--border-medium);
    background: var(--surface-4);
}

.availability-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    background: var(--surface-4);
    transition: all .18s;
}

.availability-card-body {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.availability-card-body strong {
    font-size: .85rem;
    font-weight: 700;
    color: var(--txt-primary);
    font-family: 'Manrope', sans-serif;
    line-height: 1;
}

.availability-card-body span {
    font-size: .75rem;
    color: var(--txt-faint);
    line-height: 1.4;
}

/* Available (yes) selected */
.availability-option input:checked + .availability-card--yes {
    border-color: rgba(163,177,75,.6);
    background: var(--accent-dim);
    box-shadow: 0 0 0 3px rgba(163,177,75,.08);
}

.availability-option input:checked + .availability-card--yes .availability-card-icon {
    background: rgba(163,177,75,.18);
    color: var(--accent);
}

.availability-option input:checked + .availability-card--yes .availability-card-body strong {
    color: var(--accent);
}

/* Not available (no) selected */
.availability-option input:checked + .availability-card--no {
    border-color: rgba(239,68,68,.4);
    background: rgba(239,68,68,.05);
    box-shadow: 0 0 0 3px rgba(239,68,68,.06);
}

.availability-option input:checked + .availability-card--no .availability-card-icon {
    background: rgba(239,68,68,.1);
    color: #f87171;
}

.availability-option input:checked + .availability-card--no .availability-card-body strong {
    color: #f87171;
}

/* Default icon colors */
.availability-card--yes .availability-card-icon { color: rgba(163,177,75,.5); }
.availability-card--no .availability-card-icon  { color: rgba(239,68,68,.4); }

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
   RESPONSIVE
───────────────────────────────────── */
@media (max-width: 575px) {
    .day-picker-grid {
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }
    .day-pill {
        padding: 8px 2px;
        border-radius: 9px;
    }
    .day-pill-short {
        font-size: .7rem;
    }
    .mg-section-body { padding: 1.25rem; }
    .mg-section-header { padding: 1.1rem 1.25rem; }
    .availability-toggle-group { flex-direction: column; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Day Picker ── */
    const dayPills  = document.querySelectorAll('.day-pill');
    const daySelect = document.getElementById('day_of_week');
    const dayLabel  = document.getElementById('daySelectedLabel');

    dayPills.forEach(pill => {
        pill.addEventListener('click', function () {
            dayPills.forEach(p => p.classList.remove('day-pill--active'));
            this.classList.add('day-pill--active');
            daySelect.value = this.dataset.value;

            dayLabel.innerHTML = `<i class="bi bi-check-circle-fill"></i><span>${this.dataset.full} dipilih</span>`;
            dayLabel.classList.add('has-value');
        });
    });

    /* ── Duration Preview ── */
    const startInput    = document.getElementById('start_time');
    const endInput      = document.getElementById('end_time');
    const durationBox   = document.getElementById('durationPreview');
    const durationText  = document.getElementById('durationText');

    function updateDuration() {
        const s = startInput.value;
        const e = endInput.value;
        if (!s || !e) { durationBox.style.display = 'none'; return; }

        const [sh, sm] = s.split(':').map(Number);
        const [eh, em] = e.split(':').map(Number);
        let diff = (eh * 60 + em) - (sh * 60 + sm);

        if (diff <= 0) { durationBox.style.display = 'none'; return; }

        const hours = Math.floor(diff / 60);
        const mins  = diff % 60;
        let label = '';
        if (hours > 0) label += `${hours} jam `;
        if (mins  > 0) label += `${mins} menit`;

        durationText.textContent = `Durasi sesi: ${label.trim()}`;
        durationBox.style.display = 'flex';
    }

    startInput.addEventListener('change', updateDuration);
    endInput.addEventListener('change', updateDuration);
    updateDuration();

});
</script>
@endpush