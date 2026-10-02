<?= view('partials/header', ['title' => $title]) ?>

<section class="list-page wrap">
    <div class="list-heading">
        <div>
            <p class="eyebrow"><span class="eyebrow-line"></span> THE PEOPLE BEHIND THE COUNTER</p>
            <h1>User<br><em>accounts.</em></h1>
        </div>
        <div class="list-aside">
            <span class="count-badge"><?= $totalUsers ?> TEAMMATES</span>
            <p>Players first, product nerds always. Say hi when you stop by.</p>
            <a class="text-link" href="<?= site_url('customers') ?>">View the community <span>→</span></a>
        </div>
    </div>

    <form class="account-tools" method="get" action="<?= site_url('users') ?>" role="search">
        <label for="user-search">Find a teammate
            <input id="user-search" type="search" name="q" value="<?= esc($search, 'attr') ?>" maxlength="100" placeholder="Name or username">
        </label>
        <label for="user-sort">Sort accounts
            <select id="user-sort" name="sort">
                <?php foreach ($sortOptions as $key => $option): ?>
                    <option value="<?= esc($key, 'attr') ?>" <?= $sort === $key ? 'selected' : '' ?>><?= esc($option[0]) ?></option>
                <?php endforeach ?>
            </select>
        </label>
        <button class="account-apply" type="submit">Show results <span aria-hidden="true">↗</span></button>
        <?php if ($search !== '' || $sort !== 'id_asc'): ?>
            <a class="account-clear" href="<?= site_url('users') ?>">Clear</a>
        <?php endif ?>
    </form>
    <p class="account-results" role="status">Showing <?= count($users) ?> of <?= $totalUsers ?> user accounts<?= $search !== '' ? ' matching “' . esc($search) . '”' : '' ?>.</p>

    <div class="table-wrap" role="region" aria-label="User accounts" tabindex="0">
        <table class="account-table">
            <caption class="sr-only">Team member names, usernames, account IDs and creation dates.</caption>
            <thead>
                <tr><th scope="col">TEAMMATE</th><th scope="col">USERNAME</th><th scope="col">ACCOUNT ID</th><th scope="col">JOINED</th></tr>
            </thead>
            <tbody>
            <?php if ($users === []): ?>
                <tr><td colspan="4" class="empty-roster"><?= $search !== '' ? 'No users match your search.' : 'No user accounts yet.' ?></td></tr>
            <?php else: ?>
                <?php foreach ($users as $index => $user): ?>
                <tr>
                    <td>
                        <span class="person-mark person-mark-<?= $index % 5 ?>" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr($user['full_name'], 0, 1))) ?></span>
                        <strong><?= esc($user['full_name']) ?></strong>
                    </td>
                    <td><span class="username">@<?= esc($user['username']) ?></span></td>
                    <td><span class="account-date">#<?= esc($user['id']) ?></span></td>
                    <td><time class="account-date" datetime="<?= esc(str_replace(' ', 'T', $user['created_at']), 'attr') ?>"><?= esc(date('M j, Y', strtotime($user['created_at']))) ?></time></td>
                </tr>
                <?php endforeach ?>
            <?php endif ?>
            </tbody>
        </table>
    </div>

    <p class="table-note">Good people. Good gear. Ready for your next game.</p>
</section>

<?= view('partials/footer') ?>
