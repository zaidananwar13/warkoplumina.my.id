<?php
/**
 * Checkout View
 *
 * Variables: $total (int)
 */
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="app-wrapper">

    <!-- HEADER -->
    <header class="page-header">
        <a href="<?= base_url('cart') ?>" class="back-btn">&larr;</a>
        <span class="page-title">Checkout</span>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">&#x1F319;</button>
        </div>
    </header>

    <!-- TOTAL -->
    <div class="cart-summary" style="margin-top:8px;">
        <span class="label">Total Pembayaran</span>
        <span class="total" id="totalText"><?= rupiah($total) ?></span>
    </div>

    <!-- FORM -->
    <form action="<?= base_url('checkout/process') ?>" method="post" id="checkoutForm">
        <input type="hidden" id="totalValue" value="<?= (int)$total ?>">

        <div class="checkout-section">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required>
            </div>

            <div class="form-group">
                <label for="kamar">Nomor Kamar</label>
                <input type="text" id="kamar" name="kamar" placeholder="Contoh: Kamar 12" required>
            </div>

            <div class="form-group">
                <label for="paymentMethod">Metode Pembayaran</label>
                <select name="pembayaran" id="paymentMethod" required>
                    <option value="Cash">Cash</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>

            <div id="cashFields">
                <div class="form-group">
                    <label for="paidAmount">Uang Dibayar</label>
                    <input type="number" name="uang" id="paidAmount" placeholder="Contoh: 20000">
                </div>

                <div class="change-box" id="changeBox" style="display:none;">
                    <span class="change-label">Kembalian</span>
                    <span class="change-value" id="changeText">Rp0</span>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <button type="submit" class="btn-primary">Kirim via WhatsApp</button>
        </div>
    </form>

</div>

<script src="<?= asset('js/checkout.js') ?>"></script>
<script src="<?= asset('js/theme.js') ?>"></script>
</body>
</html>
