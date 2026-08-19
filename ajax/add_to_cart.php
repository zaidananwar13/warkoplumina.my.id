<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';

$id = (int)($_POST['product_id'] ?? 0);

// ambil produk + stok
$stmt = $pdo->prepare("
    SELECT id, name, price, stock
    FROM products
    WHERE id = ? AND is_active = 1 AND stock > 0
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Produk tidak tersedia'
    ]);
    exit;
}

// validasi stok vs cart
if (isset($_SESSION['cart'][$id]) &&
    $_SESSION['cart'][$id]['qty'] >= $product['stock']) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Stok habis'
    ]);
    exit;
}

// tambah ke cart
if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = [
        'name'  => $product['name'],
        'price' => $product['price'],
        'qty'   => 1
    ];
} else {
    $_SESSION['cart'][$id]['qty']++;
}

// hitung total item
$totalItem = 0;
foreach ($_SESSION['cart'] as $c) {
    $totalItem += $c['qty'];
}

echo json_encode([
    'status' => 'success',
    'total_item' => $totalItem
]);
