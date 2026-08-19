<?php
/**
 * Admin Dashboard View
 *
 * Variables: $stats (array), $admin_role, $admin_user, $current_page
 */

$labels = json_encode(array_column($stats['sales_chart'], 'date'));
$values = json_encode(array_column($stats['sales_chart'], 'total'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin Warkop Lumina</title>
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
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-container">

<h2>Dashboard</h2>

<div class="dashboard-grid">
    <div class="dash-card">
        <h3><?= $stats['total_products'] ?></h3>
        <p>Total Produk</p>
    </div>
    <div class="dash-card">
        <h3><?= $stats['total_orders'] ?></h3>
        <p>Total Order</p>
    </div>
    <div class="dash-card">
        <h3><?= $stats['today_orders'] ?></h3>
        <p>Order Hari Ini</p>
    </div>
    <div class="dash-card highlight">
        <h3><?= rupiah($stats['today_revenue']) ?></h3>
        <p>Omzet Hari Ini</p>
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
        labels: <?= $labels ?>,
        datasets: [{
            label: 'Omzet (Rp)',
            data: <?= $values ?>,
            borderColor: '#e67e22',
            backgroundColor: 'rgba(230,126,34,0.1)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</main>
</body>
</html>
