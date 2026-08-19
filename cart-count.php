<?php
session_start();

$count = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += (int)$item['qty'];
    }
}

header('Content-Type: application/json');
echo json_encode([
    'count' => $count
]);
