# PANDUAN INSTALASI HRIS IT/IJK

## Prasyarat

- PHP >= 8.1
- MySQL >= 8.0
- Node.js >= 18
- Composer
- Laragon (atau XAMPP/WAMP)

## Instalasi

### 1. Clone/Download Project

```bash
cd D:\laragon\www
# Copy folder project ke sini
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies Node.js

```bash
npm install
```

### 4. Konfigurasi Environment

Copy `.env.example` ke `.env` dan edit:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hris_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database

Buka phpMyAdmin (`http://localhost/phpmyadmin`) dan buat database `hris_db`.

### 6. Jalankan Migrasi & Seeder

```bash
php artisan migrate
php artisan db:seed
```

### 7. Buat Storage Link

```bash
php artisan storage:link
```

### 8. Build Frontend Assets

```bash
npm run build
```

### 9. Jalankan Server

```bash
php artisan serve --port=8000
```

Buka browser: `http://localhost:8000`

## Akun Default

| Role | Nama | Password |
|------|------|----------|
| Admin | Administrator | password |
| Manager | Budi Manager | password |
| Karyawan | Dewi Karyawan | password |

## Troubleshooting

### Login gagal
- Pastikan database sudah di-seed: `php artisan db:seed`
- Cek koneksi database di `.env`

### Halaman 500 Error
- Jalankan: `php artisan config:clear`
- Jalankan: `php artisan cache:clear`

### CSS tidak tampil
- Jalankan: `npm run build`
- Cek folder `public/build/` ada isi
