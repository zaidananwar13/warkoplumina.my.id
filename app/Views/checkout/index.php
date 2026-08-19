<?php
/**
 * Checkout View
 *
 * Variables: $total (int)
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="checkout-page">

<div class="container">

    <a href="<?= base_url('cart') ?>" class="back-link">&larr; Kembali ke Keranjang</a>

    <h2>Checkout</h2>
    <p class="subtitle">
        Total Bayar: <strong id="totalText"><?= rupiah($total) ?></strong>
    </p>

    <form action="<?= base_url('checkout/process') ?>" method="post" class="card" id="checkoutForm">
        <!-- TOTAL UNTUK JS -->
        <input type="hidden" id="totalValue" value="<?= (int)$total ?>">

        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" placeholder="Nama kamu" required>

        <label for="kamar">Nomor Kamar</label>
        <input type="text" id="kamar" name="kamar" placeholder="Contoh: Kamar 12" required>

        <label for="paymentMethod">Metode Pembayaran</label>
        <select name="pembayaran" id="paymentMethod" required>
            <option value="Cash">Cash</option>
            <option value="QRIS">QRIS</option>
        </select>

        <!-- FIELD CASH -->
        <div id="cashFields">
            <label for="paidAmount">Uang Dibayar</label>
            <input type="number" name="uang" id="paidAmount" placeholder="Contoh: 20000">

            <div id="changeBox" style="display:none; margin-top:10px;">
                <strong>Kembalian:</strong>
                <span id="changeText">Rp0</span>
            </div>
        </div>

        <button type="submit" class="btn-primary">
            Kirim Pesanan ke WhatsApp
        </button>
    </form>

</div>

<script src="<?= asset('js/checkout.js') ?>"></script>
</body>
</html>
