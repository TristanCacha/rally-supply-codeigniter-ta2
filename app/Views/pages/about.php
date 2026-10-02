<?= view('partials/header', ['title' => $title]) ?>

<section class="page-hero wrap">
    <p class="eyebrow"><span class="eyebrow-line"></span> A LITTLE ABOUT US</p>
    <h1>For the love<br>of a <em>good rally.</em></h1>
    <p class="page-lede">Rally Supply is a neighborhood pickleball shop for every kind of player: curious beginners, weekend regulars, and anyone who believes the best point is the one you play together.</p>
</section>

<section class="about-band">
    <div class="about-art">
        <div class="about-ball">R</div>
        <span>COURT CULTURE<br>OVER COURT CRED</span>
    </div>
    <div class="about-copy">
        <p class="eyebrow">OUR WAY OF PLAYING</p>
        <h2>Gear advice<br>without the <em>game face.</em></h2>
        <p>We believe the right setup starts with a conversation, not a spec sheet. Our crew helps you find a paddle, fit, and feel that suits your game and your budget. Try new things. Ask every question. Take the extra warm-up.</p>
        <p>We're building more than a shop. We're building a place to get into the game, meet your next doubles partner, and leave in a better mood than you arrived.</p>
        <a class="text-link" href="<?= session()->get('staff_id') ? site_url('users') : site_url('shop') ?>"><?= session()->get('staff_id') ? 'Say hi to the crew' : 'Explore the collection' ?> <span>→</span></a>
    </div>
</section>

<section class="values wrap">
    <div>
        <span class="value-index">01</span>
        <h3>Stay curious.</h3>
        <p>There is always one more shot to learn and one more way to play.</p>
    </div>
    <div>
        <span class="value-index">02</span>
        <h3>Keep it welcoming.</h3>
        <p>Every skill level belongs on the court. Every question belongs in the shop.</p>
    </div>
    <div>
        <span class="value-index">03</span>
        <h3>Pass it back.</h3>
        <p>Pick up a spare ball, invite someone new, and keep the rally going.</p>
    </div>
</section>

<section class="page-end wrap">
    <span>THE COURT IS BETTER TOGETHER.</span>
    <a class="button button-dark" href="<?= session()->get('staff_id') ? site_url('customers') : site_url('shop') ?>"><?= session()->get('staff_id') ? 'Meet our community' : 'Shop the collection' ?> <span>↗</span></a>
</section>

<?= view('partials/footer') ?>
