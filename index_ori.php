<?php
require_once __DIR__ . '/inc/db.php';

// ambil banner aktif (1 terakhir)
$banner = $pdo->query("
    SELECT image 
    FROM banners 
    WHERE is_active = 1 
    ORDER BY id DESC 
    LIMIT 1
")->fetch();

// ambil kategori aktif
$categories = $pdo->query("
    SELECT * 
    FROM categories 
    WHERE status = 1 
    ORDER BY name
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Warkop Lumina Tebet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h1>Warkop Lumina</h1>
    <p class="subtitle">Pesan langsung dari kamar</p>
    <div class="banner">
  <img src="assets/img/banner2.png" alt="Promo Warkop Lumina">
</div>


    <!-- BANNER -->
    <?php if ($banner): ?>
        <div class="banner">
            <img 
                src="uploads/banners/<?= htmlspecialchars($banner['image']) ?>" 
                alt="Promo Warkop Lumina">
        </div>
    <?php endif; ?>

    <!-- KATEGORI -->
    <div class="category-grid">
        <?php foreach ($categories as $c): ?>
            <a href="category.php?slug=<?= urlencode($c['slug']) ?>"
               class="category-card">
                <?= htmlspecialchars($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

</div>

<!-- FLOATING CART -->
<a href="cart.php" id="floating-cart">
    🛒 <span id="cart-count">0</span>
</a>

<script src="assets/js/cart.js"></script>
</body>
</html>
