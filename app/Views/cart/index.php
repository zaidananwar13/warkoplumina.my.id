<?php
/**
 * Cart View
 *
 * Variables: $items (array), $total (int)
 */
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="app-wrapper">

    <!-- HEADER -->
    <header class="page-header">
        <a href="<?= base_url('/') ?>" class="back-btn">&larr;</a>
        <span class="page-title">Keranjang</span>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">&#x1F319;</button>
        </div>
    </header>

    <?php if (empty($items)): ?>
    <div class="cart-empty">
        <div class="cart-empty-icon">&#x1F6D2;</div>
        <p>Keranjang masih kosong</p>
        <a href="<?= base_url('/') ?>" class="btn-secondary" style="width:auto;display:inline-flex;margin-top:12px;padding:9px 20px;font-size:13px;">
            Mulai Belanja
        </a>
    </div>
    <?php else: ?>

    <!-- ITEMS -->
    <div class="card" style="margin-top:8px;">
        <?php foreach ($items as $item):
            $subtotal = $item['price'] * $item['qty'];
        ?>
        <div class="cart-item">
            <div class="cart-item-info">
                <div class="cart-item-name"><?= e($item['name']) ?></div>
                <div class="cart-item-price"><?= rupiah($item['price']) ?></div>
                <div class="cart-item-subtotal"><?= rupiah($subtotal) ?></div>
            </div>
            <div class="cart-qty">
                <a href="<?= base_url('cart/action?action=minus&id=' . $item['id']) ?>">-</a>
                <span class="qty-value"><?= $item['qty'] ?></span>
                <a href="<?= base_url('cart/action?action=plus&id=' . $item['id']) ?>">+</a>
                <a href="<?= base_url('cart/action?action=remove&id=' . $item['id']) ?>" class="remove-btn">&#x2715;</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- SUMMARY -->
    <div class="cart-summary">
        <span class="label">Total</span>
        <span class="total"><?= rupiah($total) ?></span>
    </div>

    <!-- CHECKOUT -->
    <div class="bottom-bar">
        <a href="<?= base_url('checkout') ?>" class="btn-primary">Checkout (<?= rupiah($total) ?>)</a>
    </div>

    <?php endif; ?>
</div>

<script src="<?= asset('js/theme.js') ?>"></script>
<script src="<?= asset('js/notifications.js') ?>"></script>
</body>
</html>
