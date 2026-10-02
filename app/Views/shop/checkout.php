<?= view('partials/header', ['title' => $title]) ?>

<section class="staff-page wrap pos-page">
    <div class="staff-intro"><p class="eyebrow"><span class="eyebrow-line"></span> POINT OF SALE</p><h1>Complete<br><em>the sale.</em></h1><p>Review the cart, choose a customer if applicable, and record the sale. Stock is deducted only after the transaction succeeds.</p></div>
    <div class="staff-panel checkout-panel">
        <p class="eyebrow">SALE REVIEW</p>
        <h2><?= count($items) ?> product options</h2>
        <?php foreach ($items as $item): ?>
            <div class="checkout-line"><span><?= esc($item['name']) ?> <small><?= esc($item['variant_label']) ?> · ×<?= (int) $item['quantity'] ?></small></span><strong>₱<?= number_format((int) $item['line_total_cents'] / 100, 2) ?></strong></div>
        <?php endforeach ?>
        <div class="summary-total"><span>Total</span><strong>₱<?= number_format($totalCents / 100, 2) ?></strong></div>
        <?php if ($error = session()->getFlashdata('error')): ?><p class="form-error" role="alert"><?= esc($error) ?></p><?php endif ?>
        <form method="post" action="<?= site_url('pos/checkout') ?>" class="stack-form">
            <?= csrf_field() ?>
            <label for="sale-customer">Customer account</label>
            <select id="sale-customer" name="customer_id"><option value="">Walk-in customer</option>
                <?php foreach ($customers as $customer): ?><option value="<?= (int) $customer['id'] ?>"><?= esc($customer['full_name']) ?></option><?php endforeach ?>
            </select>
            <button class="button button-dark" type="submit">Record sale <span aria-hidden="true">↗</span></button>
        </form>
        <a class="text-link" href="<?= site_url('cart') ?>">← Review cart</a>
    </div>
</section>

<?= view('partials/footer') ?>
