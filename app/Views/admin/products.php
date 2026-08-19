<?php
/**
 * Admin Products View
 *
 * Variables: $products (array), $categories (array), $edit (array|null),
 *            $filters (array), $admin_role, $current_page
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk - Admin Warkop Lumina</title>
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
        .form-scroll { overflow-x: auto; }
        .form-inner { min-width: 600px; }
        .table-scroll { overflow-x: auto; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-container">

<h2>Kelola Produk</h2>

<!-- FORM -->
<div class="card form-scroll">
<div class="form-inner">
<form method="post" action="<?= base_url('admin/products') ?>" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
    <input type="hidden" name="old_image" value="<?= e($edit['image'] ?? '') ?>">

    <label>Nama Produk</label>
    <input type="text" name="name" value="<?= e($edit['name'] ?? '') ?>" required>

    <label>Kategori</label>
    <select name="category_id" required>
        <option value="">-- Pilih --</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= (($edit['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
            <?= e($cat['name']) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label>Harga (Rp)</label>
    <input type="number" name="price" value="<?= $edit['price'] ?? '' ?>" required>

    <label>Stok</label>
    <input type="number" name="stock" value="<?= $edit['stock'] ?? '' ?>" required>

    <label>Gambar (JPG/PNG)</label>
    <input type="file" name="image" accept="image/jpeg,image/png">
    <?php if (!empty($edit['image'])): ?>
    <small>Saat ini: <?= e($edit['image']) ?></small>
    <?php endif; ?>

    <label>
        <input type="checkbox" name="is_active" value="1"
            <?= ($edit === null || ($edit['is_active'] ?? 1)) ? 'checked' : '' ?>>
        Aktif
    </label>

    <br><br>
    <button type="submit"><?= $edit ? 'Update' : 'Tambah' ?> Produk</button>
    <?php if ($edit): ?>
    <a href="<?= base_url('admin/products') ?>" style="margin-left:10px;">Batal</a>
    <?php endif; ?>
</form>
</div>
</div>

<!-- FILTERS -->
<div style="margin:20px 0;">
    <a href="<?= base_url('admin/products') ?>">Semua</a> |
    <a href="<?= base_url('admin/products?status=active') ?>">Aktif</a> |
    <a href="<?= base_url('admin/products?status=inactive') ?>">Nonaktif</a>
</div>

<!-- PRODUCT LIST -->
<div class="table-scroll">
<table width="100%" cellpadding="8" cellspacing="0">
<tr style="background:#222;">
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
        <?php if (!empty($p['image'])): ?>
        <img src="<?= upload_url('products/' . e($p['image'])) ?>" width="50" alt="">
        <?php endif; ?>
    </td>
    <td><?= e($p['name']) ?></td>
    <td><?= e($p['category'] ?? '-') ?></td>
    <td><?= rupiah($p['price']) ?></td>
    <td><?= $p['stock'] ?></td>
    <td><?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
    <td>
        <a href="<?= base_url('admin/products?edit=' . $p['id']) ?>">Edit</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

</main>
</body>
</html>
