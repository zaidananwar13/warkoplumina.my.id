<?php
require_once 'inc/auth.php';
require_once '../inc/db.php';

// filter tanggal
$from = $_GET['from'] ?? date('Y-m-d');
$to   = $_GET['to']   ?? date('Y-m-d');

// summary
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) AS total_order,
        COALESCE(SUM(total_price),0) AS omzet,
        COALESCE(SUM(CASE WHEN payment_method='cash' THEN total_price END),0) AS cash_total,
        COALESCE(SUM(CASE WHEN payment_method='qris' THEN total_price END),0) AS qris_total
    FROM orders
    WHERE DATE(created_at) BETWEEN ? AND ?
");
$stmt->execute([$from, $to]);
$summary = $stmt->fetch();

// list order
$stmt = $pdo->prepare("
    SELECT * FROM orders
    WHERE DATE(created_at) BETWEEN ? AND ?
    ORDER BY created_at DESC
");
$stmt->execute([$from, $to]);
$orders = $stmt->fetchAll();

include 'inc/header.php';
?>

<h2>Laporan Harian & Omzet</h2>

<!-- FILTER -->
<form method="get" style="margin-bottom:15px;">
    <label>Dari</label>
    <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
    <label>Sampai</label>
    <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
    <button type="submit">Tampilkan</button>
</form>

<!-- SUMMARY -->
<div class="card">
    <p><strong>Total Order:</strong> <?= (int)$summary['total_order'] ?></p>
    <p><strong>Omzet:</strong> <?= rupiah($summary['omzet']) ?></p>
    <p><strong>Cash:</strong> <?= rupiah($summary['cash_total']) ?></p>
    <p><strong>QRIS:</strong> <?= rupiah($summary['qris_total']) ?></p>
</div>

<hr>

<!-- TABLE ORDER -->
<table width="100%" cellpadding="8" cellspacing="0">
<tr style="background:#222;">
    <th>Kode</th>
    <th>Waktu</th>
    <th>Nama</th>
    <th>Kamar</th>
    <th>Bayar</th>
    <th>Total</th>
</tr>

<?php foreach ($orders as $o): ?>
<tr>
    <td><?= htmlspecialchars($o['order_code']) ?></td>
    <td><?= $o['created_at'] ?></td>
    <td><?= htmlspecialchars($o['customer_name']) ?></td>
    <td><?= htmlspecialchars($o['room_number']) ?></td>
    <td><?= strtoupper($o['payment_method']) ?></td>
    <td><?= rupiah($o['total_price']) ?></td>
</tr>
<?php endforeach; ?>

<?php if (!$orders): ?>
<tr>
    <td colspan="6" align="center">Tidak ada data</td>
</tr>
<?php endif; ?>
<a href="orders.php?detail=<?= $o['id'] ?>">Detail</a>
</table>

<?php include 'inc/footer.php'; ?>
