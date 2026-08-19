<?php
session_start();

$id     = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$id || empty($_SESSION['cart'][$id])) {
    header("Location: cart.php");
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
}

header("Location: cart.php");
exit;
