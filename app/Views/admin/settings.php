<?php
/**
 * Admin Settings View
 *
 * Variables: $whatsapp (string), $success (bool), $admin_role, $current_page
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan - Admin Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <style>
        .menu-toggle { display: none; font-size: 26px; cursor: pointer; }
        @media(max-width:768px) {
            .admin-nav { position: fixed; top: 0; left: -260px; width: 260px; height: 100%; background: #111; flex-direction: column; padding-top: 70px; transition: 0.3s; z-index: 999; }
            .admin-nav a { padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .admin-nav.show { left: 0; }
            .menu-toggle { display: block; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-container">

<h2>Pengaturan</h2>

<div class="card">
<form method="post" action="<?= base_url('admin/settings') ?>">
    <label for="whatsapp">Nomor WhatsApp Tujuan</label>
    <input type="text" id="whatsapp" name="whatsapp"
           value="<?= e($whatsapp) ?>"
           placeholder="628xxxxxxxxx" required>

    <small style="color:#aaa">
        Gunakan format 628xxx (tanpa +, tanpa spasi)
    </small>

    <br><br>
    <button type="submit">Simpan Nomor WhatsApp</button>

    <?php if ($success): ?>
    <p style="color:#2ecc71; margin-top:10px;">
        Nomor WhatsApp berhasil diperbarui
    </p>
    <?php endif; ?>
</form>
</div>

</main>
</body>
</html>
