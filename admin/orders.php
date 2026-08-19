<?php
require_once 'inc/auth.php';
require_once '../inc/db.php';
require_once '../inc/functions.php';

/* ======================
   UPDATE STATUS
====================== */
if (isset($_POST['update_status'])) {

    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->execute([
        $_POST['status'],
        $_POST['order_id']
    ]);

    header("Location: orders.php");
    exit;
}

/* ======================
   FILTER
====================== */
$where = '';

if (isset($_GET['today'])) {
    $where = "WHERE DATE(created_at) = CURDATE()";
}

/* ======================
   LIST ORDER
====================== */
$orders = $pdo->query("
    SELECT * FROM orders
    $where
    ORDER BY created_at DESC
")->fetchAll();

include 'inc/header.php';
?>

<style>

.table-scroll{
    overflow-x:auto;
}

.table-scroll table{
    min-width:900px;
    border-collapse:collapse;
}

</style>

<h2>Order Masuk</h2>

<p>
<a href="orders.php">Semua Order</a> |
<a href="orders.php?today=1">Hari Ini</a>
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

<td><?= htmlspecialchars($o['order_code']) ?></td>

<td><?= $o['created_at'] ?></td>

<td><?= htmlspecialchars($o['customer_name']) ?></td>

<td><?= htmlspecialchars($o['room_number']) ?></td>

<td><?= rupiah($o['total_price']) ?></td>

<td><?= strtoupper($o['payment_method']) ?></td>

<td>

<form method="post" style="margin:0;">

<input type="hidden" name="order_id" value="<?= $o['id'] ?>">

<select name="status">

<option value="pending"
<?= $o['status']=='pending'?'selected':'' ?>>
Pending
</option>

<option value="processed"
<?= $o['status']=='processed'?'selected':'' ?>>
Diproses
</option>

<option value="done"
<?= $o['status']=='done'?'selected':'' ?>>
Selesai
</option>

</select>

<button type="submit" name="update_status">
OK
</button>

</form>

</td>

<td>
<a href="?detail=<?= $o['id'] ?>">Lihat</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php
/* ======================
   DETAIL ORDER
====================== */
if (isset($_GET['detail'])):

$stmt = $pdo->prepare("
SELECT * FROM order_items
WHERE order_id=?
");

$stmt->execute([$_GET['detail']]);

$items = $stmt->fetchAll();
?>

<hr>

<h3>Detail Pesanan</h3>

<div class="table-scroll">

<table width="100%" cellpadding="6">

<tr style="background:#222;">
<th>Produk</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
</tr>

<?php foreach ($items as $i): ?>

<tr>

<td><?= htmlspecialchars($i['product_name']) ?></td>

<td><?= $i['quantity'] ?></td>

<td><?= rupiah($i['price']) ?></td>

<td><?= rupiah($i['subtotal']) ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php endif; ?>

<script>

/* ======================
   NOTIFIKASI ORDER REALTIME
====================== */

let lastOrder = <?= count($orders) ? $orders[0]['id'] : 0 ?>;

setInterval(function(){

fetch('orders_check.php')

.then(res => res.json())

.then(data => {

if(data.last_id > lastOrder){

alert("🔔 Order baru masuk!");

let audio = new Audio(
"https://actions.google.com/sounds/v1/alarms/beep_short.ogg"
);

audio.play();

location.reload();

}

});

},5000);

</script>

<?php include 'inc/footer.php'; ?>
