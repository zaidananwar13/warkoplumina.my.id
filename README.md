# Warkop Lumina

Sistem pemesanan online untuk Warkop Lumina Tebet. Customer memesan dari kamar, pesanan dikirim ke admin via WhatsApp, dan admin mengelola order dari panel admin.

## Tech Stack

- PHP 8.0+ (MVC tanpa framework)
- MySQL / MariaDB
- Vanilla JS
- PHPUnit 10

## Struktur Proyek

```
app/
├── Controllers/        # Handle request (public + admin)
├── Core/               # Framework: Router, Database, View, Session, dll
├── Helpers/            # Global functions (rupiah, base_url, e, dll)
├── Models/             # Data access layer (Category, Product, Order, dll)
├── Services/           # Business logic (Cart, Order, WhatsApp, Notification)
├── Views/              # Template files
config/                 # App, database, whatsapp config
routes/web.php          # Semua route definitions
public/                 # Document root (index.php, assets, uploads)
tests/                  # PHPUnit unit tests
storage/                # File-based storage (notifications)
```

## Instalasi

```bash
# Clone
git clone <repo-url>
cd warkoplumina.my.id

# Install dependencies
composer install

# Setup environment
cp .env.example .env
# Edit .env sesuai konfigurasi database kamu
```

## Setup Database

Buat database MySQL dan import tabel berikut:

```sql
CREATE DATABASE sina6694_warkoplumina;
USE sina6694_warkoplumina;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    status TINYINT DEFAULT 1
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    category_id INT,
    price INT NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    is_active TINYINT DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(50) NOT NULL,
    customer_name VARCHAR(100),
    room_number VARCHAR(50),
    total_price INT NOT NULL,
    payment_method VARCHAR(20) DEFAULT 'Cash',
    status VARCHAR(20) DEFAULT 'pending',
    created_at DATETIME
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_name VARCHAR(200),
    quantity INT,
    price INT,
    subtotal INT,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'owner'
);

CREATE TABLE settings (
    name VARCHAR(50) PRIMARY KEY,
    value VARCHAR(255)
);

-- Default admin (password: admin123)
INSERT INTO admins (username, password, role)
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'owner');
```

Untuk generate password hash:

```php
echo password_hash('admin123', PASSWORD_DEFAULT);
```

## Menjalankan

```bash
# Development server
php -S localhost:8080 server.php

# Buka browser
open http://localhost:8080
```

## Menjalankan Tests

```bash
composer test
```

## Fitur

### Customer (Public)
- Browse menu berdasarkan kategori
- Tambah ke keranjang (session-based)
- Checkout dengan Cash / QRIS
- Kirim pesanan via WhatsApp
- Riwayat pesanan
- Notifikasi realtime saat status pesanan berubah
- Dark / Light mode toggle
- Auto-fill nama dan kamar dari pesanan sebelumnya

### Admin Panel (`/admin/login`)
- Dashboard dengan statistik dan grafik omzet
- CRUD Produk (dengan upload gambar)
- CRUD Kategori
- Manajemen Order (update status: pending → diproses → selesai)
- Pengaturan nomor WhatsApp
- Role-based access (owner / kasir)

## Environment Variables

| Variable | Keterangan |
|----------|-----------|
| `APP_BASE_URL` | Base URL aplikasi |
| `APP_DEBUG` | Mode debug (true/false) |
| `DB_HOST` | MySQL host |
| `DB_NAME` | Nama database |
| `DB_USER` | Username database |
| `DB_PASS` | Password database |
| `WA_ADMIN` | Nomor WhatsApp admin (format 628xx) |

## Deployment (Apache)

Arahkan document root ke folder `public/` dan pastikan `mod_rewrite` aktif.

```apache
<VirtualHost *:80>
    DocumentRoot /path/to/warkoplumina.my.id/public
    <Directory /path/to/warkoplumina.my.id/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## License

MIT
