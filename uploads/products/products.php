<?php
require_once 'inc/auth.php';
require_once '../inc/db.php';

// ambil kategori aktif
$categories = $pdo->query("SELECT * FROM categories WHERE status=1 ORDER BY name ASC")->fetchAll();

// TAMBAH / UPDATE PRODUK
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price       = (int)$_POST['price'];
    $stock       = (int)$_POST['stock'];
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    $imageName = $_POST['old_image'] ?? null;

    // upload gambar jika ada
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . '-' . rand(100,999) . '.' . strtolower($ext);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/products/" . $imageName);
    }

    if (!empty($_POST['id'])) {
        // UPDATE
        $stmt = $pdo->prepare("
            UPDATE products 
            SET category_id=?, name=?, price=?, stock=?, image=?, is_active=?
            WHERE id=?
        ");
        $stmt->execute([
            $category_id, $name, $price, $stock, $imageName, $is_active, $_POST['id']
        ]);
    } else {
        // INSERT
        $stmt = $pdo->prepare("
            INSERT INTO products (category_id, name, price, stock, image, is_active)
            VALUES (?,?,?,?,?,?)
        ");
        $stmt->execute([
            $category_id, $name, $price, $stock, $imageName, $is_active
        ]);
    }

    header("Location: products.php");
    exit;
}

// EDIT DATA
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

// DELETE PRODUK
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists("../uploads/products/" . $img)) {
        unlink("../uploads/products/" . $img);
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$_GET['delete']]);

    header("Location: products.php");
    exit;
}

// LIST PRODUK
$products = $pdo->query("
    SELECT p.*, c.name AS category 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();
?>

<?php include 'inc/header.php'; ?>

<h2>Kelola Produk</h2>

<!-- FORM PRODUK -->
<div class="card">
<form method="post" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
    <input type="hidden" name="old_image" value="<?= $edit['image'] ?? '' ?>">

    <label>Nama Produk</label>
    <input type="text" name="name" required
           value="<?= htmlspecialchars($edit['name'] ?? '') ?>">

    <label>Kategori</label>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= ($edit && $edit['category_id']==$cat['id'])?'selected':'' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Harga (Rp)</label>
    <input type="number" name="price" required
           value="<?= $edit['price'] ?? '' ?>">

    <label>Stok</label>
    <input type="number" name="stock"
           value="<?= $edit['stock'] ?? 0 ?>">

    <label>Gambar Produk (JPG / PNG)</label>
    <input type="file" name="image">

    <?php if (!empty($edit['image'])): ?>
        <p>
            <img src="../uploads/products/<?= $edit['image'] ?>" width="80">
        </p>
    <?php endif; ?>

    <label>
        <input type="checkbox" name="is_active"
            <?= (!isset($edit) || ($edit && $edit['is_active'])) ? 'checked' : '' ?>>
        Aktif
    </label>

    <button type="submit">
        <?= $edit ? 'Update Produk' : 'Tambah Produk' ?>
    </button>
</form>
</div>

<hr>

<!-- LIST PRODUK -->
<table width="100%" cellpadding="8" cellspacing="0">
    <tr style="background:#222;">
        <th>ID</th>
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
        <td><?= $p['id'] ?></td>
        <td>
            <?php if ($p['image']): ?>
                <img src="../uploads/products/<?= $p['image'] ?>" width="50">
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= htmlspecialchars($p['category']) ?></td>
        <td><?= rupiah($p['price']) ?></td>
        <td><?= $p['stock'] ?></td>
        <td><?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
        <td>
            <a href="?edit=<?= $p['id'] ?>">Edit</a> |
            <a href="?delete=<?= $p['id'] ?>"
               onclick="return confirm('Hapus produk ini?')">
               Hapus
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'inc/footer.php'; ?>
