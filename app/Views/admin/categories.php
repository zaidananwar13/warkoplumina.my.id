<?php
/**
 * Admin Categories View
 *
 * Variables: $categories (array), $edit (array|null), $admin_role, $current_page
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori - Admin Warkop Lumina</title>
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
        .form-inner { min-width: 500px; }
        .table-scroll { overflow-x: auto; }
        .table-scroll table { min-width: 600px; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../partials/admin_header.php'; ?>

<main class="admin-container">

<h2>Kelola Kategori</h2>

<!-- FORM -->
<div class="card form-scroll">
<div class="form-inner">
<form method="post" action="<?= base_url('admin/categories') ?>">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

    <label>Nama Kategori</label>
    <input type="text" name="name" value="<?= e($edit['name'] ?? '') ?>" required>

    <label>Slug (URL)</label>
    <input type="text" name="slug" value="<?= e($edit['slug'] ?? '') ?>" required>

    <label>
        <input type="checkbox" name="status" value="1"
            <?= ($edit === null || ($edit['status'] ?? 1)) ? 'checked' : '' ?>>
        Aktif
    </label>

    <br><br>
    <button type="submit"><?= $edit ? 'Update' : 'Tambah' ?> Kategori</button>
    <?php if ($edit): ?>
    <a href="<?= base_url('admin/categories') ?>" style="margin-left:10px;">Batal</a>
    <?php endif; ?>
</form>
</div>
</div>

<!-- CATEGORY LIST -->
<div class="table-scroll" style="margin-top:20px;">
<table width="100%" cellpadding="8" cellspacing="0">
<tr style="background:#222;">
    <th>ID</th>
    <th>Nama</th>
    <th>Slug</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
<?php foreach ($categories as $cat): ?>
<tr>
    <td><?= $cat['id'] ?></td>
    <td><?= e($cat['name']) ?></td>
    <td><?= e($cat['slug']) ?></td>
    <td><?= $cat['status'] ? 'Aktif' : 'Nonaktif' ?></td>
    <td>
        <a href="<?= base_url('admin/categories?edit=' . $cat['id']) ?>">Edit</a> |
        <a href="<?= base_url('admin/categories/delete?id=' . $cat['id']) ?>"
           onclick="return confirm('Hapus kategori ini?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

</main>
</body>
</html>
