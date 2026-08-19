<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/inc/functions.php';

// ===============================
// AMBIL DATA FORM
// ===============================
$nama        = trim($_POST['nama'] ?? '');
$kamar       = trim($_POST['kamar'] ?? '');
$pembayaran  = $_POST['pembayaran'] ?? '-';
$uang        = (int)($_POST['uang'] ?? 0);

// ===============================
// AMBIL CART
// ===============================
$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
    die('Keranjang kosong');
}

// ===============================
// HITUNG TOTAL & ITEM
// ===============================
$total = 0;
$items = '';

foreach ($cart as $item) {
    $subtotal = $item['price'] * $item['qty'];
    $total += $subtotal;

    // Gunakan newline asli. Encoding dilakukan SEKALI di bagian URL.
    $items .= "• {$item['name']} x{$item['qty']} = " . rupiah($subtotal) . "\n";
}

// ===============================
// FALLBACK DATA
// ===============================
$nama  = $nama ?: '-';
$kamar = $kamar ?: '-';

// ===============================
// HITUNG KEMBALIAN (CASH)
// ===============================
$kembalian = '';

if ($pembayaran === 'Cash' && $uang > 0) {
    $kembalian  = "\n\u{1F4B0} Uang Dibayar: " . rupiah($uang);
    $kembalian .= "\n\u{1F4B8} Kembalian: " . rupiah($uang - $total);
}

// ===============================
// NOMOR WHATSAPP TUJUAN
// ===============================
$wa = '6285211170118'; // format 628xxx, tanpa tanda +

// ===============================
// SUSUN PESAN
// ===============================
// Pesan dibuat sebagai UTF-8 normal terlebih dahulu.
// Emoji, bullet, newline dan karakter khusus kemudian
// di-encode otomatis oleh http_build_query().
$pesan =
    "\u{1F6D2} *Pesanan Warkop Lumina*\n\n" .
    $items .
    "\n*Total: " . rupiah($total) . "*\n\n" .
    "\u{1F464} Nama: {$nama}\n" .
    "\u{1F4CD} Kamar: {$kamar}\n" .
    "\u{1F4B3} Pembayaran: {$pembayaran}" .
    $kembalian .
    "\n\n\u{1F64F} Terima kasih";

// ===============================
// CLEAR CART (PENTING)
// ===============================
unset($_SESSION['cart']);

// OPTIONAL: bersihkan data checkout lain
unset($_SESSION['checkout']);

// ===============================
// REDIRECT KE WHATSAPP
// ===============================
// Encode seluruh pesan SATU KALI agar:
// - emoji tampil normal
// - bullet • tampil normal
// - newline tetap menjadi baris baru
// - karakter khusus tidak merusak URL
$encodedPesan = rawurlencode($pesan);

header("Location: https://wa.me/{$wa}?text={$encodedPesan}");
exit;
