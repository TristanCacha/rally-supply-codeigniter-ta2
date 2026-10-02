<?= view('partials/header', ['title' => $title]) ?>

<section class="staff-page wrap pos-page">
    <div class="staff-intro">
        <p class="eyebrow"><span class="eyebrow-line"></span> YOUR NEXT RALLY</p>
        <h1>Place your<br><em>order.</em></h1>
        <p>No staff account is needed. Add your contact details so the shop can follow up about your order. This demo does not collect payment or arrange shipping.</p>
    </div>
    <div class="staff-panel checkout-panel">
        <p class="eyebrow">ORDER REVIEW</p>
        <h2><?= count($items) ?> product options</h2>
        <?php foreach ($items as $item): ?>
            <div class="checkout-line"><span><?= esc($item['name']) ?><small><?= esc($item['variant_label']) ?> · ×<?= (int) $item['quantity'] ?></small></span><strong>₱<?= number_format((int) $item['line_total_cents'] / 100, 2) ?></strong></div>
        <?php endforeach ?>
        <div class="summary-total"><span>Total</span><strong>₱<?= number_format($totalCents / 100, 2) ?></strong></div>
        <?php if ($error = session()->getFlashdata('error')): ?><p class="form-error" role="alert"><?= esc($error) ?></p><?php endif ?>
        <form method="post" action="<?= site_url('checkout') ?>" class="stack-form">
            <?= csrf_field() ?>
            <label for="buyer-name">Full name</label>
            <input id="buyer-name" name="buyer_name" type="text" maxlength="100" autocomplete="name" value="<?= esc($input['name'] ?? '', 'attr') ?>" required>
            <label for="buyer-email">Email address</label>
            <input id="buyer-email" name="buyer_email" type="email" maxlength="100" autocomplete="email" value="<?= esc($input['email'] ?? '', 'attr') ?>" required>
            <label for="buyer-phone">Phone number <span class="optional-label">optional</span></label>
            <input id="buyer-phone" name="buyer_phone" type="tel" maxlength="20" autocomplete="tel" value="<?= esc($input['phone'] ?? '', 'attr') ?>">
            <p class="checkout-disclosure">Placing an order reserves the selected stock and creates a pending order for staff review. No payment is charged. Keep the confirmation page link for your reference.</p>
            <button class="button button-dark" type="submit">Place order <span aria-hidden="true">↗</span></button>
        </form>
        <a class="text-link" href="<?= site_url('cart') ?>">← Review cart</a>
    </div>
</section>

<?= view('partials/footer') ?>
