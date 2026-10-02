<?= view('partials/header', ['title' => $title]) ?>

<section class="shop-hero wrap">
    <div>
        <p class="eyebrow"><span class="eyebrow-line"></span> THE RALLY SUPPLY COLLECTION</p>
        <h1>Good gear for<br><em>every rally.</em></h1>
        <p class="shop-lede">Find your paddle, dial in your setup, and get everything you need to feel at home on court.</p>
    </div>
    <aside class="shop-note"><span class="shop-note-mark">✳</span><p>Thoughtful picks.<br>More time in the game.</p></aside>
</section>

<section class="shop-catalog wrap" aria-labelledby="catalog-heading">
    <div class="catalog-topline">
        <div>
            <p class="eyebrow">EQUIPMENT FOR THE EVERYDAY COURT</p>
            <h2 id="catalog-heading">The collection <span>· <?= count($products) ?> picks</span></h2>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?><p class="form-error" role="alert"><?= esc(session()->getFlashdata('error')) ?></p><?php endif ?>

    <nav class="shop-filters" aria-label="Filter products by category">
        <?php foreach ($filters as $filterKey => $filterLabel): ?>
            <?php $filterUrl = site_url('shop') . ($filterKey === 'all' ? '' : '?category=' . rawurlencode($filterKey)); ?>
            <a class="filter-button <?= $activeFilter === $filterKey ? 'is-active' : '' ?>"
               href="<?= esc($filterUrl, 'attr') ?>"
               <?= $activeFilter === $filterKey ? 'aria-current="page"' : '' ?>><?= esc($filterLabel) ?></a>
        <?php endforeach ?>
    </nav>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <article class="product-card">
                <div class="product-image-wrap">
                    <img class="product-image" src="<?= esc(base_url('assets/images/' . $product['image_file']), 'attr') ?>"
                         alt="<?= esc($product['image_alt'], 'attr') ?>" loading="lazy">
                    <span class="product-badge"><?= esc($product['badge']) ?></span>
                    <span class="product-category"><?= esc($product['category_label']) ?></span>
                </div>
                <div class="product-info">
                    <div class="product-name-row">
                        <h3><?= esc($product['name']) ?></h3>
                        <span class="product-price">₱<?= number_format((int) $product['price_cents'] / 100, 2) ?></span>
                    </div>
                    <p><?= esc($product['description']) ?></p>
                    <dl class="product-specs">
                        <?php foreach ($product['specs'] as $label => $value): ?>
                            <div><dt><?= esc($label) ?></dt><dd><?= esc($value) ?></dd></div>
                        <?php endforeach ?>
                    </dl>
                    <?php $inStock = array_filter($product['variants'], static fn ($variant) => (int) $variant['stock_qty'] > 0); ?>
                    <form class="product-buy" method="post" action="<?= site_url('cart/add') ?>">
                        <?= csrf_field() ?>
                        <label>Available <?= esc(strtolower($product['option_label'])) ?>
                            <select name="variant_id" required <?= $inStock === [] ? 'disabled' : '' ?>>
                                <option value="" disabled selected>Choose an option</option>
                                <?php foreach ($product['variants'] as $variant): ?>
                                    <option value="<?= esc($variant['id'], 'attr') ?>" <?= (int) $variant['stock_qty'] < 1 ? 'disabled' : '' ?>><?= esc($variant['label']) ?> · <?= (int) $variant['stock_qty'] ?> in stock</option>
                                <?php endforeach ?>
                            </select>
                        </label>
                        <div class="product-buy-row">
                            <label class="quantity-label">Qty <input type="number" name="quantity" min="1" max="20" value="1" required <?= $inStock === [] ? 'disabled' : '' ?>></label>
                            <button class="button button-dark" type="submit" <?= $inStock === [] ? 'disabled' : '' ?>><?= $inStock === [] ? 'Sold out' : 'Add to cart' ?> <span aria-hidden="true">↗</span></button>
                        </div>
                    </form>
                </div>
            </article>
        <?php endforeach ?>
    </div>
    <?php if ($products === []): ?><p class="empty-roster">No products in this category yet.</p><?php endif ?>
    <p class="catalog-disclaimer">Fictional demo catalog. Prices shown in Philippine pesos. Stock is checked again when a sale is completed.</p>
</section>

<section class="shop-callout wrap">
    <div><p class="eyebrow">NOT SURE WHERE TO START?</p><h2>Pick your feel.<br><em>We'll help from there.</em></h2></div>
    <a class="button button-dark" href="<?= site_url('about') ?>">Meet Rally Supply <span>↗</span></a>
</section>

<?= view('partials/footer') ?>
