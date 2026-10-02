<?= view('partials/header', ['title' => $title]) ?>

<section class="list-page wrap">
    <div class="list-heading">
        <div>
            <p class="eyebrow"><span class="eyebrow-line"></span> THE RALLY ROSTER</p>
            <h1>Customer<br><em>accounts.</em></h1>
        </div>
        <div class="list-aside">
            <span class="count-badge"><?= $totalCustomers ?> CUSTOMERS</span>
            <p>A friendly face makes every visit better. Our community, one player at a time.</p>
            <a class="text-link" href="<?= site_url('users') ?>">View user accounts <span>→</span></a>
        </div>
    </div>

    <form class="account-tools" method="get" action="<?= site_url('customers') ?>" role="search">
        <label for="customer-search">Find a customer
            <input id="customer-search" type="search" name="q" value="<?= esc($search, 'attr') ?>" maxlength="100" placeholder="Search by name">
        </label>
        <label for="customer-sort">Sort accounts
            <select id="customer-sort" name="sort">
                <?php foreach ($sortOptions as $key => $option): ?>
                    <option value="<?= esc($key, 'attr') ?>" <?= $sort === $key ? 'selected' : '' ?>><?= esc($option[0]) ?></option>
                <?php endforeach ?>
            </select>
        </label>
        <button class="account-apply" type="submit">Show results <span aria-hidden="true">↗</span></button>
        <?php if ($search !== '' || $sort !== 'id_asc'): ?>
            <a class="account-clear" href="<?= site_url('customers') ?>">Clear</a>
        <?php endif ?>
    </form>
    <p class="account-results" role="status">Showing <?= count($customers) ?> of <?= $totalCustomers ?> customer accounts<?= $search !== '' ? ' matching “' . esc($search) . '”' : '' ?>.</p>

    <div class="table-wrap" role="region" aria-label="Customer accounts" tabindex="0">
        <table class="account-table">
            <caption class="sr-only">Customer names, email addresses, phone numbers and creation dates.</caption>
            <thead>
                <tr><th scope="col">PLAYER</th><th scope="col">EMAIL</th><th scope="col">PHONE</th><th scope="col">JOINED</th></tr>
            </thead>
            <tbody>
            <?php if ($customers === []): ?>
                <tr><td colspan="4" class="empty-roster"><?= $search !== '' ? 'No customers match your search.' : 'No customer accounts yet.' ?></td></tr>
            <?php else: ?>
                <?php foreach ($customers as $index => $customer): ?>
                <tr>
                    <td>
                        <span class="row-number" title="Customer ID"><?= esc(str_pad((string) $customer['id'], 2, '0', STR_PAD_LEFT)) ?></span>
                        <span class="person-mark person-mark-<?= $index % 5 ?>" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr($customer['full_name'], 0, 1))) ?></span>
                        <strong><?= esc($customer['full_name']) ?></strong>
                    </td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= esc($customer['phone'] ?: 'Not provided') ?></td>
                    <td><time class="account-date" datetime="<?= esc(str_replace(' ', 'T', $customer['created_at']), 'attr') ?>"><?= esc(date('M j, Y', strtotime($customer['created_at']))) ?></time></td>
                </tr>
                <?php endforeach ?>
            <?php endif ?>
            </tbody>
        </table>
    </div>

    <p class="table-note">A place for every player. A better game together.</p>
</section>

<?= view('partials/footer') ?>
