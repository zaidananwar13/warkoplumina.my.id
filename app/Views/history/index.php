<?php
/**
 * Order History View
 *
 * Variables: $orders (array)
 */
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Warkop Lumina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<div class="app-wrapper">

    <!-- HEADER -->
    <header class="page-header">
        <a href="<?= base_url('/') ?>" class="back-btn">&larr;</a>
        <span class="page-title">Riwayat Pesanan</span>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">&#x1F319;</button>
        </div>
    </header>

    <?php if (empty($orders)): ?>
    <div class="history-empty">
        <div class="history-empty-icon">&#x1F4CB;</div>
        <p>Belum ada riwayat pesanan</p>
        <a href="<?= base_url('/') ?>" class="btn-secondary" style="width:auto;display:inline-flex;margin-top:12px;padding:9px 20px;font-size:13px;">
            Mulai Pesan
        </a>
    </div>
    <?php else: ?>

    <div style="padding-top:8px;">
        <?php foreach ($orders as $order): ?>
        <div class="history-item">
            <div class="history-header">
                <span class="history-code"><?= e($order['order_code']) ?></span>
                <span class="history-date"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></span>
            </div>

            <div class="history-items">
                <?php foreach ($order['items'] as $item): ?>
                <?= e($item['product_name']) ?> x<?= $item['quantity'] ?><br>
                <?php endforeach; ?>
            </div>

            <div class="history-footer">
                <span class="history-total"><?= rupiah($order['total_price']) ?></span>
                <span class="history-status <?= e($order['status']) ?>">
                    <?= match($order['status']) {
                        'pending' => 'Menunggu',
                        'processed' => 'Diproses',
                        'done' => 'Selesai',
                        default => ucfirst($order['status']),
                    } ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</div>

<script src="<?= asset('js/notifications.js') ?>"></script>
<script src="<?= asset('js/theme.js') ?>"></script>
</body>
</html>
