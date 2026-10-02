<?php $brandClass = $brandClass ?? 'brand'; ?>
<a class="<?= esc($brandClass) ?>" href="<?= site_url('/') ?>" aria-label="Rally Supply home">
    <svg class="brand-mark" viewBox="0 0 44 44" aria-hidden="true">
        <rect x="1" y="1" width="42" height="42" rx="12" fill="currentColor" />
        <rect x="8" y="8" width="28" height="28" rx="3" fill="none" stroke="#f5f2ea" stroke-width="1.2" />
        <path d="M22 8v28M8 22h28" stroke="#f5f2ea" stroke-width="1" opacity=".78" />
        <circle cx="22" cy="22" r="5" fill="#d8c38f" />
        <circle cx="20.4" cy="20.5" r=".7" fill="#1b2c24" />
        <circle cx="23.7" cy="21.2" r=".7" fill="#1b2c24" />
        <circle cx="21.7" cy="24" r=".7" fill="#1b2c24" />
    </svg>
    <span class="brand-name"><span>RALLY</span><span class="brand-light">SUPPLY</span></span>
</a>
