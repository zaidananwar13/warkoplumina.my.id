<?php
session_start();
require_once __DIR__ . '/inc/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) exit;

$stmt = $pdo->prepare("SELECT id,name,price FROM products WHERE id=? AND is_active=1");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) exit;

if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = [
        'id'    => $product['id'],
        'name'  => $product['name'],
        'price' => $product['price'],
        'qty'   => 1
    ];
} else {
    $_SESSION['cart'][$id]['qty']++;
}
