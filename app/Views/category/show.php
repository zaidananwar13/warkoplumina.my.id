<?php
/**
 * Category Products View
 *
 * Variables: $category (array), $products (array)
 */
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title><?= e($category['name']) ?> - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="app-wrapper">

    <!-- HEADER -->
    <header class="page-header">
        <a href="<?= base_url('/') ?>" class="back-btn">&larr;</a>
        <span class="page-title"><?= e($category['name']) ?></span>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">&#x1F319;</button>
            <a href="<?= base_url('cart') ?>" class="header-cart">
                &#x1F6D2;
                <span class="badge" id="cart-count" style="display:none;">0</span>
            </a>
        </div>
    </header>

    <!-- PRODUCTS -->
    <?php if (!empty($products)): ?>
    <div class="product-grid">
        <?php foreach ($products as $p): ?>
        <div class="product-card">
            <?php if (!empty($p['image'])): ?>
            <img src="<?= upload_url('products/' . e($p['image'])) ?>"
                 alt="<?= e($p['name']) ?>"
                 class="product-img" loading="lazy">
            <?php else: ?>
            <div class="product-img" style="display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--text-muted);">&#x1F4F7;</div>
            <?php endif; ?>

            <div class="product-info">
                <div class="product-name"><?= e($p['name']) ?></div>
                <div class="product-price"><?= rupiah($p['price']) ?></div>
            </div>

            <button class="product-btn" onclick="addToCart(<?= (int)$p['id'] ?>)">+ Keranjang</button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center" style="padding:40px 12px;color:var(--text-secondary);">
        Belum ada produk di kategori ini.
    </div>
    <?php endif; ?>

</div>

<!-- FLOATING CART -->
<a href="<?= base_url('cart') ?>" id="floating-cart">
    <span aria-hidden="true">&#x1F6D2;</span>
    <span id="cart-count-fab">0</span>
</a>

<script src="<?= asset('js/cart.js') ?>"></script>
<script src="<?= asset('js/theme.js') ?>"></script>
</body>
</html>
