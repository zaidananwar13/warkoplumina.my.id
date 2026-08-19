<?php
session_start();

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: index.php");
    exit;
}

// fallback aman
if (!isset($_SESSION['admin_role'])) {
    $_SESSION['admin_role'] = 'owner';
}
