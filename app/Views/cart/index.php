<?php
/**
 * Cart View
 *
 * Variables: $items (array), $total (int)
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="container">

    <a href="<?= base_url('/') ?>" class="back-link">&larr; Kembali ke Beranda</a>

    <h2>Keranjang</h2>

    <?php if (empty($items)): ?>
    <div class="card">
        <p>Keranjang masih kosong.</p>
    </div>
    <?php else: ?>
    <div class="card">
        <?php foreach ($items as $item):
            $subtotal = $item['price'] * $item['qty'];
        ?>
        <div class="cart-row">
            <div>
                <strong><?= e($item['name']) ?></strong><br>
                <small><?= rupiah($item['price']) ?></small>
            </div>

            <div class="cart-controls">
                <a href="<?= base_url('cart/action?action=minus&id=' . $item['id']) ?>">&#x2796;</a>
                <span><?= $item['qty'] ?></span>
                <a href="<?= base_url('cart/action?action=plus&id=' . $item['id']) ?>">&#x2795;</a>
                <a href="<?= base_url('cart/action?action=remove&id=' . $item['id']) ?>" class="remove">&#x2716;</a>
            </div>

            <div class="cart-subtotal">
                <?= rupiah($subtotal) ?>
            </div>
        </div>
        <?php endforeach; ?>

        <hr>

        <div class="cart-total">
            <span>Total</span>
            <strong><?= rupiah($total) ?></strong>
        </div>
    </div>

    <a href="<?= base_url('checkout') ?>" class="btn-primary">
        Lanjut Checkout
    </a>
    <?php endif; ?>

</div>

</body>
</html>
