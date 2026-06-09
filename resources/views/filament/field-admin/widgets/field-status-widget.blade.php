{{-- resources/views/filament/field-admin/widgets/field-status-widget.blade.php --}}

<x-filament-widgets::widget>
<style>
/* ═══ FIELD STATUS WIDGET ═══ */
.fsw-wrap {
    background: var(--surface-1);
    border: 1px solid var(--border-subtle);
    border-radius: 18px;
    padding: 1.25rem 1.35rem;
    box-shadow: var(--card-shadow);
    transition: border-color .2s;
}
.fsw-wrap:hover { border-color: var(--border-medium); }

/* Header */
.fsw-header {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem; padding-bottom: .75rem;
    border-bottom: 1px solid var(--border-subtle);
    gap: 10px; flex-wrap: wrap;
}
.fsw-title {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: .9rem; font-weight: 700;
    color: var(--txt-primary); margin: 0;
}
.fsw-title svg { color: var(--accent-current); }
.fsw-hint { font-size: .72rem; color: var(--txt-muted); }

/* Grid */
.fsw-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}
@media(max-width:640px){ .fsw-grid { grid-template-columns: 1fr; } }

/* Card */
.fsw-card {
    display: flex; align-items: center;
    justify-content: space-between; gap: 10px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 13px; padding: 12px 14px;
    transition: border-color .15s, background .15s, transform .15s;
    position: relative; overflow: hidden;
}
.fsw-card::before {
    content: ''; position: absolute;
    left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--accent-current); opacity: 0;
    transition: opacity .15s;
}
.fsw-card:hover {
    border-color: var(--accent-border);
    background: var(--surface-3);
    transform: translateY(-1px);
}
.fsw-card:hover::before { opacity: 1; }

.fsw-card-name {
    font-family: 'Manrope', sans-serif;
    font-size: .875rem; font-weight: 700;
    color: var(--txt-primary); margin-bottom: 2px;
}
.fsw-card-type { font-size: .72rem; color: var(--txt-muted); }

.fsw-card-right {
    display: flex; align-items: center;
    gap: 10px; flex-shrink: 0;
}

/* Status badges */
.fsw-badge {
    font-size: .65rem; font-weight: 600;
    padding: 2px 9px; border-radius: 99px;
    display: inline-flex; align-items: center; gap: 4px;
}
.fsw-badge::before {
    content: ''; width: 5px; height: 5px;
    background: currentColor; border-radius: 50%;
}
.fsw-badge--active {
    background: rgba(16,185,129,.12);
    color: #059669;
    border: 1px solid rgba(16,185,129,.22);
}
.fsw-badge--inactive {
    background: rgba(239,68,68,.10);
    color: #dc2626;
    border: 1px solid rgba(239,68,68,.18);
}
html.dark .fsw-badge--active {
    background: rgba(16,185,129,.18);
    color: #6ee7b7;
    border-color: rgba(16,185,129,.30);
}
html.dark .fsw-badge--inactive {
    background: rgba(239,68,68,.15);
    color: #fca5a5;
    border-color: rgba(239,68,68,.22);
}

/* Toggle switch */
.fsw-toggle {
    position: relative; display: inline-block;
    width: 44px; height: 24px; flex-shrink: 0; cursor: pointer;
}
.fsw-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.fsw-toggle-track {
    position: absolute; inset: 0;
    border-radius: 12px; background: var(--surface-4);
    border: 1px solid var(--border-medium);
    transition: background .2s;
}
.fsw-toggle input:checked ~ .fsw-toggle-track {
    background: var(--accent-current);
    border-color: var(--accent-current);
}
.fsw-toggle-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 16px; height: 16px; border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
    transition: left .2s;
}
.fsw-toggle input:checked ~ .fsw-toggle-thumb { left: 23px; }

/* Empty state */
.fsw-empty {
    grid-column: 1 / -1;
    text-align: center; padding: 2rem 1rem;
    background: var(--surface-2);
    border: 1px dashed var(--border-medium);
    border-radius: 13px;
    font-size: .83rem; color: var(--txt-muted);
}
.fsw-empty svg { margin: 0 auto .5rem; display: block; opacity: .35; color: var(--txt-faint); }
.fsw-empty-title {
    font-family: 'Manrope', sans-serif;
    font-size: .875rem; font-weight: 700;
    color: var(--txt-secondary); margin-bottom: 4px;
}
</style>

<div class="fsw-wrap">

    <div class="fsw-header">
        <h2 class="fsw-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
            </svg>
            Lapangan Saya
        </h2>
        <span class="fsw-hint">Klik toggle untuk buka/tutup cepat</span>
    </div>

    <div class="fsw-grid">
        @forelse($fields as $field)
        <div class="fsw-card">
            <div>
                <div class="fsw-card-name">{{ $field->name }}</div>
                <div class="fsw-card-type">{{ $field->type ?? $field->venue?->name ?? '—' }}</div>
            </div>
            <div class="fsw-card-right">
                <span class="fsw-badge {{ $field->status === 'active' ? 'fsw-badge--active' : 'fsw-badge--inactive' }}">
                    {{ $field->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                </span>
                <label class="fsw-toggle" title="{{ $field->is_available ? 'Tutup sementara' : 'Buka kembali' }}">
                    <input type="checkbox"
                        {{ $field->is_available ? 'checked' : '' }}
                        wire:click="toggleAvailable({{ $field->id }})">
                    <span class="fsw-toggle-track"></span>
                    <span class="fsw-toggle-thumb"></span>
                </label>
            </div>
        </div>
        @empty
        <div class="fsw-empty">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
            </svg>
            <div class="fsw-empty-title">Belum ada lapangan</div>
            <div>Tambahkan lapangan melalui menu Lapangan.</div>
        </div>
        @endforelse
    </div>

</div>
</x-filament-widgets::widget>