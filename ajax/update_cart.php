<?php
require_once '../inc/functions.php';

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['product_id'] ?? 0);

if (!isset($_SESSION['cart'][$id])) {
    echo json_encode(['status' => 'error']);
    exit;
}

switch ($action) {
    case 'plus':
        $_SESSION['cart'][$id]['qty']++;
        break;

    case 'minus':
        $_SESSION['cart'][$id]['qty']--;
        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$id]);
        break;

    case 'clear':
        unset($_SESSION['cart']);
        break;
}

$totalItem = 0;
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $c) {
        $totalItem += $c['qty'];
        $totalPrice += $c['price'] * $c['qty'];
    }
}

echo json_encode([
    'status' => 'success',
    'total_item' => $totalItem,
    'total_price' => $totalPrice
]);
