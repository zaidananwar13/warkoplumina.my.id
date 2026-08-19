<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// proteksi dasar
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: index.php");
    exit;
}

$role    = $_SESSION['admin_role'] ?? 'kasir';
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Warkop Lumina Tebet</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/css/admin.css">

<style>

/* tombol hamburger */
.menu-toggle{
    display:none;
    font-size:26px;
    cursor:pointer;
}

/* mobile nav */
@media(max-width:768px){

.admin-nav{
    position:fixed;
    top:0;
    left:-260px;
    width:260px;
    height:100%;
    background:#111;
    flex-direction:column;
    padding-top:70px;
    transition:0.3s;
    z-index:999;
}

.admin-nav a{
    padding:15px 20px;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.admin-nav.show{
    left:0;
}

.menu-toggle{
    display:block;
}

}

</style>

</head>
<body>

<header class="admin-header">

<div class="admin-brand">
<strong>Lumina</strong><span>Admin</span>
</div>

<div class="menu-toggle" onclick="toggleMenu()">☰</div>

<nav class="admin-nav" id="adminNav">

<a href="dashboard.php"
class="<?= $current=='dashboard.php'?'active':'' ?>">
Dashboard
</a>

<a href="orders.php"
class="<?= $current=='orders.php'?'active':'' ?>">
Order
</a>

<?php if ($role === 'owner'): ?>

<a href="products.php"
class="<?= $current=='products.php'?'active':'' ?>">
Produk
</a>

<a href="categories.php"
class="<?= $current=='categories.php'?'active':'' ?>">
Kategori
</a>

<a href="banners.php"
class="<?= $current=='banners.php'?'active':'' ?>">
Banner
</a>

<a href="reports.php"
class="<?= $current=='reports.php'?'active':'' ?>">
Laporan
</a>

<a href="admins.php"
class="<?= $current=='admins.php'?'active':'' ?>">
Admin
</a>

<a href="settings.php"
class="<?= $current=='settings.php'?'active':'' ?>">
Pengaturan
</a>

<?php endif; ?>

<a href="logout.php" class="logout">Logout</a>

</nav>

</header>

<script>

function toggleMenu(){
document.getElementById("adminNav").classList.toggle("show");
}

</script>

<main class="admin-container">