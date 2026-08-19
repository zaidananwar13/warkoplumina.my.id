<?php
/**
 * Homepage View
 *
 * Variables: $categories (array)
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Warkop Lumina Tebet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080808">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(circle at 50% 0%, #1c1c1c 0%, #0b0b0b 42%, #050505 100%);
        }
        .container {
            width: min(757px, calc(100% - 28px)) !important;
            max-width: 757px !important;
            margin: 0 auto;
            padding: 26px 0 100px;
        }
        .brand-header { text-align: center; margin-bottom: 20px; }
        .brand-header h1 {
            margin: 0; color: #fff;
            font-size: clamp(25px, 4vw, 34px);
            font-weight: 800; letter-spacing: .2px;
        }
        .subtitle { margin: 6px 0 0; color: #9b9b9b; font-size: 14px; }
        .open-status {
            display: inline-flex; align-items: center; gap: 7px;
            margin-top: 10px; padding: 6px 11px; border-radius: 999px;
            background: rgba(35,35,35,.85); color: #d8d8d8; font-size: 12px;
        }
        .open-dot { width: 8px; height: 8px; border-radius: 50%; background: #2ecc71; }
        .banner-img {
            width: 100%; border-radius: 12px; margin: 18px 0;
            display: block;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px; margin-top: 20px;
        }
        .cat-card {
            background: rgba(30,30,30,.9); border-radius: 10px;
            padding: 18px 14px; text-align: center;
            text-decoration: none; color: #fff;
            transition: background .2s;
        }
        .cat-card:hover { background: rgba(50,50,50,.95); }
        .cat-card .cat-name { font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">

    <div class="brand-header">
        <h1>Warkop Lumina</h1>
        <p class="subtitle">Tebet, Jakarta Selatan</p>
        <div class="open-status">
            <span class="open-dot"></span> Buka Sekarang
        </div>
    </div>

    <img src="<?= asset('img/banner2.png') ?>" alt="Banner Warkop Lumina" class="banner-img">

    <h3 style="color:#fff; margin-top:24px;">Pilih Kategori</h3>

    <div class="category-grid">
        <?php foreach ($categories as $cat): ?>
        <a href="<?= base_url('category/' . e($cat['slug'])) ?>" class="cat-card">
            <div class="cat-name"><?= e($cat['name']) ?></div>
        </a>
        <?php endforeach; ?>
    </div>

</div>

<!-- FLOATING CART -->
<a href="<?= base_url('cart') ?>" id="floating-cart">
    &#x1F6D2; <span id="cart-count">0</span>
</a>

<script src="<?= asset('js/cart.js') ?>"></script>
</body>
</html>
