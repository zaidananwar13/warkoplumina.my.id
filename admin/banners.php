<?php
require_once 'inc/auth.php';
require_once '../inc/db.php';

/* ======================
   SIMPAN / UPDATE
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title    = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $active   = isset($_POST['is_active']) ? 1 : 0;
    $image    = $_POST['old_image'] ?? null;

    if (!empty($_FILES['image']['name'])) {

        $allowedMime = ['image/jpeg','image/png'];
        $mime = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($mime, $allowedMime)) {
            die('Format gambar harus JPG atau PNG');
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $image = time() . '-' . rand(100,999) . '.' . $ext;

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/banners/" . $image
        );
    }

    if (!empty($_POST['id'])) {

        $pdo->prepare("
            UPDATE banners 
            SET image=?, title=?, subtitle=?, is_active=?
            WHERE id=?
        ")->execute([
            $image,
            $title,
            $subtitle,
            $active,
            $_POST['id']
        ]);

    } else {

        $pdo->prepare("
            INSERT INTO banners (image,title,subtitle,is_active)
            VALUES (?,?,?,?)
        ")->execute([
            $image,
            $title,
            $subtitle,
            $active
        ]);
    }

    header("Location: banners.php");
    exit;
}

/* ======================
   MODE EDIT
====================== */
$edit = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("
        SELECT * FROM banners
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
        SELECT image FROM banners
        WHERE id=?
    ");

    $stmt->execute([$_GET['delete']]);

    $img = $stmt->fetchColumn();

    if ($img && file_exists("../uploads/banners/".$img)) {
        unlink("../uploads/banners/".$img);
    }

    $pdo->prepare("
        DELETE FROM banners
        WHERE id=?
    ")->execute([$_GET['delete']]);

    header("Location: banners.php");
    exit;
}

/* ======================
   LIST BANNER
====================== */
$banners = $pdo->query("
    SELECT * FROM banners
    ORDER BY id DESC
")->fetchAll();

include 'inc/header.php';
?>

<style>

.form-scroll{
    overflow-x:auto;
}

.form-inner{
    min-width:600px;
}

.table-scroll{
    overflow-x:auto;
}

.table-scroll table{
    min-width:700px;
}

</style>

<h2>Kelola Banner</h2>

<!-- FORM -->
<div class="card form-scroll">

<div class="form-inner">

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
<input type="hidden" name="old_image" value="<?= $edit['image'] ?? '' ?>">

<label>Judul</label>

<input type="text"
name="title"
value="<?= htmlspecialchars($edit['title'] ?? '') ?>">

<label>Sub Judul</label>

<input type="text"
name="subtitle"
value="<?= htmlspecialchars($edit['subtitle'] ?? '') ?>">

<label>Gambar Banner (JPG / PNG)</label>

<input type="file"
name="image"
<?= $edit ? '' : 'required' ?>>

<?php if (!empty($edit['image'])): ?>

<img src="../uploads/banners/<?= $edit['image'] ?>"
style="max-width:100%;margin-top:10px;border-radius:8px;">

<?php endif; ?>

<label>

<input type="checkbox"
name="is_active"
<?= (!isset($edit) || ($edit && $edit['is_active'])) ? 'checked' : '' ?>>

Aktif

</label>

<button type="submit">

<?= $edit ? 'Update Banner' : 'Upload Banner' ?>

</button>

</form>

</div>
</div>

<hr>

<h3>Daftar Banner</h3>

<div class="table-scroll">

<table width="100%">

<tr style="background:#222;">
<th>Preview</th>
<th>Judul</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php foreach ($banners as $b): ?>

<tr>

<td>
<img src="../uploads/banners/<?= $b['image'] ?>" width="140">
</td>

<td><?= htmlspecialchars($b['title']) ?></td>

<td>

<?php if ($b['is_active']): ?>

<span class="badge badge-active">Aktif</span>

<?php else: ?>

<span class="badge badge-inactive">Nonaktif</span>

<?php endif; ?>

</td>

<td>

<a href="?edit=<?= $b['id'] ?>">Edit</a>

|

<a href="?delete=<?= $b['id'] ?>"
onclick="return confirm('Hapus banner?')">

Hapus

</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include 'inc/footer.php'; ?>