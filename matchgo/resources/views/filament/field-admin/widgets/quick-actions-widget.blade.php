{{-- resources/views/filament/field-admin/widgets/quick-actions-widget.blade.php --}}

<x-filament-widgets::widget>
<style>
/* ═══ QUICK ACTIONS WIDGET ═══ */
.qaw-wrap {
    background: var(--surface-1);
    border: 1px solid var(--border-subtle);
    border-radius: 18px;
    padding: 1.25rem 1.35rem;
    box-shadow: var(--card-shadow);
    transition: border-color .2s;
}
.qaw-wrap:hover { border-color: var(--border-medium); }

/* Header */
.qaw-header {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: .9rem; font-weight: 700;
    color: var(--txt-primary);
    margin-bottom: 1rem; padding-bottom: .75rem;
    border-bottom: 1px solid var(--border-subtle);
}
.qaw-header svg { color: var(--accent-current); }

/* Grid */
.qaw-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}
@media(max-width:480px){ .qaw-grid { grid-template-columns: 1fr; } }

/* Button */
.qaw-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 14px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    text-decoration: none;
    color: var(--txt-secondary);
    font-size: .82rem; font-weight: 600;
    font-family: 'Inter', sans-serif;
    transition: all .15s;
    position: relative; overflow: hidden;
}
.qaw-btn::before {
    content: ''; position: absolute;
    left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--accent-current); opacity: 0;
    transition: opacity .15s;
}
.qaw-btn:hover {
    border-color: var(--accent-border);
    color: var(--txt-primary);
    background: var(--surface-3);
    transform: translateY(-1px);
    text-decoration: none;
}
.qaw-btn:hover::before { opacity: 1; }

/* Icon circle */
.qaw-icon {
    width: 34px; height: 34px;
    border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--accent-dim);
    color: var(--accent-current);
    border: 1px solid var(--accent-border);
}

/* Arrow */
.qaw-arrow {
    margin-left: auto;
    font-size: .72rem;
    color: var(--txt-faint);
    flex-shrink: 0;
    transition: transform .15s;
}
.qaw-btn:hover .qaw-arrow {
    transform: translate(2px, -2px);
    color: var(--accent-current);
}
</style>

<div class="qaw-wrap">

    <div class="qaw-header">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
        </svg>
        Aksi Cepat
    </div>

    <div class="qaw-grid">
        @foreach($actions as $action)
        <a href="{{ $action['url'] }}" class="qaw-btn">
            <span class="qaw-icon">{!! $action['icon'] !!}</span>
            {{ $action['label'] }}
            <span class="qaw-arrow">↗</span>
        </a>
        @endforeach
    </div>

</div>
</x-filament-widgets::widget>