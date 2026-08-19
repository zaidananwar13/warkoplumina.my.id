<?php
session_start();
require_once __DIR__ . '/inc/functions.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    header("Location: cart.php");
    exit;
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="checkout-page">

<div class="container">

    <a href="cart.php" class="back-link">← Kembali ke Keranjang</a>

    <h2>Checkout</h2>
    <p class="subtitle">
        Total Bayar: <strong id="totalText"><?= rupiah($total) ?></strong>
    </p>

    <form action="send-wa.php" method="post" class="card" id="checkoutForm">

        <!-- TOTAL UNTUK JS -->
        <input type="hidden" id="totalValue" value="<?= (int)$total ?>">

        <label>Nama</label>
        <input type="text" name="nama" placeholder="Nama kamu" required>

        <label>Nomor Kamar</label>
        <input type="text" name="kamar" placeholder="Contoh: Kamar 12" required>

        <label>Metode Pembayaran</label>
        <select name="pembayaran" id="paymentMethod" required>
            <option value="Cash">Cash</option>
            <option value="QRIS">QRIS</option>
        </select>

        <!-- FIELD CASH -->
        <div id="cashFields">
            <label>Uang Dibayar</label>
            <input type="number"
                   name="uang"
                   id="paidAmount"
                   placeholder="Contoh: 20000">

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

<!-- =========================
     JS LOGIC CHECKOUT
========================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const paymentMethod = document.getElementById('paymentMethod');
    const cashFields    = document.getElementById('cashFields');
    const paidAmount    = document.getElementById('paidAmount');
    const changeBox     = document.getElementById('changeBox');
    const changeText    = document.getElementById('changeText');
    const totalValue    = document.getElementById('totalValue');

    function formatRupiah(num) {
        return 'Rp' + num.toLocaleString('id-ID');
    }

    function updatePaymentUI() {
        const method = paymentMethod.value;

        if (method === 'QRIS') {
            cashFields.style.display = 'none';
            paidAmount.value = '';
            paidAmount.disabled = true;
            changeBox.style.display = 'none';
        } else {
            cashFields.style.display = 'block';
            paidAmount.disabled = false;
        }
    }

    function updateChange() {
        const paid  = parseInt(paidAmount.value || 0, 10);
        const total = parseInt(totalValue.value || 0, 10);

        if (paid >= total && paid > 0) {
            const change = paid - total;
            changeText.textContent = formatRupiah(change);
            changeBox.style.display = 'block';
        } else {
            changeBox.style.display = 'none';
        }
    }

    // init saat load
    updatePaymentUI();

    // event
    paymentMethod.addEventListener('change', updatePaymentUI);
    paidAmount.addEventListener('input', updateChange);
});
</script>

</body>
</html>
