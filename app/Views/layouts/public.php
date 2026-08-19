<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? 'Warkop Lumina Tebet') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080808">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <?php if (!empty($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>
<body<?= !empty($bodyClass) ? ' class="' . e($bodyClass) . '"' : '' ?>>

<?= $content ?>

<?php if (!empty($showFloatingCart)): ?>
<!-- FLOATING CART -->
<a href="<?= base_url('cart') ?>" id="floating-cart">
    <span aria-hidden="true">&#x1F6D2;</span> <span id="cart-count">0</span>
</a>
<?php endif; ?>

<script src="<?= asset('js/cart.js') ?>"></script>
<?php if (!empty($extraJs)): ?>
    <?= $extraJs ?>
<?php endif; ?>
</body>
</html>
