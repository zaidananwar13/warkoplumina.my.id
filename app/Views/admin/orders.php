<?php
/**
 * Admin Orders View
 *
 * Variables: $orders (array), $detail (array|null), $detail_items (array),
 *            $today_filter (bool), $admin_role, $current_page
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Order - Admin Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <style>
        .menu-toggle { display: none; font-size: 26px; cursor: pointer; }
        @media(max-width:768px) {
            .admin-nav { position: fixed; top: 0; left: -260px; width: 260px; height: 100%; background: #111; flex-direction: column; padding-top: 70px; transition: 0.3s; z-index: 999; }
            .admin-nav a { padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .admin-nav.show { left: 0; }
            .menu-toggle { display: block; }
        }
        .table-scroll { overflow-x: auto; }
        .table-scroll table { min-width: 900px; border-collapse: collapse; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-container">

<h2>Order Masuk</h2>

<p>
    <a href="<?= base_url('admin/orders') ?>">Semua Order</a> |
    <a href="<?= base_url('admin/orders?today=1') ?>">Hari Ini</a>
</p>

<div class="table-scroll">
<table width="100%" cellpadding="8" cellspacing="0">
<tr style="background:#222;">
    <th>Kode</th>
    <th>Waktu</th>
    <th>Nama</th>
    <th>Kamar</th>
    <th>Total</th>
    <th>Bayar</th>
    <th>Status</th>
    <th>Detail</th>
</tr>

<?php foreach ($orders as $o): ?>
<tr>
    <td><?= e($o['order_code']) ?></td>
    <td><?= e($o['created_at']) ?></td>
    <td><?= e($o['customer_name']) ?></td>
    <td><?= e($o['room_number']) ?></td>
    <td><?= rupiah($o['total_price']) ?></td>
    <td><?= strtoupper(e($o['payment_method'] ?? '-')) ?></td>
    <td>
        <form method="post" action="<?= base_url('admin/orders/status') ?>" style="margin:0;">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="pending" <?= ($o['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processed" <?= ($o['status'] ?? '') === 'processed' ? 'selected' : '' ?>>Diproses</option>
                <option value="done" <?= ($o['status'] ?? '') === 'done' ? 'selected' : '' ?>>Selesai</option>
            </select>
            <noscript><button type="submit">Update</button></noscript>
        </form>
    </td>
    <td>
        <a href="<?= base_url('admin/orders?detail=' . $o['id']) ?>">Lihat</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

<?php if ($detail): ?>
<hr>
<h3>Detail Order: <?= e($detail['order_code']) ?></h3>
<div class="card">
    <table width="100%" cellpadding="6">
        <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
        <?php foreach ($detail_items as $item): ?>
        <tr>
            <td><?= e($item['product_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= rupiah($item['price']) ?></td>
            <td><?= rupiah($item['subtotal']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

</main>
</body>
</html>
