<?php
/**
 * Admin Header Partial
 *
 * Shared navigation for all admin pages.
 * Variables expected: $admin_role, $current_page
 */
?>
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
