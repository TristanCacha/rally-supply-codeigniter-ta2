<?= view('partials/header', ['title' => $title]) ?>

<section class="staff-page wrap">
    <div class="staff-intro">
        <p class="eyebrow"><span class="eyebrow-line"></span> RALLY SUPPLY POS</p>
        <h1>Welcome<br><em>back.</em></h1>
        <p>Sign in to view account rosters, manage stock, and complete sales.</p>
    </div>
    <div class="staff-panel">
        <p class="eyebrow">STAFF ACCESS</p>
        <h2>Sign in</h2>
        <?php if ($error !== null): ?><p class="form-error" role="alert"><?= esc($error) ?></p><?php endif ?>
        <form method="post" action="<?= site_url('login') ?>" class="stack-form">
            <?= csrf_field() ?>
            <input type="hidden" name="next" value="<?= esc($next, 'attr') ?>">
            <label for="staff-username">Username</label>
            <input id="staff-username" name="username" type="text" maxlength="50" autocomplete="username" required>
            <label for="staff-password">Password</label>
            <input id="staff-password" name="password" type="password" autocomplete="current-password" required>
            <button class="button button-dark" type="submit">Sign in <span aria-hidden="true">↗</span></button>
        </form>
        <p class="help-note">For the local demo, open <code>.local/staff-login.txt</code> in the project folder to find your first login.</p>
    </div>
</section>

<?= view('partials/footer') ?>
