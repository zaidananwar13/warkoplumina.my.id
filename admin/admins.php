<?php
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/../inc/db.php';

// HANYA OWNER
if ($_SESSION['admin_role'] !== 'owner') {
    die('Akses ditolak');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $pdo->prepare("
        INSERT INTO admins (username,password,role)
        VALUES (?,?,?)
    ");
    $stmt->execute([$username,$password,$role]);

    $success = true;
}

$admins = $pdo->query("SELECT username, role FROM admins ORDER BY role DESC")->fetchAll();

include __DIR__ . '/inc/header.php';
?>

<h2>Kelola Admin</h2>

<div class="card">
<form method="post">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Role</label>
    <select name="role">
        <option value="kasir">Kasir</option>
        <option value="owner">Owner</option>
    </select>

    <button type="submit">Tambah Admin</button>

    <?php if (!empty($success)): ?>
        <p style="color:#2ecc71;margin-top:10px;">Admin berhasil ditambahkan</p>
    <?php endif; ?>
</form>
</div>

<hr>

<h3>Daftar Admin</h3>
<table width="100%">
<tr>
    <th>Username</th>
    <th>Role</th>
</tr>
<?php foreach ($admins as $a): ?>
<tr>
    <td><?= htmlspecialchars($a['username']) ?></td>
    <td><?= ucfirst($a['role']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php include __DIR__ . '/inc/footer.php'; ?>
