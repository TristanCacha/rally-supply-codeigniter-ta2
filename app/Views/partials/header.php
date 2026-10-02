<?php
$currentPath = trim(uri_string(), '/');
$isStaff = session()->get('staff_id') !== null;
$navItems = [
    ['label' => 'Home', 'path' => '', 'href' => site_url('/')],
    ['label' => 'Shop', 'path' => 'shop', 'href' => site_url('shop')],
    ['label' => 'About', 'path' => 'about', 'href' => site_url('about')],
];
if ($isStaff) {
    $navItems[] = ['label' => 'Customers', 'path' => 'customers', 'href' => site_url('customers')];
    $navItems[] = ['label' => 'Users', 'path' => 'users', 'href' => site_url('users')];
    $navItems[] = ['label' => 'Inventory', 'path' => 'inventory', 'href' => site_url('inventory')];
    $navItems[] = ['label' => 'Orders', 'path' => 'orders', 'href' => site_url('orders')];
    $navItems[] = ['label' => 'Sales', 'path' => 'sales', 'href' => site_url('sales')];
}
$navItems[] = ['label' => 'Cart (' . (new \App\Libraries\CartService())->count() . ')', 'path' => 'cart', 'href' => site_url('cart')];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f2ea">
    <title><?= esc($title) ?> · Rally Supply</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/store.css') ?>">
</head>
<body>
<div class="topline"><span>Built around good games</span><span><?= $isStaff ? 'SIGNED IN · ' . esc((string) session()->get('staff_name')) : 'PLAY MORE. FIND YOUR EDGE.' ?> <b>✳</b></span></div>

<header class="site-header">
    <?= view('partials/brand') ?>
    <nav class="main-nav" aria-label="Main navigation">
        <?php foreach ($navItems as $item): ?>
            <?php $active = $currentPath === $item['path'] || ($item['path'] !== '' && str_starts_with($currentPath, $item['path'] . '/')); ?>
            <a
                href="<?= esc($item['href']) ?>"
                class="<?= $active ? 'is-active' : '' ?>"
                <?= $active ? 'aria-current="page"' : '' ?>
            >
                <?= esc($item['label']) ?>
            </a>
        <?php endforeach ?>
    </nav>
    <?php if ($isStaff): ?>
        <form class="logout-form" method="post" action="<?= site_url('logout') ?>"><?= csrf_field() ?><button class="header-cta" type="submit">Sign out <span aria-hidden="true">↗</span></button></form>
    <?php else: ?>
        <a class="header-cta" href="<?= site_url('shop') ?>">Shop the collection <span>↗</span></a>
    <?php endif ?>
</header>

<main>
