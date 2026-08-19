<?php
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/../inc/db.php';

// HANYA OWNER
if ($_SESSION['admin_role'] !== 'owner') {
    die('Akses ditolak');
}

$success = false;

// simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wa = preg_replace('/[^0-9]/', '', $_POST['whatsapp']);

    $stmt = $pdo->prepare("
        INSERT INTO settings (name,value)
        VALUES ('whatsapp_number',?)
        ON DUPLICATE KEY UPDATE value=?
    ");
    $stmt->execute([$wa,$wa]);
    $success = true;
}

// ambil nomor saat ini
$stmt = $pdo->prepare("SELECT value FROM settings WHERE name='whatsapp_number'");
$stmt->execute();
$current = $stmt->fetchColumn();

include __DIR__ . '/inc/header.php';
?>

<h2>Pengaturan</h2>

<div class="card">
<form method="post">
    <label>Nomor WhatsApp Tujuan</label>
    <input type="text"
           name="whatsapp"
           value="<?= htmlspecialchars($current) ?>"
           placeholder="628xxxxxxxxx"
           required>

    <small style="color:#aaa">
        Gunakan format 628xxx (tanpa +, tanpa spasi)
    </small>

    <br><br>

    <button type="submit">Simpan Nomor WhatsApp</button>

    <?php if ($success): ?>
        <p style="color:#2ecc71;margin-top:10px;">
            Nomor WhatsApp berhasil diperbarui
        </p>
    <?php endif; ?>
</form>
</div>

<?php include __DIR__ . '/inc/footer.php'; ?>
