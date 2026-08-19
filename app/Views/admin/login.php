<?php
/**
 * Admin Login View
 *
 * Variables: $error (string)
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="login-page">

<div class="login-box">
    <h2>Admin Login</h2>
    <p class="login-subtitle">Warkop Lumina Tebet</p>

    <?php if (!empty($error)): ?>
    <div class="login-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/login') ?>">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
