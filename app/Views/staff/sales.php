<?= view('partials/header', ['title' => $title]) ?>

<section class="list-page wrap">
    <div class="list-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> THE SALES LEDGER</p><h1>Recent<br><em>sales.</em></h1></div><div class="list-aside"><span class="count-badge"><?= $totalSales ?> SALES</span><p>The latest twenty completed sales. Open a receipt to inspect the recorded items and totals.</p></div></div>
    <?php if ($notice = session()->getFlashdata('notice')): ?><p class="form-notice" role="status"><?= esc($notice) ?></p><?php endif ?>
    <div class="table-wrap" role="region" aria-label="Recent sales" tabindex="0">
        <table class="account-table"><caption class="sr-only">Completed sales, customers, staff, dates and totals</caption>
            <thead><tr><th scope="col">SALE</th><th scope="col">CUSTOMER</th><th scope="col">STAFF</th><th scope="col">DATE</th><th scope="col">TOTAL</th></tr></thead>
            <tbody>
            <?php if ($sales === []): ?><tr><td colspan="5" class="empty-roster">No sales recorded yet.</td></tr><?php endif ?>
            <?php foreach ($sales as $sale): ?>
                <tr><td><a class="table-link" href="<?= site_url('sales/' . $sale['id']) ?>">#<?= (int) $sale['id'] ?> ↗</a></td>
                    <td><?= esc($customers[$sale['customer_id']] ?? 'Walk-in') ?></td><td><?= esc($users[$sale['user_id']] ?? 'Staff account') ?></td>
                    <td><time class="account-date" datetime="<?= esc(str_replace(' ', 'T', $sale['created_at']), 'attr') ?>"><?= esc(date('M j, Y · g:i A', strtotime($sale['created_at']))) ?></time></td>
                    <td><strong>₱<?= number_format((int) $sale['total_cents'] / 100, 2) ?></strong></td></tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</section>

<?= view('partials/footer') ?>
