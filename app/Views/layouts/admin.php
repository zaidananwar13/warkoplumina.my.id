<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Warkop Lumina Tebet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <style>
        .menu-toggle { display: none; font-size: 26px; cursor: pointer; }
        @media(max-width:768px) {
            .admin-nav {
                position: fixed; top: 0; left: -260px; width: 260px;
                height: 100%; background: #111; flex-direction: column;
                padding-top: 70px; transition: 0.3s; z-index: 999;
            }
            .admin-nav a { padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .admin-nav.show { left: 0; }
            .menu-toggle { display: block; }
        }
    </style>
</head>
<body>

<header class="admin-header">
    <div class="admin-brand">
        <strong>Lumina</strong><span>Admin</span>
    </div>

    <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <nav class="admin-nav" id="adminNav">
        <a href="<?= base_url('admin/dashboard') ?>"
           class="<?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
            Dashboard
        </a>

        <a href="<?= base_url('admin/orders') ?>"
           class="<?= ($current_page ?? '') === 'orders' ? 'active' : '' ?>">
            Order
        </a>

        <?php if (($admin_role ?? '') === 'owner'): ?>
        <a href="<?= base_url('admin/products') ?>"
           class="<?= ($current_page ?? '') === 'products' ? 'active' : '' ?>">
            Produk
        </a>

        <a href="<?= base_url('admin/categories') ?>"
           class="<?= ($current_page ?? '') === 'categories' ? 'active' : '' ?>">
            Kategori
        </a>

        <a href="<?= base_url('admin/settings') ?>"
           class="<?= ($current_page ?? '') === 'settings' ? 'active' : '' ?>">
            Pengaturan
        </a>
        <?php endif; ?>

        <a href="<?= base_url('admin/logout') ?>" class="logout">Logout</a>
    </nav>
</header>

<script>
function toggleMenu() {
    document.getElementById("adminNav").classList.toggle("show");
}
</script>

<main class="admin-container">
    <?= $content ?>
</main>

</body>
</html>
