<?php
session_start();
require_once __DIR__ . '/../inc/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {

        // 🔐 SESSION LENGKAP
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user']   = $admin['username'];
        $_SESSION['admin_role']   = $admin['role'] ?: 'owner'; // fallback aman

        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Username atau password salah';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-page">

<div class="login-box">
    <h2>Admin Login</h2>
    <p class="login-subtitle">Warkop Lumina Tebet</p>

    <?php if ($error): ?>
        <div class="login-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
