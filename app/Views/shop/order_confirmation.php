<?= view('partials/header', ['title' => $title]) ?>

<section class="staff-page wrap pos-page">
    <div class="staff-intro">
        <p class="eyebrow"><span class="eyebrow-line"></span> ORDER RECEIVED</p>
        <h1>Thank you,<br><em><?= esc(explode(' ', trim($order['buyer_name']))[0]) ?>.</em></h1>
        <p>Order #<?= (int) $order['id'] ?> is pending staff review. Your items are reserved. No payment has been charged and shipping has not been arranged.</p>
        <a class="text-link" href="<?= site_url('shop') ?>">← Continue shopping</a>
    </div>
    <div class="staff-panel checkout-panel receipt-card">
        <p class="eyebrow">RALLY SUPPLY · ORDER #<?= (int) $order['id'] ?></p>
        <h2><?= esc(ucfirst($order['status'])) ?> order</h2>
        <p>Placed <?= esc(date('M j, Y · g:i A', strtotime($order['created_at']))) ?>. Contact: <?= esc($order['buyer_email']) ?><?= $order['buyer_phone'] ? ' · ' . esc($order['buyer_phone']) : '' ?>.</p>
        <?php foreach ($items as $item): ?>
            <div class="checkout-line"><span><?= esc($item['product_name']) ?><small><?= esc($item['variant_label']) ?> · ×<?= (int) $item['quantity'] ?> · ₱<?= number_format((int) $item['unit_price_cents'] / 100, 2) ?> each</small></span><strong>₱<?= number_format((int) $item['line_total_cents'] / 100, 2) ?></strong></div>
        <?php endforeach ?>
        <div class="summary-total"><span>Order total</span><strong>₱<?= number_format((int) $order['total_cents'] / 100, 2) ?></strong></div>
        <p class="checkout-disclosure">This confirmation records an order request only. It is not a payment receipt.</p>
        <button class="button button-dark print-button" type="button" onclick="window.print()">Print confirmation <span aria-hidden="true">↗</span></button>
    </div>
</section>

<?= view('partials/footer') ?>
