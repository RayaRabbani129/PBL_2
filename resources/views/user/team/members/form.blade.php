{{-- resources/views/user/team/members/form.blade.php --}}

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    {{-- ════════════════════════════════════════
         SECTION — INFORMASI ANGGOTA
    ════════════════════════════════════════ --}}
    <div class="mg-section mb-4">

        {{-- Section Header --}}
        <div class="mg-section-header">
            <div class="mg-section-num">01</div>
            <div>
                <h6 class="mg-section-title">Informasi Anggota</h6>
                <p class="mg-section-sub">
                    Kelola data pemain dan status keanggotaan tim.
                </p>
            </div>
        </div>

        {{-- Section Body --}}
        <div class="mg-section-body">

            <div class="row g-4">

                {{-- Nama --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label for="name" class="form-label-mg">
                            Nama Anggota
                            <span class="required-star">*</span>
                        </label>

                        <div class="input-icon-wrap">
                            <i class="bi bi-person-fill input-icon"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control-mg with-icon @error('name') is-invalid-mg @enderror"
                                value="{{ old('name', $member?->name) }}"
                                placeholder="cth. Budi Santoso"
                                required
                            >
                        </div>

                        @error('name')
                            <div class="field-error-mg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="col-lg-6">
                    <div class="form-group-mg">
                        <label for="role" class="form-label-mg">
                            Posisi / Role
                            <span class="required-star">*</span>
                        </label>

                        <div class="input-icon-wrap">
                            <i class="bi bi-shield-fill input-icon"></i>

                            <select
                                id="role"
                                name="role"
                                class="form-control-mg with-icon @error('role') is-invalid-mg @enderror"
                                required
                            >
                                <option value="" disabled {{ old('role', $member?->role) === null ? 'selected' : '' }}>
                                    -- Pilih Role --
                                </option>

                                <option value="player" {{ old('role', $member?->role) === 'player' ? 'selected' : '' }}>
                                    Player
                                </option>

                                <option value="captain" {{ old('role', $member?->role) === 'captain' ? 'selected' : '' }}>
                                    Captain
                                </option>

                                <option value="goalkeeper" {{ old('role', $member?->role) === 'goalkeeper' ? 'selected' : '' }}>
                                    Goalkeeper
                                </option>

                                <option value="defender" {{ old('role', $member?->role) === 'defender' ? 'selected' : '' }}>
                                    Defender
                                </option>

                                <option value="midfielder" {{ old('role', $member?->role) === 'midfielder' ? 'selected' : '' }}>
                                    Midfielder
                                </option>

                                <option value="striker" {{ old('role', $member?->role) === 'striker' ? 'selected' : '' }}>
                                    Striker
                                </option>
                            </select>
                        </div>

                        @error('role')
                            <div class="field-error-mg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-12">
                    <div class="form-group-mg">

                        <label class="form-label-mg">
                            Status Anggota
                            <span class="required-star">*</span>
                        </label>

                        <div class="status-toggle-group">

                            {{-- Aktif --}}
                            <label class="status-toggle-option">
                                <input
                                    type="radio"
                                    name="status"
                                    value="active"
                                    {{ old('status', $member?->status ?? 'active') === 'active' ? 'checked' : '' }}
                                >

                                <span class="status-toggle-card status-toggle-active">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Aktif</span>
                                </span>
                            </label>

                            {{-- Cadangan --}}
                            <label class="status-toggle-option">
                                <input
                                    type="radio"
                                    name="status"
                                    value="substitute"
                                    {{ old('status', $member?->status) === 'substitute' ? 'checked' : '' }}
                                >

                                <span class="status-toggle-card status-toggle-warning">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Cadangan</span>
                                </span>
                            </label>

                            {{-- Tidak Aktif --}}
                            <label class="status-toggle-option">
                                <input
                                    type="radio"
                                    name="status"
                                    value="inactive"
                                    {{ old('status', $member?->status) === 'inactive' ? 'checked' : '' }}
                                >

                                <span class="status-toggle-card status-toggle-inactive">
                                    <i class="bi bi-pause-circle-fill"></i>
                                    <span>Tidak Aktif</span>
                                </span>
                            </label>

                        </div>

                        @error('status')
                            <div class="field-error-mg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         ACTIONS
    ════════════════════════════════════════ --}}
    <div class="form-actions">

        <a href="{{ route('team.index') }}"
           class="btn-matchgo-outline form-action-cancel">
            <i class="bi bi-x-lg"></i> Batal
        </a>

        <button type="submit"
                class="btn-matchgo-primary form-action-submit">
            <i class="bi bi-check-lg"></i> {{ $btnText }}
        </button>

    </div>

</form>

@push('styles')
<style>

/* ─────────────────────────────────────
   SECTION LAYOUT
───────────────────────────────────── */
.mg-section {
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 20px;
    overflow: hidden;
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
   INPUT ICON
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
   REQUIRED
───────────────────────────────────── */
.required-star {
    color: #f87171;
    margin-left: 2px;
}

/* ─────────────────────────────────────
   STATUS TOGGLE
───────────────────────────────────── */
.status-toggle-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
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
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 14px;
    border: 1.5px solid var(--border-subtle);
    background: var(--surface-3);
    font-size: .82rem;
    font-weight: 600;
    color: var(--txt-muted);
    transition: all .18s;
}

.status-toggle-card:hover {
    transform: translateY(-1px);
}

/* Active */
.status-toggle-option input:checked + .status-toggle-active {
    border-color: rgba(163,177,75,.55);
    background: var(--accent-dim);
    color: var(--accent);
    box-shadow: 0 0 0 3px rgba(163,177,75,.08);
}

/* Substitute */
.status-toggle-option input:checked + .status-toggle-warning {
    border-color: rgba(245,158,11,.45);
    background: rgba(245,158,11,.08);
    color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245,158,11,.08);
}

/* Inactive */
.status-toggle-option input:checked + .status-toggle-inactive {
    border-color: rgba(239,68,68,.45);
    background: rgba(239,68,68,.08);
    color: #f87171;
    box-shadow: 0 0 0 3px rgba(239,68,68,.08);
}

/* ─────────────────────────────────────
   INVALID
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
   ACTIONS
───────────────────────────────────── */
.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    padding: 1rem 0 .5rem;
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

    .mg-section-body {
        padding: 1.25rem;
    }

    .mg-section-header {
        padding: 1.1rem 1.25rem;
    }

    .status-toggle-group {
        flex-direction: column;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-action-cancel,
    .form-action-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush