# HR Management System (HRIS)

**Aplikasi Web Manajemen Karyawan Berbasis Laravel**

Sistem Informasi Manajemen Sumber Daya Manusia (HRIS) berbasis web yang dikembangkan menggunakan Laravel 11. Sistem ini dirancang untuk mendukung administrasi kepegawaian secara terintegrasi.

## Fitur Utama

### 1. Manajemen Data Karyawan
- CRUD data karyawan lengkap (profil, jabatan, satuan kerja, foto)
- Import data karyawan dari Excel (.xlsx, .xls, .csv)
- Export data karyawan ke Excel
- Filter dan pencarian data

### 2. Manajemen Jabatan & Satuan Kerja
- Kelola data jabatan dengan komponen gaji
- Kelola struktur satuan kerja/departemen
- Relasi kepala satuan kerja

### 3. Sistem Absensi
- **Absensi Online** - Karyawan dapat absen langsung melalui website
- **Input Manual** - Admin dapat menambahkan data absensi
- **Import/Export Excel** - Import dari mesin fingerprint, export rekap
- **Filter** - Filter berdasarkan tanggal, karyawan, status kehadiran
- Status: Hadir, Izin, Sakit, Alpha

### 4. Pengajuan Izin
- Pengajuan izin (Sakit, Cuti, Izin Khusus) dengan upload dokumen
- Sistem approval (Setujui / Tolak) oleh Admin/Atasan
- Modal verifikasi dengan catatan
- Riwayat pengajuan lengkap

### 5. Penugasan Tugas
- Atasan membuat dan menugaskan tugas
- Karyawan update status (Baru, Diproses, Selesai, Terlambat)
- Upload bukti penyelesaian
- Monitoring progres tugas

### 6. Penilaian Kinerja
- Penilaian periodik dengan 5 kriteria (Disiplin, Kualitas, Tanggung Jawab, Komunikasi, Inisiatif)
- Perhitungan total nilai otomatis
- Riwayat penilaian per karyawan

### 7. Perhitungan Gaji (Payroll)
- Perhitungan gaji otomatis berdasarkan jabatan dan absensi
- Komponen: Gaji Pokok, Tunjangan Jabatan, Transport, Makan
- Potongan otomatis berdasarkan absensi (alpha/izin)
- Slip gaji digital (cetak/download)
- Status pembayaran (Pending/Dibayar)

### 8. Manajemen Dokumen
- Upload dokumen dengan berbagai tipe (Kontrak, SK, Sertifikat, Personal, Lainnya)
- Download dokumen dengan kontrol akses per role
- Filter berdasarkan tipe dokumen dan karyawan
- Upload file multi-format (PDF, DOC, XLS, Gambar, dll)

### 9. Manajemen Komponen Gaji
- Kelola komponen penambahan (tunjangan) dan potongan (BPJS, dll)
- Perhitungan gaji otomatis menyertakan komponen dari database
- Riwayat perubahan komponen gaji

### 10. Sistem Hak Akses (RBAC)
- **Admin** - Akses penuh ke semua fitur
- **Atasan** - Operasional, approval izin, penugasan, penilaian
- **Karyawan** - Lihat data pribadi, absensi, izin, tugas

## Teknologi

- **Backend**: Laravel 11, PHP 8.3+
- **Database**: MySQL 8.0 (utf8mb4)
- **Frontend**: Blade, Tailwind CSS, Alpine.js, FontAwesome 6
- **Auth**: Laravel Authentication + 2FA (Fortify-ready)
- **RBAC**: Spatie Laravel Permission (3 Role: Admin, Atasan, Karyawan)
- **Import/Export**: Maatwebsite Laravel Excel (Employee & Attendance)
- **Build Tools**: Vite, PostCSS
- **Security**: CSRF, XSS Protection, Encrypted Fields (AES-256), Rate Limiting, Security Headers

## Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- MySQL
- Node.js & NPM

### Langkah Instalasi

```bash
# 1. Clone atau copy project
cd /path/to/project

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install

# 4. Copy .env & konfigurasi
cp .env.example .env
# Edit .env, atur koneksi database:
# DB_DATABASE=hris_db
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Generate key
php artisan key:generate

# 6. Buat database
# Buka phpMyAdmin atau MySQL CLI:
# CREATE DATABASE hris_db;

# 7. Jalankan migrasi
php artisan migrate

# 8. Jalankan seeder (data sample)
php artisan db:seed

# 9. Buat storage link
php artisan storage:link

# 10. Build assets
npm run build

# 11. Jalankan server
php artisan serve
```

### Login (Data Sample)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hr.test | password |
| Atasan | budi@hr.test | password |
| Karyawan | dewi@hr.test | password |

## Struktur Direktori

```
app/
├── Exports/         # Export kelas Excel
├── Http/
│   ├── Controllers/ # Controller untuk setiap modul
│   └── Middleware/
├── Imports/         # Import kelas Excel
├── Models/          # Eloquent Models
└── Providers/
database/
├── migrations/      # Schema database
├── seeders/         # Data seeder
└── factories/
resources/
├── css/             # Tailwind CSS
├── js/              # JavaScript (React/Vue)
└── views/           # Blade templates
    ├── absensi/
    ├── auth/
    ├── izin/
    ├── jabatan/
    ├── karyawan/
    ├── layouts/
    ├── penggajian/
    ├── penilaian/
    ├── dokumen/
    ├── satuan-kerja/
    └── tugas/
routes/
└── web.php          # Route definitions
```

## Penggunaan

### Import Excel Karyawan
1. Buka menu **Karyawan**
2. Klik **Pilih File** pada form Import Excel
3. Pilih file Excel dengan format:
   - Baris 1: Header (nik, nama_lengkap, tempat_lahir, dll)
   - Kolom jabatan_id dan satuan_kerja_id diisi ID yang sudah ada
4. Klik **Import Excel**

### Absensi Online
1. Buka menu **Absensi**
2. Klik **Absen Online**
3. Pilih nama karyawan
4. Klik **Absen Sekarang**

### Pengajuan Izin
1. Buka menu **Pengajuan Izin**
2. Klik **Ajukan Izin**
3. Isi jenis izin, periode, alasan, dan upload bukti
4. Admin/Atasan akan melakukan verifikasi

## Keamanan

- Autentikasi Laravel dengan session
- RBAC (Spatie Permission) untuk 3 role
- Validasi input di server-side
- CSRF protection
- Enkripsi data sensitif (alamat, telepon, rekening)
- Logging aktivitas pengguna
- Rate limiting pada login

## Lisensi

Hak Cipta © 2024 - Proyek Pengembangan Aplikasi Web Manajemen Karyawan
