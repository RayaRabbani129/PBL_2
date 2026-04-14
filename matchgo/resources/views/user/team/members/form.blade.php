{{-- resources/views/user/team/members/form.blade.php --}}

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    {{-- Card: Informasi Anggota --}}
    <div class="card-matchgo mb-4">
        <h6 class="font-display font-semi mb-4" style="font-size:.875rem; color:var(--txt-secondary); text-transform:uppercase; letter-spacing:.07em;">
            Informasi Anggota
        </h6>

        <div class="row g-4">

            {{-- Nama --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="name" class="form-label-mg">
                        Nama Anggota <span style="color:#f87171">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control-mg @error('name') is-invalid-mg @enderror"
                        value="{{ old('name', $member?->name) }}"
                        placeholder="cth. Budi Santoso"
                        required
                    >
                    @error('name')
                        <div class="field-error-mg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Role --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="role" class="form-label-mg">
                        Posisi / Role <span style="color:#f87171">*</span>
                    </label>
                    <input
                        type="text"
                        id="role"
                        name="role"
                        class="form-control-mg @error('role') is-invalid-mg @enderror"
                        value="{{ old('role', $member?->role) }}"
                        placeholder="cth. Striker, Goalkeeper, Penjaga Gawang"
                        required
                    >
                    @error('role')
                        <div class="field-error-mg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="col-lg-6">
                <div class="form-group-mg">
                    <label for="status" class="form-label-mg">
                        Status <span style="color:#f87171">*</span>
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="form-control-mg @error('status') is-invalid-mg @enderror"
                        required
                    >
                        <option value="" disabled {{ old('status', $member?->status) === null ? 'selected' : '' }}>-- Pilih Status --</option>
                        <option value="active"     {{ old('status', $member?->status) === 'active'     ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive"   {{ old('status', $member?->status) === 'inactive'   ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="substitute" {{ old('status', $member?->status) === 'substitute' ? 'selected' : '' }}>Cadangan</option>
                    </select>
                    @error('status')
                        <div class="field-error-mg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('team.index') }}" class="btn-matchgo-outline">
            Batal
        </a>
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