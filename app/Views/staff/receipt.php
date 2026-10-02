<?= view('partials/header', ['title' => $title]) ?>

<section class="staff-page wrap pos-page">
    <div class="staff-intro"><p class="eyebrow"><span class="eyebrow-line"></span> SALE RECORDED</p><h1>Receipt<br><em>#<?= (int) $sale['id'] ?>.</em></h1><p>Saved on <?= esc(date('M j, Y · g:i A', strtotime($sale['created_at']))) ?> by <?= esc($staff['full_name'] ?? 'Staff account') ?>.</p><a class="text-link" href="<?= site_url('sales') ?>">← All sales</a></div>
    <div class="staff-panel checkout-panel receipt-card">
        <p class="eyebrow">RALLY SUPPLY · SALE #<?= (int) $sale['id'] ?></p>
        <h2><?= esc($customer['full_name'] ?? 'Walk-in customer') ?></h2>
        <?php foreach ($items as $item): ?>
            <div class="checkout-line"><span><?= esc($item['product_name']) ?><small><?= esc($item['variant_label']) ?> · ×<?= (int) $item['quantity'] ?> · ₱<?= number_format((int) $item['unit_price_cents'] / 100, 2) ?> each</small></span><strong>₱<?= number_format((int) $item['line_total_cents'] / 100, 2) ?></strong></div>
        <?php endforeach ?>
        <div class="summary-total"><span>Sale total</span><strong>₱<?= number_format((int) $sale['total_cents'] / 100, 2) ?></strong></div>
        <p>Thank you for playing with Rally Supply.</p>
        <button class="button button-dark print-button" type="button" onclick="window.print()">Print receipt <span aria-hidden="true">↗</span></button>
    </div>
</section>

<?= view('partials/footer') ?>
