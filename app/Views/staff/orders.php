<?= view('partials/header', ['title' => $title]) ?>

<section class="list-page wrap pos-page">
    <div class="list-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> WEBSITE REQUESTS</p><h1>Customer<br><em>orders.</em></h1></div><div class="list-aside"><span class="count-badge"><?= (int) $totalOrders ?> ORDERS</span><p>Pending orders reserve stock. Mark one fulfilled after handling it, or cancel it to release the stock.</p></div></div>
    <?php if ($notice = session()->getFlashdata('notice')): ?><p class="form-notice" role="status"><?= esc($notice) ?></p><?php endif ?>
    <?php if ($error = session()->getFlashdata('error')): ?><p class="form-error" role="alert"><?= esc($error) ?></p><?php endif ?>
    <div class="table-wrap" role="region" aria-label="Website orders" tabindex="0">
        <table class="account-table"><caption class="sr-only">Website order requests, buyer contacts, statuses and actions</caption>
            <thead><tr><th scope="col">ORDER</th><th scope="col">CUSTOMER</th><th scope="col">STATUS</th><th scope="col">DATE</th><th scope="col">TOTAL</th><th scope="col">ACTION</th></tr></thead>
            <tbody>
            <?php if ($orders === []): ?><tr><td colspan="6" class="empty-roster">No website orders yet.</td></tr><?php endif ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><a class="table-link" href="<?= site_url('order/' . $order['public_token']) ?>">#<?= (int) $order['id'] ?> ↗</a></td>
                    <td><strong><?= esc($order['buyer_name']) ?></strong><small class="order-contact"><?= esc($order['buyer_email']) ?><?= $order['buyer_phone'] ? ' · ' . esc($order['buyer_phone']) : '' ?></small></td>
                    <td><span class="order-status order-status-<?= esc($order['status'], 'attr') ?>"><?= esc(ucfirst($order['status'])) ?></span></td>
                    <td><time class="account-date" datetime="<?= esc(str_replace(' ', 'T', $order['created_at']), 'attr') ?>"><?= esc(date('M j, Y · g:i A', strtotime($order['created_at']))) ?></time></td>
                    <td><strong>₱<?= number_format((int) $order['total_cents'] / 100, 2) ?></strong></td>
                    <td>
                        <?php if ($order['status'] === 'pending'): ?>
                            <div class="order-actions">
                                <form method="post" action="<?= site_url('orders/' . $order['id'] . '/status') ?>"><?= csrf_field() ?><input type="hidden" name="status" value="fulfilled"><button type="submit">Mark fulfilled</button></form>
                                <form method="post" action="<?= site_url('orders/' . $order['id'] . '/status') ?>" onsubmit="return confirm('Cancel this order and release its reserved stock?')"><?= csrf_field() ?><input type="hidden" name="status" value="cancelled"><button class="order-cancel" type="submit">Cancel</button></form>
                            </div>
                        <?php else: ?><span class="order-complete">No action needed</span><?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</section>

<?= view('partials/footer') ?>
