<footer style="
    padding: 1.5rem 0;
    border-top: 1px solid var(--border-subtle);
    margin-top: 2rem;
">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">

        {{-- Brand --}}
        <a href="{{ url('/') }}" style="
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        ">
            <div style="
                width: 26px; height: 26px;
                border-radius: 7px;
                background: var(--accent-dim);
                display: flex; align-items: center; justify-content: center;
                font-family: 'Manrope', sans-serif;
                font-weight: 800;
                color: var(--accent);
                font-size: 0.75rem;
            ">M</div>
            <span style="
                font-family: 'Manrope', sans-serif;
                font-weight: 700;
                font-size: 0.875rem;
                color: var(--txt-primary);
            ">MatchGo</span>
        </a>

        {{-- Copyright --}}
        <p style="color:var(--txt-faint);font-size:0.775rem;margin:0;">
            &copy; {{ date('Y') }} MATCHGO. All rights reserved.
        </p>

        {{-- Sosmed --}}
        <div style="display:flex;gap:8px;">
            <a href="#" class="mg-icon-btn" style="width:30px;height:30px;border-radius:8px;" aria-label="Instagram">
                <i class="bi bi-instagram" style="font-size:0.85rem;"></i>
            </a>
            <a href="#" class="mg-icon-btn" style="width:30px;height:30px;border-radius:8px;" aria-label="Twitter">
                <i class="bi bi-twitter-x" style="font-size:0.85rem;"></i>
            </a>
            <a href="#" class="mg-icon-btn" style="width:30px;height:30px;border-radius:8px;" aria-label="TikTok">
                <i class="bi bi-tiktok" style="font-size:0.85rem;"></i>
            </a>
        </div>

    </div>
</footer>