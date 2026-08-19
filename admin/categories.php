<?php
require_once 'inc/auth.php';
require_once '../inc/db.php';

// TAMBAH / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = trim($_POST['name']);
    $slug   = trim($_POST['slug']);
    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($_POST['id'])) {

        $stmt = $pdo->prepare("
            UPDATE categories
            SET name=?, slug=?, status=?
            WHERE id=?
        ");

        $stmt->execute([
            $name,
            $slug,
            $status,
            $_POST['id']
        ]);

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO categories
            (name,slug,status)
            VALUES (?,?,?)
        ");

        $stmt->execute([
            $name,
            $slug,
            $status
        ]);
    }

    header("Location: categories.php");
    exit;
}

/* ======================
   MODE EDIT
====================== */
$edit = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("
        SELECT * FROM categories
        WHERE id=?
    ");

    $stmt->execute([$_GET['edit']]);

    $edit = $stmt->fetch();
}

/* ======================
   DELETE
====================== */
if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("
        DELETE FROM categories
        WHERE id=?
    ");

    $stmt->execute([$_GET['delete']]);

    header("Location: categories.php");
    exit;
}

/* ======================
   LIST DATA
====================== */
$categories = $pdo->query("
    SELECT * FROM categories
    ORDER BY id DESC
")->fetchAll();

include 'inc/header.php';
?>

<style>

.form-scroll{
    overflow-x:auto;
}

.form-inner{
    min-width:500px;
}

.table-scroll{
    overflow-x:auto;
}

.table-scroll table{
    min-width:600px;
}

</style>

<h2>Kelola Kategori</h2>

<!-- FORM -->
<div class="card form-scroll">

<div class="form-inner">

<form method="post">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<label>Nama Kategori</label>

<input type="text"
name="name"
required
value="<?= htmlspecialchars($edit['name'] ?? '') ?>">

<label>Slug (URL)</label>

<input type="text"
name="slug"
required
value="<?= htmlspecialchars($edit['slug'] ?? '') ?>">

<label>

<input type="checkbox"
name="status"
<?= (!isset($edit) || ($edit && $edit['status'])) ? 'checked' : '' ?>>

Aktif

</label>

<button type="submit">

<?= $edit ? 'Update Kategori' : 'Tambah Kategori' ?>

</button>

</form>

</div>
</div>

<hr>

<h3>Daftar Kategori</h3>

<div class="table-scroll">

<table width="100%" cellpadding="8" cellspacing="0">

<tr style="background:#222;">
<th>ID</th>
<th>Nama</th>
<th>Slug</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php foreach ($categories as $c): ?>

<tr>

<td><?= $c['id'] ?></td>

<td><?= htmlspecialchars($c['name']) ?></td>

<td><?= htmlspecialchars($c['slug']) ?></td>

<td>

<?php if ($c['status']): ?>

<span class="badge badge-active">Aktif</span>

<?php else: ?>

<span class="badge badge-inactive">Nonaktif</span>

<?php endif; ?>

</td>

<td>

<a href="?edit=<?= $c['id'] ?>">Edit</a>

|

<a href="?delete=<?= $c['id'] ?>"
onclick="return confirm('Hapus kategori ini?')">

Hapus

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include 'inc/footer.php'; ?>