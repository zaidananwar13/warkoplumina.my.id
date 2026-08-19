<?php
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

// total produk
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// total order
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// order hari ini
$todayOrders = $pdo->query("
    SELECT COUNT(*) 
    FROM orders 
    WHERE DATE(created_at) = CURDATE()
")->fetchColumn();

// omzet hari ini
$todayOmzet = $pdo->query("
    SELECT COALESCE(SUM(total_price),0)
    FROM orders
    WHERE DATE(created_at) = CURDATE()
")->fetchColumn();

/* ======================
   DATA GRAFIK 7 HARI
====================== */

$sales = $pdo->query("
SELECT DATE(created_at) tgl,
SUM(total_price) total
FROM orders
GROUP BY DATE(created_at)
ORDER BY tgl DESC
LIMIT 7
")->fetchAll();

$labels=[];
$values=[];

foreach(array_reverse($sales) as $s){

$labels[]=$s['tgl'];
$values[]=$s['total'];

}

include __DIR__ . '/inc/header.php';
?>

<h2>Dashboard</h2>

<div class="dashboard-grid">
</div>

<div class="dashboard-grid">

<div class="dash-card">
    <h3><?= $totalProducts ?></h3>
    <p>Total Produk</p>
</div>

<div class="dash-card">
    <h3><?= $totalOrders ?></h3>
    <p>Total Order</p>
</div>

<div class="dash-card">
    <h3><?= $todayOrders ?></h3>
    <p>Order Hari Ini</p>
</div>

<div class="dash-card highlight">
    <h3><?= rupiah($todayOmzet) ?></h3>
    <p>Omzet Hari Ini</p>
</div>

</div>
</div>

<hr>

<h3>Grafik Omzet 7 Hari Terakhir</h3>

<canvas id="salesChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {

type: 'line',

data: {

labels: <?= json_encode($labels) ?>,

datasets: [{

label: 'Omzet',

data: <?= json_encode($values) ?>,

borderWidth: 3,
tension: 0.3,
fill: true

}]

},

options: {

responsive: true,

plugins:{
legend:{
display:true
}
},

scales:{

y:{
beginAtZero:true
}

}

}

});

</script>

<?php include __DIR__ . '/inc/footer.php'; ?>
