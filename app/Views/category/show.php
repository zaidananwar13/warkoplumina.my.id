<?php
/**
 * Category Products View
 *
 * Variables: $category (array), $products (array)
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($category['name']) ?> - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="container">

    <a href="<?= base_url('/') ?>" class="back-link">&larr; Kembali</a>

    <h2><?= e($category['name']) ?></h2>

    <div class="product-list">
        <?php foreach ($products as $p): ?>
        <div class="product-card">
            <?php if (!empty($p['image'])): ?>
            <img src="<?= upload_url('products/' . e($p['image'])) ?>"
                 alt="<?= e($p['name']) ?>"
                 class="product-img">
            <?php endif; ?>

            <div class="product-name"><?= e($p['name']) ?></div>
            <div class="product-price"><?= rupiah($p['price']) ?></div>

            <button onclick="addToCart(<?= (int)$p['id'] ?>)">Tambah</button>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- FLOATING CART -->
<a href="<?= base_url('cart') ?>" id="floating-cart">
    &#x1F6D2; <span id="cart-count">0</span>
</a>

<script src="<?= asset('js/cart.js') ?>"></script>
</body>
</html>
