<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug=? AND status=1");
$stmt->execute([$slug]);
$cat = $stmt->fetch();

if (!$cat) die('Kategori tidak ditemukan');

$stmt = $pdo->prepare("
    SELECT * FROM products
    WHERE category_id=? 
      AND is_active=1 
      AND stock>0
    ORDER BY name
");
$stmt->execute([$cat['id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($cat['name']) ?> - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <a href="index.php" class="back-link">← Kembali</a>

    <h2><?= htmlspecialchars($cat['name']) ?></h2>

    <div class="product-list">
        <?php foreach ($products as $p): ?>
            <div class="product-card">

                <!-- FOTO PRODUK -->
                <?php if (!empty($p['image'])): ?>
                    <img
                        src="uploads/products/<?= htmlspecialchars($p['image']) ?>"
                        alt="<?= htmlspecialchars($p['name']) ?>"
                        class="product-img">
                <?php endif; ?>

                <div class="product-name">
                    <?= htmlspecialchars($p['name']) ?>
                </div>

                <div class="product-price">
                    <?= rupiah($p['price']) ?>
                </div>

                <button onclick="addToCart(<?= (int)$p['id'] ?>)">
                    Tambah
                </button>
            </div>
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
