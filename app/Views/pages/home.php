<?= view('partials/header', ['title' => $title]) ?>

<section class="hero wrap">
    <div class="hero-copy">
        <p class="eyebrow"><span class="eyebrow-line"></span> THE PICKLEBALL SHOP FOR EVERY PLAYER</p>
        <h1>Find your<br>next <em>game.</em></h1>
        <p class="hero-description">Paddles, court shoes, and everything between points. Explore a considered collection built for how you play.</p>
        <div class="hero-actions">
            <a class="button button-dark" href="<?= site_url('shop') ?>">Shop pickleball gear <span>↗</span></a>
            <a class="text-link" href="<?= site_url('about') ?>">Meet Rally Supply <span>→</span></a>
        </div>
        <div class="hero-proof">
            <div class="avatar-stack"><i>MR</i><i>AS</i><i>BC</i></div>
            <p><strong>Made for your kind of game</strong><br><span>From first serve to game point</span></p>
        </div>
    </div>
    <div class="hero-art" aria-label="Illustration of a pickleball paddle and court ball">
        <div class="art-sun"></div>
        <div class="art-circle art-circle-one"></div>
        <div class="art-circle art-circle-two"></div>
        <div class="court-grid"></div>
        <div class="paddle">
            <div class="paddle-face">
                <span class="paddle-swoosh">✳</span>
                <span class="paddle-word">RALLY<br>SUPPLY</span>
                <span class="paddle-grip"></span>
            </div>
        </div>
        <div class="hero-ball"><span></span></div>
        <div class="art-sticker">GOOD<br>RALLY<br>ENERGY <b>✳</b></div>
        <span class="art-caption">EQUIPMENT FOR THE EVERYDAY COURT</span>
    </div>
</section>

<section class="ticker" aria-label="Shop specialties">
    <div class="ticker-track">
        <span>PLAY YOUR WAY</span><b>✳</b>
        <span>FIND YOUR FEEL</span><b>✳</b>
        <span>OWN THE KITCHEN</span><b>✳</b>
        <span>PLAY YOUR WAY</span><b>✳</b>
        <span>FIND YOUR FEEL</span><b>✳</b>
        <span>OWN THE KITCHEN</span><b>✳</b>
    </div>
</section>

<section class="intro wrap">
    <div>
        <p class="eyebrow">THE RALLY DIFFERENCE</p>
        <h2>Good days start<br>at the <em>baseline.</em></h2>
    </div>
    <p class="intro-copy">We make it easier to find the gear that feels right, so you can spend less time comparing specs and more time chasing the next point. Friendly advice, player-tested picks, no gatekeeping.</p>
</section>

<section class="feature-grid wrap">
    <article class="feature-card feature-lime">
        <span class="feature-number">01 / PADDLES</span>
        <div class="feature-icon">⌁</div>
        <h3>Find your<br>sweet spot.</h3>
        <p>From forgiving first paddles to touch-first control. Try it, feel it, find your game.</p>
        <a href="<?= site_url('shop') ?>" aria-label="Shop pickleball paddles">Shop paddles ↗</a>
    </article>
    <article class="feature-card feature-lilac">
        <span class="feature-number">02 / PEOPLE</span>
        <div class="feature-icon">↗</div>
        <h3>It's better<br>as a rally.</h3>
        <p>Whether it's your first open play or your hundredth, there's a place for you here.</p>
        <a href="<?= session()->get('staff_id') ? site_url('customers') : site_url('about') ?>"><?= session()->get('staff_id') ? 'Meet the community' : 'Read our story' ?> ↗</a>
    </article>
    <article class="feature-card feature-dark">
        <span class="feature-number">03 / THE CREW</span>
        <div class="feature-icon">✳</div>
        <h3>Players helping<br>players.</h3>
        <p>Real humans behind the counter. Ask us about grip size, shoes, and where to play.</p>
        <a href="<?= session()->get('staff_id') ? site_url('users') : site_url('shop') ?>"><?= session()->get('staff_id') ? 'Meet the team' : 'Explore the gear' ?> ↗</a>
    </article>
</section>

<section class="closing-cta wrap">
    <div>
        <p class="eyebrow">YOUR NEXT GAME IS CALLING</p>
        <h2>Bring the<br><em>good energy.</em></h2>
    </div>
    <a class="button button-lime" href="<?= site_url('shop') ?>">Shop the collection <span>↗</span></a>
    <span class="closing-ball" aria-hidden="true">✳</span>
</section>

<?= view('partials/footer') ?>
