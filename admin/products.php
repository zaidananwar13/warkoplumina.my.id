<?php
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

/* ======================
   AMBIL KATEGORI AKTIF
====================== */
$categories = $pdo->query("
    SELECT * FROM categories
    WHERE status = 1
    ORDER BY name
")->fetchAll();

/* ======================
   SIMPAN / UPDATE PRODUK
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name        = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price       = (int)$_POST['price'];
    $stock       = (int)$_POST['stock'];
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $image       = $_POST['old_image'] ?? null;

    if (!empty($_FILES['image']['name'])) {

        $mime = mime_content_type($_FILES['image']['tmp_name']);
        $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($mime, ['image/jpeg','image/png'])) {
            die('Gambar harus JPG atau PNG');
        }

        $image = time() . '-' . random_int(1000,9999) . '.' . $ext;

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            __DIR__ . '/../uploads/products/' . $image
        );
    }

    if (!empty($_POST['id'])) {

        $stmt = $pdo->prepare("
            UPDATE products SET
                name=?,
                category_id=?,
                price=?,
                stock=?,
                image=?,
                is_active=?
            WHERE id=?
        ");

        $stmt->execute([
            $name,
            $category_id,
            $price,
            $stock,
            $image,
            $is_active,
            $_POST['id']
        ]);

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO products
            (name,category_id,price,stock,image,is_active)
            VALUES (?,?,?,?,?,?)
        ");

        $stmt->execute([
            $name,
            $category_id,
            $price,
            $stock,
            $image,
            $is_active
        ]);
    }

    header("Location: products.php");
    exit;
}

/* ======================
   MODE EDIT
====================== */
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

/* ======================
   FILTER STATUS + KATEGORI
====================== */

$where = [];
$params = [];

if (isset($_GET['status'])) {

    if ($_GET['status'] === 'active') {
        $where[] = 'p.is_active = 1';
    }

    elseif ($_GET['status'] === 'inactive') {
        $where[] = 'p.is_active = 0';
    }
}

if (isset($_GET['cat']) && $_GET['cat'] != '') {
    $where[] = 'p.category_id = ?';
    $params[] = $_GET['cat'];
}

$whereSQL = '';
if ($where) {
    $whereSQL = 'WHERE ' . implode(' AND ', $where);
}

/* ======================
   LIST PRODUK
====================== */

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    $whereSQL
    ORDER BY p.id DESC
");

$stmt->execute($params);
$products = $stmt->fetchAll();

include __DIR__ . '/inc/header.php';
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

.filter-bar{
display:flex;
gap:10px;
align-items:center;
}

</style>

<h2>Kelola Produk</h2>

<div class="card form-scroll">

<div class="form-inner">

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
<input type="hidden" name="old_image" value="<?= $edit['image'] ?? '' ?>">

<label>Nama Produk</label>

<input type="text"
name="name"
required
value="<?= htmlspecialchars($edit['name'] ?? '') ?>">

<div class="row">

<div>

<label>Kategori</label>

<select name="category_id" required>

<?php foreach ($categories as $c): ?>

<option value="<?= $c['id'] ?>"
<?= ($edit && $edit['category_id']==$c['id'])?'selected':'' ?>>

<?= htmlspecialchars($c['name']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div>

<label>Harga (Rp)</label>

<input type="number"
name="price"
required
value="<?= $edit['price'] ?? '' ?>">

</div>

</div>

<label>Stok</label>

<input type="number"
name="stock"
value="<?= $edit['stock'] ?? 0 ?>">

<label>Gambar Produk</label>

<input type="file" name="image">

<?php if (!empty($edit['image'])): ?>

<img src="../uploads/products/<?= $edit['image'] ?>" width="80" style="margin-top:8px;border-radius:6px;">

<?php endif; ?>

<div class="checkbox-row">

<input type="checkbox"
id="is_active"
name="is_active"

<?= (!isset($edit) || ($edit && $edit['is_active'])) ? 'checked' : '' ?>>

<label for="is_active">
Produk Aktif (tampil di website)
</label>

</div>

<button type="submit">
<?= $edit ? 'Update Produk' : 'Tambah Produk' ?>
</button>

</form>

</div>
</div>

<hr>

<div class="filter-bar">

<form method="get">

<label>Tampilkan:</label>

<select name="status">

<option value="">Semua</option>

<option value="active"
<?= (($_GET['status'] ?? '')=='active')?'selected':'' ?>>
Aktif
</option>

<option value="inactive"
<?= (($_GET['status'] ?? '')=='inactive')?'selected':'' ?>>
Nonaktif
</option>

</select>

<label>Kategori:</label>

<select name="cat">

<option value="">Semua</option>

<?php foreach ($categories as $c): ?>

<option value="<?= $c['id'] ?>"
<?= (($_GET['cat'] ?? '')==$c['id'])?'selected':'' ?>>

<?= htmlspecialchars($c['name']) ?>

</option>

<?php endforeach; ?>

</select>

<button type="submit">Filter</button>

<a href="products.php">Reset</a>

</form>

</div>

<h3>Produk Terdaftar</h3>

<div class="table-scroll">

<table width="100%">

<tr>
<th>Gambar</th>
<th>Nama</th>
<th>Kategori</th>
<th>Harga</th>
<th>Stok</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php foreach ($products as $p): ?>

<tr>

<td>
<?php if ($p['image']): ?>
<img src="../uploads/products/<?= $p['image'] ?>" width="50">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($p['name']) ?></td>

<td><?= htmlspecialchars($p['category']) ?></td>

<td><?= rupiah($p['price']) ?></td>

<td><?= $p['stock'] ?></td>

<td>
<?php if ($p['is_active']): ?>
<span class="badge badge-active">Aktif</span>
<?php else: ?>
<span class="badge badge-inactive">Nonaktif</span>
<?php endif; ?>
</td>

<td>
<a href="?edit=<?= $p['id'] ?>">Edit</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php include __DIR__ . '/inc/footer.php'; ?>
