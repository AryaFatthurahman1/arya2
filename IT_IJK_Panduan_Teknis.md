# IT/IJK PANDUAN TEKNIS & INSTRUKSI KERJA
## PENGEMBANGAN APLIKASI WEB MANAJEMEN PROJEK INTERFACES
### (HRIS Modern dengan React, Vue.js, Chart.js, & Notifikasi Real-time)

Dokumen ini merupakan **Instruksi Kerja (IJK)** dan **Panduan Teknis (IT)** untuk implementasi, konfigurasi, pengoperasian, serta audit keamanan pada seluruh aplikasi web dalam portofolio proyek.

---

## 1. Arsitektur Global & Komponen Sistem

Sistem ini terdiri dari modul aplikasi utama yang modern dan komprehensif untuk pengelolaan data perusahaan:

### 1.1 Modul Utama: HR Management System (HRIS) - Enhanced
Aplikasi manajemen sumber daya manusia berbasis Laravel 11 dengan UI modern.
*   **Struktur Direktori**: `d:\laragon\www\Pengembangan Aplikasi Web Management Projek Interfaces_Laravel`
*   **Teknologi**: 
    - Backend: Laravel 11, PHP 8.2+, MySQL
    - Frontend: Tailwind CSS, React.js, Vue.js 3, Alpine.js
    - Visualisasi: Chart.js 4.x
    - Keamanan: Spatie Permission (RBAC), AES-256 Encryption
    - Import/Export: Maatwebsite Excel
*   **Fitur Utama**:
    - Manajemen Karyawan dengan Import/Export Excel
    - Absensi Online & Import dari Mesin Fingerprint
    - Pengajuan Izin/Sakit/Cuti dengan Upload Dokumen
    - Penugasan Tugas dengan Status Tracking
    - Penilaian Kinerja (KPI) dengan 5 Indikator
    - Penggajian Otomatis & Slip Gaji Digital
    - Sistem Dokumen dengan Support Multi-format (PDF, Word, Excel, Images)
    - Dashboard dengan Data Visualization Real-time
    - Sistem Notifikasi Real-time
    - UI Modern dengan Glassmorphism & Gradients

### 1.2 Fitur-Fitur Baru & Peningkatan UI/UX

#### 1.2.1 Modern UI Design
- **Tailwind CSS Enhanced**: Menggunakan gradient backgrounds, glassmorphism effects, dan smooth transitions
- **Responsive Layout**: Sidebar navigation dengan backdrop blur, card dengan hover effects
- **Color Scheme**: Indigo-Purple gradient theme dengan modern color palette
- **Typography**: Google Fonts (Inter) untuk keterbacaan optimal

#### 1.2.2 React.js Components
- **Dashboard Stats**: Komponen statistik dengan animasi hover dan gradient text
- **Attendance Chart**: Visualisasi data absensi mingguan dengan bar chart interaktif
- **File Upload Component**: Drag & drop file upload dengan preview dan progress indicator
- **Location**: `resources/js/react.jsx`

#### 1.2.3 Vue.js Components
- **Dashboard Stats (Vue)**: Alternatif implementasi statistik dashboard
- **Location**: `resources/js/vue.js` dan `resources/js/components/DashboardStats.vue`

#### 1.2.4 Data Visualization (Chart.js)
- **Line Chart**: Statistik kehadiran 7 hari terakhir
- **Doughnut Chart**: Distribusi status karyawan (Tetap, Kontrak, Percobaan)
- **Location**: Terintegrasi di `resources/views/dashboard.blade.php`

#### 1.2.5 Real-time Notifications
- **Notification System**: Notifikasi untuk izin pending, tugas baru, dan absensi alpha
- **Auto-refresh**: Update otomatis setiap 30 detik
- **Dropdown UI**: Panel notifikasi dengan Alpine.js
- **Location**: `app/Http/Controllers/NotificationController.php`

#### 1.2.6 Enhanced File Upload
- **Multi-format Support**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV, ZIP, JPG, JPEG, PNG, GIF, SVG
- **Max File Size**: 20MB per file
- **Security**: File type validation, secure storage path
- **Location**: `app/Http/Controllers/DokumenController.php`

---

## 2. Struktur Database & Normalisasi

### 2.1 Schema Database HRIS (`hris_db` - MySQL)
Database ini dirancang dengan struktur ternormalisasi (3NF) guna menjamin integritas data:

```mermaid
ergencyDiagram
    users ||--o| karyawan : "user_id"
    jabatan ||--o{ karyawan : "jabatan_id"
    satuan_kerja ||--o{ karyawan : "satuan_kerja_id"
    karyawan ||--o{ absensi : "karyawan_id"
    karyawan ||--o{ pengajuan_izin : "karyawan_id"
    karyawan ||--o{ penilaian : "karyawan_id"
    karyawan ||--o{ penggajian : "karyawan_id"
    users ||--o{ tugas : "assigned_by"
    users ||--o{ tugas : "assigned_to"
```

*   **Tabel `users`**: Menyimpan kredensial login default Laravel dan field tambahan untuk verifikasi 2FA (`two_factor_secret`, `two_factor_confirmed_at`).
*   **Tabel `karyawan`**: Data pribadi detail karyawan. Kolom sensitif seperti `alamat`, `telepon`, dan `nomor_rekening` dienkripsi menggunakan AES-256-CBC.
*   **Tabel `jabatan`**: Posisi kerja beserta komponen gaji standar (gaji pokok, tunjangan jabatan, transport, makan).
*   **Tabel `satuan_kerja`**: Departemen/divisi organisasi beserta kepala satuan kerja.
*   **Tabel `absensi`**: Log kehadiran harian dengan koordinat GPS (`latitude`, `longitude`) dan foto selfie.
*   **Tabel `pengajuan_izin`**: Pengajuan ketidakhadiran (izin, sakit, cuti) dengan upload dokumen surat dokter.
*   **Tabel `tugas`**: Penugasan kerja dari atasan ke karyawan dengan upload bukti pengerjaan atau link file.
*   **Tabel `penilaian`**: Evaluasi kinerja periodik berdasarkan 5 indikator (Disiplin, Kualitas, Tanggung Jawab, Komunikasi, Inisiatif).
*   **Tabel `penggajian`**: Rekap penggajian bulanan otomatis berdasarkan kehadiran dan jabatan.

---

## 3. Panduan Instalasi & Konfigurasi Lingkungan (Laragon)

Ikuti langkah-langkah di bawah ini untuk menjalankan seluruh proyek di server lokal Windows menggunakan **Laragon**:

### 3.1 Konfigurasi Database MySQL
1. Buka panel Laragon, klik tombol **Database** (phpMyAdmin / HeidiSQL).
2. Buat database baru bernama `hris_db` dan `payment_system`:
   ```sql
   CREATE DATABASE hris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE DATABASE payment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import file [database_setup.sql](file:///d:/laragon/www/Pengembangan%20Aplikasi%20Web%20Management%20Projek%20Interfaces/database_setup.sql) ke database `hris_db` untuk inisialisasi tabel dan data awal.

### 3.2 Setup Proyek Utama (HRIS)
Jalankan perintah berikut di PowerShell atau Command Prompt dalam direktori `d:\laragon\www\Pengembangan Aplikasi Web Management Projek Interfaces_Laravel`:
```bash
# 1. Pastikan dependensi composer terinstal
composer install

# 2. Buat file .env dari template
copy .env.example .env

# 3. Generate Application Key
php artisan key:generate

# 4. Jalankan migrasi database dan seeder data sample
php artisan migrate:fresh --seed

# 5. Buat symbolic link untuk akses file upload
php artisan storage:link

# 6. Install dependensi frontend (Node.js harus terinstal)
npm install

# 7. Kompilasi asset frontend (Tailwind/Vite)
npm run build

# 8. Bersihkan cache sistem
php artisan optimize:clear
```

### 3.3 Setup Proyek Pendukung (Payment System)
Jalankan perintah berikut di direktori `d:\laragon\www\payment-system`:
```bash
# 1. Pastikan dependensi composer terinstal
composer install

# 2. Setup env
copy .env.example .env

# 3. Jalankan migrasi dan seeder
php artisan key:generate
php artisan migrate --seed
```
*Catatan: Modul Payment System saat ini dalam pengembangan dan dapat diakses terpisah.*

---

## 4. Instruksi Kerja Pengoperasian Fitur Utama (HRIS)

### 4.1 Manajemen Karyawan & Import Excel
*   **Akses**: Navigasi ke menu **Karyawan** (`/karyawan`).
*   **Tambah Manual**: Klik **Tambah Karyawan**, lengkapi form data pribadi, jabatan, departemen, dan detail bank.
*   **Import Massal**:
    1. Siapkan file Excel (`.xlsx`) dengan header: `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin` (L/P), `agama`, `status_pernikahan` (belum_menikah/menikah/cerai), `alamat`, `telepon`, `email`, `jabatan_id`, `satuan_kerja_id`, `tanggal_masuk`, `status_karyawan` (tetap/kontrak/percobaan).
    2. Klik tombol **Pilih File**, arahkan ke file Excel Anda, dan klik **Import Excel**.
    3. Sistem akan memvalidasi duplikasi NIK dan validitas format tanggal secara otomatis sebelum menyimpannya ke database.

### 4.3 Sistem Absensi (Online & Import)
*   **Absen Mandiri**:
    1. Karyawan mengakses halaman `/absensi/online`.
    2. Pilih nama karyawan (atau terdeteksi otomatis jika sudah login) lalu klik **Absen Sekarang**.
    3. Sistem akan mencatat waktu masuk (Clock In) secara real-time.
*   **Import Absensi**:
    1. Buka menu **Absensi** (`/absensi`).
    2. Unggah berkas Excel rekap absensi dari mesin fingerprint menggunakan form **Import Excel** di sebelah kanan atas.
    3. Format kolom: `nik`, `tanggal`, `jam_masuk`, `jam_keluar`, `status` (hadir/izin/sakit/alpha), `keterangan`.
    4. Sistem akan melakukan *upsert* (menambahkan log baru atau memperbarui jika log tanggal tersebut sudah ada).

### 4.3 Alur Pengajuan Izin
1. Karyawan menavigasi ke menu **Pengajuan Izin** (`/izin`) dan mengklik **Ajukan Izin**.
2. Isi formulir: Jenis Izin (sakit/cuti/izin_khusus), Tanggal Mulai, Tanggal Selesai, Alasan, dan upload dokumen pendukung (misal: surat dokter berbentuk PDF/JPG).
3. Atasan/Admin akan melihat daftar pengajuan dengan status `Pending`.
4. Atasan dapat mengklik tombol **Approve** (Setujui) atau **Reject** (Tolak) dengan menyertakan catatan verifikasi.
5. Jika disetujui, absensi harian pada rentang tanggal tersebut akan otomatis tercatat sebagai `izin` atau `sakit`.

### 4.4 Manajemen Tugas & Penilaian Kinerja
*   **Pemberian Tugas**: Atasan mengakses halaman `/tugas`, membuat tugas baru, mengisi judul, deskripsi, tenggat waktu, dan memilih karyawan pelaksana.
*   **Update Status**: Karyawan membuka halaman `/tugas` milik mereka, mengubah status menjadi `Diproses` atau `Selesai`, dan mengunggah tautan dokumen pengerjaan atau berkas bukti penyelesaian.
*   **Penilaian Kinerja**: Atasan mengakses menu `/penilaian`, memilih karyawan, menentukan periode penilaian, dan memberikan rating (1-100) pada 5 kriteria dasar. Nilai akhir dihitung secara rata-rata otomatis.

### 4.6 Payroll (Perhitungan Gaji Otomatis)
1. Buka menu **Penggajian** (`/penggajian`).
2. Klik **Proses Gaji** untuk memicu perhitungan otomatis.
3. **Rumus Perhitungan Gaji Bersih**:
   $$\text{Gaji Bersih} = \text{Gaji Pokok} + \text{Tunjangan Jabatan} + \text{Tunjangan Transport} + \text{Tunjangan Makan} - \text{Potongan Kehadiran}$$
   *Potongan kehadiran dihitung otomatis dari jumlah hari absen berstatus `Alpha` atau ketidakhadiran tanpa izin.*
4. Administrator dapat menandai penggajian sebagai `Dibayar`.
5. Karyawan dapat melihat dan mengunduh slip gaji digital mereka dalam format cetak ramah printer (A4 layout).

### 4.7 Manajemen Dokumen
*   **Upload Dokumen**:
    1. Akses menu **Dokumen** (`/dokumen`)
    2. Klik **Upload Dokumen**
    3. Pilih jenis dokumen (Kontrak, SK, Sertifikat, Personal, Lainnya)
    4. Upload file (PDF, Word, Excel, Images, dll - Max 20MB)
    5. Sistem akan menyimpan file secara aman di storage
*   **Download Dokumen**:
    - Klik tombol download pada setiap dokumen
    - File akan diunduh dengan nama dokumen yang sesuai
*   **Security**: Validasi file type, size limit, dan secure storage path

---

## 5. Implementasi Keamanan Diperkuat (Hardening)

Untuk memastikan sistem aman digunakan di lingkungan produksi, beberapa lapisan keamanan diimplementasikan:

### 5.1 Enkripsi Kolom Sensitif (Laravel Casting)
Data karyawan dilindungi di tingkat database menggunakan enkripsi AES-256-CBC. Pada model `App\Models\Karyawan`, enkripsi ditangani menggunakan Eloquent Attributes Casting:
```php
protected function alamat(): Attribute
{
    return Attribute::make(
        get: fn ($value) => $value ? $this->safeDecrypt($value) : $value,
        set: fn ($value) => $value ? Crypt::encryptString($value) : $value,
    );
}
```

### 5.2 Multi-Factor Authentication (MFA/2FA)
Aktivasi 2FA menggunakan Fortify (disimulasikan):
1. Pengguna wajib mengaktifkan 2FA melalui menu pengaturan akun.
2. Kode QR dihasilkan berdasarkan kunci rahasia TOTP yang disimpan terenkripsi di database.
3. Pada saat login, pengguna harus memasukkan 6 digit kode OTP dari aplikasi Google Authenticator untuk memverifikasi identitas.

### 5.3 Role-Based Access Control (RBAC)
Pemberian izin diatur menggunakan package `spatie/laravel-permission` yang dikelompokkan ke dalam tiga tingkatan peran:
1. **Admin**: Mengelola seluruh konfigurasi, master data karyawan/jabatan/satuan kerja, rekap absensi, penggajian, serta persetujuan izin.
2. **Atasan**: Membuat penugasan tugas, menginput penilaian kinerja bawahan, dan melakukan persetujuan awal izin.
3. **Karyawan**: Melakukan absensi online, melihat profil pribadi, mengajukan izin, mengupdate progres tugas, dan melihat slip gaji pribadi.

---

## 6. Panduan Praktikum SQL JOIN (Studi Kasus Karyawan & Departemen)

Aplikasi **Payment System** menyediakan modul visualisasi query JOIN SQL yang dapat diakses melalui browser pada alamat `http://payment-system.test/practicum/join` (atau localhost:8000/join).

Berikut penjelasan query JOIN yang diajarkan dalam praktikum ini:

### 6.1 INNER JOIN
Mengembalikan baris yang memiliki nilai yang cocok di kedua tabel.
```sql
SELECT k.id_karyawan, k.nama_karyawan, d.nama_dept, k.gaji
FROM karyawan k
INNER JOIN departemen d ON k.id_dept = d.id_dept;
```
*Guna: Mengetahui karyawan yang aktif bekerja di departemen yang terdaftar.*

### 6.2 LEFT JOIN
Mengembalikan semua baris dari tabel kiri (karyawan), dan baris yang cocok dari tabel kanan (departemen).
```sql
SELECT k.id_karyawan, k.nama_karyawan, d.nama_dept, k.gaji
FROM karyawan k
LEFT JOIN departemen d ON k.id_dept = d.id_dept;
```
*Guna: Menampilkan seluruh karyawan, termasuk yang belum ditempatkan di departemen mana pun (kolom departemen bernilai NULL).*

### 6.3 RIGHT JOIN
Mengembalikan semua baris dari tabel kanan (departemen), dan baris yang cocok dari tabel kiri (karyawan).
```sql
SELECT k.id_karyawan, k.nama_karyawan, d.nama_dept, k.gaji
FROM karyawan k
RIGHT JOIN departemen d ON k.id_dept = d.id_dept;
```
*Guna: Menampilkan seluruh departemen, termasuk departemen yang belum memiliki staf.*

### 6.4 FULL OUTER JOIN (Simulasi UNION)
Mengembalikan semua baris ketika ada kecocokan di salah satu tabel kiri atau kanan. Karena MySQL tidak mendukung `FULL OUTER JOIN` secara bawaan, digunakan gabungan `LEFT JOIN` dan `RIGHT JOIN` melalui operator `UNION`:
```sql
(SELECT k.id_karyawan, k.nama_karyawan, d.nama_dept, k.gaji
FROM karyawan k
LEFT JOIN departemen d ON k.id_dept = d.id_dept)
UNION
(SELECT k.id_karyawan, k.nama_karyawan, d.nama_dept, k.gaji
FROM karyawan k
RIGHT JOIN departemen d ON k.id_dept = d.id_dept);
```
*Guna: Menampilkan seluruh relasi data karyawan dan departemen tanpa ada baris data yang terbuang.*

---

## 8. Pemeliharaan dan Troubleshooting Ringan

*   **Error: Class "Maatwebsite\Excel\Facades\Excel" Not Found**
    Pastikan dependensi Laravel Excel sudah terpasang. Jalankan `composer require maatwebsite/excel`.
*   **Error: Storage link broken (404 foto profil tidak muncul)**
    Hapus symbolic link lama dan buat ulang:
    ```bash
    rm public/storage
    php artisan storage:link
    ```
*   **Error: Database Connection Refused**
    Periksa kesamaan port MySQL antara `.env` (misal: `DB_PORT=3306`) dengan port aktif di aplikasi Laragon Anda.
*   **Error: Chart.js not loading**
    Pastikan CDN Chart.js ter-load dengan benar di `resources/views/layouts/app.blade.php`
*   **Error: Notifications not updating**
    Periksa console browser untuk error JavaScript dan pastikan route `/notifications` dapat diakses

---

## 9. Deployment ke Production

Untuk deployment ke production server:

### 9.1 Environment Configuration
```bash
# Set environment to production
APP_ENV=production
APP_DEBUG=false

# Generate production key
php artisan key:generate

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9.2 Security Checklist
- [ ] Change default admin password
- [ ] Enable HTTPS with SSL certificate
- [ ] Configure firewall rules
- [ ] Set up database backups
- [ ] Enable logging and monitoring
- [ ] Configure CORS if using API from different domain
- [ ] Review and update file upload permissions

### 9.3 Performance Optimization
```bash
# Install production dependencies
npm install --production

# Build assets for production
npm run build

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 10. Support & Maintenance

Untuk dukungan teknis dan pertanyaan:
- Referensi: `API_DOCUMENTATION.md` untuk detail API
- Log files: `storage/logs/laravel.log`
- Database backup: Gunakan mysqldump untuk backup rutin

---
*Dokumen ini merupakan bagian dari standar Instruksi Kerja Operasional Aplikasi Web Manajemen Projek - Versi 2.0 (Enhanced)*
