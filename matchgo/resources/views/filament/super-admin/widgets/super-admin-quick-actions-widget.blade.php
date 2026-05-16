<x-filament-widgets::widget>
<style>
/* ═══ SUPER ADMIN QUICK ACTIONS — MATCH FIELD ADMIN STYLE ═══ */
.saq-wrap {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.saq-card {
    background: var(--surface-1);
    border: 1px solid var(--border-subtle);
    border-radius: 18px;
    padding: 1.25rem 1.35rem;
    box-shadow: var(--card-shadow);
    transition: border-color .2s;
}

.saq-card:hover {
    border-color: var(--border-medium);
}

.saq-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--border-subtle);
}

.saq-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: .95rem;
    font-weight: 800;
    color: var(--txt-primary);
    margin: 0;
}

.saq-title svg {
    color: var(--accent-current);
}

.saq-subtitle {
    font-size: .74rem;
    color: var(--txt-muted);
    margin-top: 2px;
}

.saq-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .11em;
    color: var(--accent-current);
    background: var(--accent-dim);
    border: 1px solid var(--accent-border);
    border-radius: 99px;
    padding: 4px 11px;
    white-space: nowrap;
}

.saq-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 99px;
    background: var(--accent-current);
}

.saq-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .75rem;
}

.saq-item {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 1rem;
    border-radius: 14px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    text-decoration: none;
    transition: border-color .16s, background .16s, transform .16s;
}

.saq-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--accent-current);
    opacity: 0;
    transition: opacity .16s;
}

.saq-item:hover {
    transform: translateY(-2px);
    background: var(--surface-3);
    border-color: var(--accent-border);
    text-decoration: none;
}

.saq-item:hover::before {
    opacity: 1;
}

.saq-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: var(--accent-dim);
    border: 1px solid var(--accent-border);
    color: var(--accent-current);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.saq-icon--green {
    background: rgba(16,185,129,.08);
    border-color: rgba(16,185,129,.18);
    color: #059669;
}

.saq-icon--yellow {
    background: rgba(245,158,11,.08);
    border-color: rgba(245,158,11,.18);
    color: #d97706;
}

.saq-icon--blue {
    background: rgba(96,165,250,.08);
    border-color: rgba(96,165,250,.18);
    color: #3b82f6;
}

.saq-icon--orange {
    background: rgba(251,146,60,.08);
    border-color: rgba(251,146,60,.16);
    color: #ea580c;
}

.saq-body {
    min-width: 0;
}

.saq-name {
    font-family: 'Manrope', sans-serif;
    font-size: .86rem;
    font-weight: 800;
    color: var(--txt-primary);
    margin-bottom: 3px;
}

.saq-desc {
    font-size: .72rem;
    color: var(--txt-muted);
    line-height: 1.45;
}

.saq-arrow {
    margin-left: auto;
    color: var(--txt-faint);
    opacity: .65;
    transition: transform .16s, color .16s, opacity .16s;
    flex-shrink: 0;
}

.saq-item:hover .saq-arrow {
    transform: translateX(2px);
    color: var(--accent-current);
    opacity: 1;
}

.saq-tip {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    background: var(--surface-2);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    padding: 11px 15px;
    font-size: .78rem;
    color: var(--txt-secondary);
    line-height: 1.55;
}

.saq-tip svg {
    flex-shrink: 0;
    margin-top: 1px;
    color: var(--accent-current);
}

.saq-tip strong {
    color: var(--txt-primary);
}

.saq-tip em {
    color: var(--accent-current);
    font-style: normal;
    font-weight: 600;
}

/* Dark mode */
html.dark .saq-card,
.dark .saq-card {
    background: var(--surface-1);
    border-color: var(--border-subtle);
}

html.dark .saq-item,
.dark .saq-item {
    background: var(--surface-2);
    border-color: var(--border-subtle);
}

html.dark .saq-item:hover,
.dark .saq-item:hover {
    background: var(--surface-3);
    border-color: var(--accent-border);
}

html.dark .saq-tip,
.dark .saq-tip {
    background: var(--surface-2);
    border-color: var(--border-subtle);
}

/* Responsive */
@media(max-width: 1100px) {
    .saq-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 640px) {
    .saq-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .saq-grid {
        grid-template-columns: 1fr;
    }

    .saq-item {
        padding: .9rem;
    }
}
</style>

<div class="saq-wrap">

    <div class="saq-card">
        <div class="saq-header">
            <div>
                <h2 class="saq-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"/>
                    </svg>
                    Aksi Cepat
                </h2>
                <div class="saq-subtitle">
                    Shortcut untuk mengelola data utama sistem MATCHGO.
                </div>
            </div>

            <div class="saq-badge">
                <span class="saq-badge-dot"></span>
                Super Admin
            </div>
        </div>

        <div class="saq-grid">
            <a href="{{ filament()->getUrl() }}/users" class="saq-item">
                <div class="saq-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>

                <div class="saq-body">
                    <div class="saq-name">User Management</div>
                    <div class="saq-desc">Kelola akun pengguna, role, dan akses sistem.</div>
                </div>

                <div class="saq-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </a>

            <a href="{{ filament()->getUrl() }}/teams" class="saq-item">
                <div class="saq-icon saq-icon--blue">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                    </svg>
                </div>

                <div class="saq-body">
                    <div class="saq-name">Team Management</div>
                    <div class="saq-desc">Pantau data tim, member, statistik, dan status ban.</div>
                </div>

                <div class="saq-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </a>

            <a href="{{ filament()->getUrl() }}/venues" class="saq-item">
                <div class="saq-icon saq-icon--green">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>

                <div class="saq-body">
                    <div class="saq-name">Venue Management</div>
                    <div class="saq-desc">Kelola venue, lapangan, lokasi, dan admin lapangan.</div>
                </div>

                <div class="saq-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </a>

            <a href="{{ filament()->getUrl() }}/bookings" class="saq-item">
                <div class="saq-icon saq-icon--yellow">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>

                <div class="saq-body">
                    <div class="saq-name">Booking Management</div>
                    <div class="saq-desc">Pantau booking lapangan dan status pemesanan.</div>
                </div>

                <div class="saq-arrow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <div class="saq-tip">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>
            <strong>Tips:</strong>
            gunakan <em>Team Management</em> untuk memantau status tim dan fitur ban,
            lalu gunakan <em>Booking Management</em> untuk mengecek jadwal dan pemesanan.
        </span>
    </div>

</div>
</x-filament-widgets::widget>