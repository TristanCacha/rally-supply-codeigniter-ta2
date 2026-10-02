<?= view('partials/header', ['title' => $title]) ?>

<section class="list-page wrap">
    <div class="list-heading">
        <div>
            <p class="eyebrow"><span class="eyebrow-line"></span> BEHIND THE COUNTER</p>
            <h1>Stock<br><em>on hand.</em></h1>
        </div>
        <div class="list-aside"><span class="count-badge"><?= count($variants) ?> OPTIONS</span><p>Update the quantity for each product option. A completed sale reduces this number automatically.</p></div>
    </div>
    <?php if ($notice = session()->getFlashdata('notice')): ?><p class="form-notice" role="status"><?= esc($notice) ?></p><?php endif ?>
    <?php if ($error = session()->getFlashdata('error')): ?><p class="form-error" role="alert"><?= esc($error) ?></p><?php endif ?>
    <div class="table-wrap" role="region" aria-label="Product inventory" tabindex="0">
        <table class="account-table inventory-table">
            <caption class="sr-only">Product options and their current stock quantities</caption>
            <thead><tr><th scope="col">PRODUCT</th><th scope="col">OPTION</th><th scope="col">SKU</th><th scope="col">STOCK</th><th scope="col">UPDATE</th></tr></thead>
            <tbody>
            <?php foreach ($variants as $variant): ?>
                <tr>
                    <td><strong><?= esc($variant['name']) ?></strong><small><?= esc($variant['category_label']) ?></small></td>
                    <td><?= esc($variant['label']) ?></td>
                    <td><span class="account-date"><?= esc($variant['sku']) ?></span></td>
                    <td><span class="stock-pill <?= (int) $variant['stock_qty'] === 0 ? 'stock-empty' : '' ?>"><?= (int) $variant['stock_qty'] ?></span></td>
                    <td>
                        <form class="stock-form" method="post" action="<?= site_url('inventory/update') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="variant_id" value="<?= esc($variant['id'], 'attr') ?>">
                            <label class="sr-only" for="stock-<?= (int) $variant['id'] ?>">New stock for <?= esc($variant['name']) ?>, <?= esc($variant['label']) ?></label>
                            <input id="stock-<?= (int) $variant['id'] ?>" type="number" name="stock_qty" min="0" max="9999" value="<?= (int) $variant['stock_qty'] ?>" required>
                            <button type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</section>

<?= view('partials/footer') ?>
