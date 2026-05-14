<x-filament-widgets::widget>
<style>
    .sa-hero {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        background: var(--mg-surface-2, var(--surface-2, #F8F8F4));
        border: 1px solid var(--mg-border-subtle, var(--border-subtle, rgba(0,0,0,.07)));
        box-shadow: var(--card-shadow, 0 4px 16px rgba(0,0,0,.08));
    }

    .sa-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(
            ellipse at top left,
            var(--mg-accent-dim, var(--accent-dim, rgba(163,177,75,.12))) 0%,
            transparent 65%
        );
        pointer-events: none;
    }

    .sa-hero-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(var(--mg-border-subtle, rgba(0,0,0,.07)) 1px, transparent 1px),
            linear-gradient(90deg, var(--mg-border-subtle, rgba(0,0,0,.07)) 1px, transparent 1px);
        background-size: 32px 32px;
        opacity: .28;
        pointer-events: none;
    }

    .sa-circle-1 {
        position: absolute;
        top: -80px;
        right: -70px;
        width: 250px;
        height: 250px;
        border-radius: 999px;
        background: var(--mg-accent-dim, var(--accent-dim, rgba(163,177,75,.12)));
        opacity: .7;
    }

    .sa-circle-2 {
        position: absolute;
        bottom: -90px;
        right: 130px;
        width: 170px;
        height: 170px;
        border-radius: 999px;
        background: var(--mg-accent-dim, var(--accent-dim, rgba(163,177,75,.12)));
        opacity: .35;
    }

    .sa-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .sa-left {
        flex: 1;
        min-width: 240px;
    }

    .sa-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 4px 13px;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--mg-accent-current, var(--accent-current, #7A8C2E));
        background: var(--mg-accent-dim, var(--accent-dim, rgba(163,177,75,.12)));
        border: 1px solid var(--accent-border, rgba(163,177,75,.22));
        margin-bottom: .8rem;
    }

    .sa-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--mg-accent-current, var(--accent-current, #A3B14B));
    }

    .sa-title {
        font-family: 'Manrope', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -.02em;
        color: var(--mg-txt-primary, var(--txt-primary, #1A1A17));
        margin: 0;
    }

    .sa-title span {
        color: var(--mg-accent-current, var(--accent-current, #7A8C2E));
    }

    .sa-subtitle {
        margin-top: .45rem;
        font-size: .875rem;
        color: var(--mg-txt-muted, var(--txt-muted, #6E6E64));
    }

    .sa-actions {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        margin-top: 1.2rem;
    }

    .sa-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: .75rem;
        font-weight: 700;
        text-decoration: none;
        transition: .15s ease;
    }

    .sa-btn:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .sa-btn-primary {
        color: var(--mg-accent-current, var(--accent-current, #7A8C2E));
        background: var(--mg-accent-dim, var(--accent-dim, rgba(163,177,75,.12)));
        border: 1px solid var(--accent-border, rgba(163,177,75,.22));
    }

    .sa-btn-secondary {
        color: var(--mg-txt-secondary, var(--txt-secondary, #4A4A42));
        background: var(--mg-surface-3, var(--surface-3, #F1F1EB));
        border: 1px solid var(--mg-border-medium, var(--border-medium, rgba(0,0,0,.11)));
    }

    .sa-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .65rem;
        min-width: 360px;
    }

    .sa-stat {
        background: var(--mg-surface-1, var(--surface-1, #fff));
        border: 1px solid var(--mg-border-subtle, var(--border-subtle, rgba(0,0,0,.07)));
        border-radius: 14px;
        padding: .9rem 1rem;
        transition: .15s ease;
    }

    .sa-stat:hover {
        transform: translateY(-2px);
        border-color: var(--accent-border, rgba(163,177,75,.22));
    }

    .sa-stat-icon {
        color: var(--mg-accent-current, var(--accent-current, #7A8C2E));
        margin-bottom: .45rem;
    }

    .sa-stat-value {
        font-family: 'Manrope', sans-serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--mg-txt-primary, var(--txt-primary, #1A1A17));
        line-height: 1;
    }

    .sa-stat-label {
        margin-top: .35rem;
        font-size: .7rem;
        color: var(--mg-txt-muted, var(--txt-muted, #6E6E64));
        font-weight: 600;
    }

    @media(max-width: 900px) {
        .sa-hero {
            padding: 1.35rem;
        }

        .sa-stats {
            min-width: unset;
            width: 100%;
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 640px) {
        .sa-stats {
            grid-template-columns: 1fr;
        }

        .sa-title {
            font-size: 1.25rem;
        }
    }
</style>

<div class="sa-hero">
    <div class="sa-hero-grid"></div>
    <div class="sa-circle-1"></div>
    <div class="sa-circle-2"></div>

    <div class="sa-hero-inner">
        <div class="sa-left">
            <div class="sa-badge">
                <span class="sa-badge-dot"></span>
                Super Admin Panel
            </div>

            <h2 class="sa-title">
                Selamat datang, <span>{{ $userName }}</span>!
            </h2>

            <p class="sa-subtitle">
                Pantau seluruh aktivitas MATCHGO mulai dari user, tim, venue, booking, hingga verifikasi sistem.
            </p>

            <div class="sa-actions">
                <a href="{{ filament()->getUrl() }}/users" class="sa-btn sa-btn-primary">
                    Kelola User
                </a>

                <a href="{{ filament()->getUrl() }}/venues" class="sa-btn sa-btn-secondary">
                    Kelola Venue
                </a>

                <a href="{{ filament()->getUrl() }}/bookings" class="sa-btn sa-btn-secondary">
                    Lihat Booking
                </a>
            </div>
        </div>

        <div class="sa-stats">
            <div class="sa-stat">
                <div class="sa-stat-icon">👥</div>
                <div class="sa-stat-value">{{ $totalUsers }}</div>
                <div class="sa-stat-label">Total User</div>
            </div>

            <div class="sa-stat">
                <div class="sa-stat-icon">🛡️</div>
                <div class="sa-stat-value">{{ $totalTeams }}</div>
                <div class="sa-stat-label">Total Tim</div>
            </div>

            <div class="sa-stat">
                <div class="sa-stat-icon">📍</div>
                <div class="sa-stat-value">{{ $totalVenues }}</div>
                <div class="sa-stat-label">Total Venue</div>
            </div>

            <div class="sa-stat">
                <div class="sa-stat-icon">📅</div>
                <div class="sa-stat-value">{{ $totalBookings }}</div>
                <div class="sa-stat-label">Total Booking</div>
            </div>

            <div class="sa-stat">
                <div class="sa-stat-icon">⏳</div>
                <div class="sa-stat-value">{{ $pendingBookings }}</div>
                <div class="sa-stat-label">Booking Pending</div>
            </div>

            <div class="sa-stat">
                <div class="sa-stat-icon">✅</div>
                <div class="sa-stat-value">{{ $completedBookings }}</div>
                <div class="sa-stat-label">Booking Selesai</div>
            </div>
        </div>
    </div>
</div>
</x-filament-widgets::widget>