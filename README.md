# Excel Button

Fitur upload dan download data aset via Excel menggunakan PHP Native dan PhpSpreadsheet.

## Requirements

- PHP >= 8.0
- MySQL
- Composer

## Instalasi

**1. Clone repository**
```bash
git clone https://github.com/username/excel-button.git
cd excel-button
```

**2. Install dependencies**
```bash
composer install
```

**3. Buat database**

Buat database `db_aset` di MySQL, lalu import file `db_aset.sql` yang tersedia di repository:

```bash
mysql -u root -p db_aset < db_aset.sql
```

Atau import manual lewat phpMyAdmin.

**4. Sesuaikan koneksi database**

Edit file `koneksi.php`:
```php
$host = 'localhost';
$db   = 'db_aset';
$user = 'root';
$pass = '';
```

**5. Jalankan project**
```bash
php -S localhost:8080
```

Buka browser: `http://localhost:8080`
