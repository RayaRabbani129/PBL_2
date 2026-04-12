    <footer style="padding: 2rem 0; border-top: 1px solid var(--card-border, rgba(255,255,255,0.06)); margin-top: 3rem;">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <a class="navbar-brand-custom" href="{{ url('/') }}" style="font-size: 1.1rem;">
                    <span class="brand-icon" style="width:24px; height:24px; font-size:0.8rem;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </span>
                    MATCH<span class="brand-accent">GO</span>
                </a>
                <p style="color: var(--text-muted, #64748b); font-size: 0.82rem; margin: 0;">
                    &copy; {{ date('Y') }} MATCHGO. All rights reserved.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" style="color: var(--text-muted, #64748b); font-size: 1rem; transition: color 0.2s;"
                       onmouseover="this.style.color='var(--lime, #a3e635)'"
                       onmouseout="this.style.color='var(--text-muted, #64748b)'">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" style="color: var(--text-muted, #64748b); font-size: 1rem; transition: color 0.2s;"
                       onmouseover="this.style.color='var(--lime, #a3e635)'"
                       onmouseout="this.style.color='var(--text-muted, #64748b)'">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" style="color: var(--text-muted, #64748b); font-size: 1rem; transition: color 0.2s;"
                       onmouseover="this.style.color='var(--lime, #a3e635)'"
                       onmouseout="this.style.color='var(--text-muted, #64748b)'">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>