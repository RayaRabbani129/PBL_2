<x-filament-widgets::widget>
<style>
    .sa-quick-wrap {
        background: var(--mg-surface-1, var(--surface-1, #fff));
        border: 1px solid var(--mg-border-subtle, var(--border-subtle, rgba(0,0,0,.07)));
        border-radius: 18px;
        padding: 1.25rem 1.35rem;
        box-shadow: var(--card-shadow, 0 4px 16px rgba(0,0,0,.08));
    }

    .sa-quick-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid var(--mg-border-subtle, var(--border-subtle, rgba(0,0,0,.07)));
    }

    .sa-quick-title {
        font-family: 'Manrope', sans-serif;
        font-size: .95rem;
        font-weight: 800;
        color: var(--mg-txt-primary, var(--txt-primary, #1A1A17));
        margin: 0;
    }

    .sa-quick-subtitle {
        font-size: .75rem;
        color: var(--mg-txt-muted, var(--txt-muted, #6E6E64));
    }

    .sa-quick-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .75rem;
    }

    .sa-quick-card {
        display: flex;
        flex-direction: column;
        gap: .45rem;
        padding: 1rem;
        border-radius: 14px;
        background: var(--mg-surface-2, var(--surface-2, #F8F8F4));
        border: 1px solid var(--mg-border-subtle, var(--border-subtle, rgba(0,0,0,.07)));
        text-decoration: none;
        transition: .15s ease;
    }

    .sa-quick-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent-border, rgba(163,177,75,.22));
        background: var(--mg-accent-dim, var(--accent-dim, rgba(163,177,75,.12)));
        text-decoration: none;
    }

    .sa-quick-icon {
        font-size: 1.25rem;
    }

    .sa-quick-name {
        font-family: 'Manrope', sans-serif;
        font-size: .85rem;
        font-weight: 800;
        color: var(--mg-txt-primary, var(--txt-primary, #1A1A17));
    }

    .sa-quick-desc {
        font-size: .72rem;
        color: var(--mg-txt-muted, var(--txt-muted, #6E6E64));
        line-height: 1.45;
    }

    @media(max-width: 900px) {
        .sa-quick-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 640px) {
        .sa-quick-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="sa-quick-wrap">
    <div class="sa-quick-header">
        <div>
            <h2 class="sa-quick-title">Aksi Cepat</h2>
            <div class="sa-quick-subtitle">Shortcut untuk mengelola data utama sistem.</div>
        </div>
    </div>

    <div class="sa-quick-grid">
        <a href="{{ filament()->getUrl() }}/users" class="sa-quick-card">
            <div class="sa-quick-icon">👥</div>
            <div class="sa-quick-name">User Management</div>
            <div class="sa-quick-desc">Kelola akun pengguna, role, dan akses sistem.</div>
        </a>

        <a href="{{ filament()->getUrl() }}/teams" class="sa-quick-card">
            <div class="sa-quick-icon">🛡️</div>
            <div class="sa-quick-name">Team Management</div>
            <div class="sa-quick-desc">Pantau data tim, member, dan statistik tim.</div>
        </a>

        <a href="{{ filament()->getUrl() }}/venues" class="sa-quick-card">
            <div class="sa-quick-icon">📍</div>
            <div class="sa-quick-name">Venue Management</div>
            <div class="sa-quick-desc">Kelola venue, lapangan, dan informasi lokasi.</div>
        </a>

        <a href="{{ filament()->getUrl() }}/bookings" class="sa-quick-card">
            <div class="sa-quick-icon">📅</div>
            <div class="sa-quick-name">Booking Management</div>
            <div class="sa-quick-desc">Pantau booking lapangan dan status pemesanan.</div>
        </a>
    </div>
</div>
</x-filament-widgets::widget>