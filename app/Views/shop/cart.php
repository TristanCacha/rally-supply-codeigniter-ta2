<?= view('partials/header', ['title' => $title]) ?>

<section class="list-page wrap pos-page">
    <div class="list-heading">
        <div><p class="eyebrow"><span class="eyebrow-line"></span> THE NEXT RALLY</p><h1>Your<br><em>cart.</em></h1></div>
        <div class="list-aside"><span class="count-badge"><?= count($items) ?> OPTIONS</span><p>Choose quantities here. Stock is checked again when you place your order.</p></div>
    </div>
    <?php if ($notice = session()->getFlashdata('notice')): ?><p class="form-notice" role="status"><?= esc($notice) ?></p><?php endif ?>
    <?php if ($error = session()->getFlashdata('error')): ?><p class="form-error" role="alert"><?= esc($error) ?></p><?php endif ?>
    <?php if ($stockIssue): ?><p class="form-error" role="alert">Stock changed for an item in your cart. Reduce its quantity before checkout.</p><?php endif ?>
    <?php if ($items === []): ?>
        <div class="empty-panel"><h2>Nothing in the cart yet.</h2><p>Find a paddle, a few essentials, or your next court favorite.</p><a class="button button-dark" href="<?= site_url('shop') ?>">Explore the collection <span>↗</span></a></div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <article class="cart-item">
                        <img src="<?= esc(base_url('assets/images/' . $item['image_file']), 'attr') ?>" alt="" loading="lazy">
                        <div class="cart-item-info"><h2><?= esc($item['name']) ?></h2><p><?= esc($item['variant_label']) ?></p><span>₱<?= number_format((int) $item['price_cents'] / 100, 2) ?> each · <?= (int) $item['stock_qty'] ?> in stock</span></div>
                        <div class="cart-item-actions">
                            <strong>₱<?= number_format((int) $item['line_total_cents'] / 100, 2) ?></strong>
                            <form method="post" action="<?= site_url('cart/update') ?>" class="stock-form">
                                <?= csrf_field() ?><input type="hidden" name="variant_id" value="<?= (int) $item['variant_id'] ?>">
                                <label class="sr-only" for="cart-qty-<?= (int) $item['variant_id'] ?>">Quantity of <?= esc($item['name']) ?></label>
                                <input id="cart-qty-<?= (int) $item['variant_id'] ?>" type="number" name="quantity" min="0" max="99" value="<?= (int) $item['quantity'] ?>" required>
                                <button type="submit">Update</button>
                            </form>
                            <form method="post" action="<?= site_url('cart/remove') ?>">
                                <?= csrf_field() ?><input type="hidden" name="variant_id" value="<?= (int) $item['variant_id'] ?>">
                                <button class="link-button" type="submit">Remove</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach ?>
            </div>
            <aside class="summary-card"><p class="eyebrow">ORDER SUMMARY</p><h2>Ready to rally?</h2><div class="summary-total"><span>Total</span><strong>₱<?= number_format($totalCents / 100, 2) ?></strong></div><p>Final quantity and price are checked when you place the order. No payment is collected on this demo site.</p>
                <?php if ($stockIssue): ?>
                    <p>Update the cart quantity before checkout.</p>
                <?php else: ?>
                    <a class="button button-dark" href="<?= site_url('checkout') ?>">Continue to checkout <span>↗</span></a>
                <?php endif ?>
                <?php if (session()->get('staff_id')): ?><a class="text-link" href="<?= site_url('pos/checkout') ?>">Record as an in-person sale →</a><?php endif ?>
                <a class="text-link" href="<?= site_url('shop') ?>">Continue shopping →</a>
            </aside>
        </div>
    <?php endif ?>
</section>

<?= view('partials/footer') ?>
