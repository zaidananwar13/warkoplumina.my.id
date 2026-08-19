<?php
session_start();
require_once __DIR__ . '/inc/functions.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <a href="index.php" class="back-link">← Kembali ke Beranda</a>

    <h2>Keranjang</h2>

    <?php if (!$cart): ?>
        <div class="card">
            <p>Keranjang masih kosong.</p>
        </div>
    <?php else: ?>

        <div class="card">
            <?php foreach ($cart as $item): 
                $subtotal = $item['price'] * $item['qty'];
                $total += $subtotal;
            ?>
                <div class="cart-row">
                    <div>
                        <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                        <small><?= rupiah($item['price']) ?></small>
                    </div>

                    <div class="cart-controls">
                        <a href="cart-action.php?action=minus&id=<?= $item['id'] ?>">➖</a>
                        <span><?= $item['qty'] ?></span>
                        <a href="cart-action.php?action=plus&id=<?= $item['id'] ?>">➕</a>
                        <a href="cart-action.php?action=remove&id=<?= $item['id'] ?>" class="remove">✖</a>
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

        <a href="checkout.php" class="btn-primary">
            Lanjut Checkout
        </a>

    <?php endif; ?>

</div>

</body>
</html>
