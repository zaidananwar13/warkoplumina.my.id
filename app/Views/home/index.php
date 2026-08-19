<?php
/**
 * Homepage View - Compact Shopee-style
 *
 * Variables: $categories (array)
 */
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Warkop Lumina Tebet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="app-wrapper">

    <!-- HEADER -->
    <header class="app-header">
        <span class="brand">Warkop Lumina</span>
        <div class="status-open">
            <span class="status-dot"></span> Buka
        </div>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">&#x1F319;</button>
            <a href="<?= base_url('history') ?>" class="header-history" title="Riwayat" id="historyBtn">
                &#x1F4CB;
                <span class="badge history-badge" id="history-badge" style="display:none;">0</span>
            </a>
            <a href="<?= base_url('cart') ?>" class="header-cart">
                &#x1F6D2;
                <span class="badge" id="cart-count" style="display:none;">0</span>
            </a>
        </div>
    </header>

    <!-- BANNER -->
    <div class="banner-section">
        <div class="banner-card">
            <img src="<?= asset('img/banner2.png') ?>" alt="Banner Warkop Lumina">
        </div>
    </div>

    <!-- CATEGORIES -->
    <div class="section-title">Menu</div>

    <div class="category-grid">
        <?php
        $icons = ['&#x2615;', '&#x1F354;', '&#x1F379;', '&#x1F370;', '&#x1F35C;', '&#x1F95E;', '&#x1F969;', '&#x1F366;'];
        $i = 0;
        foreach ($categories as $cat):
        ?>
        <a href="<?= base_url('category/' . e($cat['slug'])) ?>" class="cat-item">
            <div class="cat-icon"><?= $icons[$i % count($icons)] ?></div>
            <span class="cat-name"><?= e($cat['name']) ?></span>
        </a>
        <?php $i++; endforeach; ?>
    </div>

</div>

<!-- FLOATING CART -->
<a href="<?= base_url('cart') ?>" id="floating-cart">
    <span aria-hidden="true">&#x1F6D2;</span>
    <span id="cart-count-fab">0</span>
</a>

<script src="<?= asset('js/cart.js') ?>"></script>
<script src="<?= asset('js/notifications.js') ?>"></script>
<script src="<?= asset('js/theme.js') ?>"></script>
</body>
</html>
