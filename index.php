<?php
require_once __DIR__ . '/inc/db.php';

// Banner front page dipanggil langsung dari file:
// assets/img/banner2.png
// Pastikan file tersebut adalah banner Warkop Lumina 757 x 235 px.

// Ambil kategori aktif
$categories = $pdo->query("
    SELECT *
    FROM categories
    WHERE status = 1
    ORDER BY name
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Warkop Lumina Tebet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080808">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Front page Warkop Lumina */
        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at 50% 0%, #1c1c1c 0%, #0b0b0b 42%, #050505 100%);
        }

        .container {
            width: min(757px, calc(100% - 28px)) !important;
            max-width: 757px !important;
            margin: 0 auto;
            padding: 26px 0 100px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .brand-header h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(25px, 4vw, 34px);
            font-weight: 800;
            letter-spacing: .2px;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #9b9b9b;
            font-size: 14px;
        }

        .open-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 10px;
            padding: 6px 11px;
            border-radius: 999px;
            background: rgba(35, 35, 35, .85);
            color: #d8d8d8;
            font-size: 12px;
        }

        .open-status .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #35c759;
            box-shadow: 0 0 8px rgba(53,199,89,.7);
        }

        .banner {
            width: 100%;
            margin: 0 auto 22px;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0,0,0,.38);
        }

        .banner img {
            display: block;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            aspect-ratio: auto !important;
            object-fit: initial !important;
        }

        .section-title {
            margin: 0 0 12px 2px;
            color: #fff;
            font-size: 16px;
            font-weight: 750;
        }

        .category-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .category-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 64px;
            box-sizing: border-box;
            padding: 14px 20px;
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 14px;
            background: linear-gradient(135deg, #1d1d1d, #131313);
            color: #f2c94c;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 5px 18px rgba(0,0,0,.24);
            transition: transform .18s ease, border-color .18s ease, background .18s ease;
        }

        .category-card::after {
            content: "›";
            color: #777;
            font-size: 24px;
            font-weight: 400;
            line-height: 1;
        }

        .category-card:hover {
            transform: translateY(-2px);
            border-color: rgba(242,201,76,.28);
            background: linear-gradient(135deg, #242424, #171717);
        }

        .category-card:active {
            transform: scale(.99);
        }

        #floating-cart {
            position: fixed;
            z-index: 1000;
            left: 50%;
            bottom: 18px;
            transform: translateX(-50%);
            width: min(757px, calc(100% - 28px));
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 52px;
            padding: 12px 18px;
            border: 1px solid rgba(242,201,76,.25);
            border-radius: 14px;
            background: rgba(20,20,20,.94);
            color: #f2c94c;
            text-decoration: none;
            font-size: 15px;
            font-weight: 750;
            box-shadow: 0 10px 30px rgba(0,0,0,.55);
            backdrop-filter: blur(10px);
        }

        #cart-count {
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #f2c94c;
            color: #111;
            font-size: 12px;
            font-weight: 800;
        }

        @media (max-width: 600px) {
            .container {
                width: calc(100% - 24px);
                padding-top: 20px;
            }

            .banner {
                border-radius: 12px;
                margin-bottom: 18px;
            }

            .category-card {
                min-height: 58px;
                padding: 13px 16px;
            }

            #floating-cart {
                width: calc(100% - 24px);
                bottom: 12px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <header class="brand-header">
        <h1>Warkop Lumina</h1>
        <p class="subtitle">Pesan makanan &amp; minuman langsung dari kamar</p>
        <div class="open-status">
            <span class="dot"></span>
            Pesanan sedang dibuka
        </div>
    </header>

    <!-- BANNER UTAMA -->
    <div class="banner">
        <img
            src="assets/img/banner2.png"
            alt="Banner Warkop Lumina 757 x 235">
    </div>

    <!-- KATEGORI -->
    <h2 class="section-title">Menu Warkop Lumina</h2>

    <div class="category-grid">
        <?php foreach ($categories as $c): ?>
            <a
                href="category.php?slug=<?= urlencode($c['slug']) ?>"
                class="category-card">
                <span><?= htmlspecialchars($c['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>

</div>

<!-- FLOATING CART -->
<a href="cart.php" id="floating-cart" aria-label="Buka keranjang">
    🛒 <span>Keranjang</span>
    <span id="cart-count">0</span>
</a>

<script src="assets/js/cart.js"></script>
</body>
</html>
