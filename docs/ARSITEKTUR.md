# ARSITEKTUR SISTEM HRIS IT/IJK

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11 (PHP 8.1+) |
| Database | MySQL 8.0 |
| Frontend | Blade Templates + Tailwind CSS |
| Interaktif | React 18 + TypeScript |
| Chart | Chart.js 4 |
| Animasi | CSS Animations |
| Icons | Font Awesome 6 |
| Font | Inter (Google Fonts) |

## Arsitektur MVC

```
Request → Router → Controller → Model → Database
                                    ↓
                              View (Blade + React)
                                    ↓
                              Response (HTML + JSON)
```

### Alur Autentikasi

```
Login Form → AuthController::login()
    ↓
Validasi Input (nama/email + password)
    ↓
Cari User (by email ATAU name)
    ↓
Hash::check(password, user.password)
    ↓
Auth::login(user) → Session regenerate
    ↓
Redirect ke Dashboard
```

### Alur RBAC (Role-Based Access Control)

```
User Login → Spatie Permission Check
    ↓
Role: admin / atasan / karyawan
    ↓
Permission Check per Route
    ↓
Allow → Controller → View
Deny  → 403 Forbidden
```

## Struktur Database

### Tabel Utama

| Tabel | Keterangan |
|-------|------------|
| users | Akun pengguna sistem |
| karyawan | Data karyawan |
| jabatan | Daftar jabatan |
| satuan_kerja | Unit/divisi kerja |
| absensi | Log kehadiran |
| pengajuan_izin | Pengajuan izin/sakit/cuti |
| tugas | Penugasan tugas |
| penilaian | Penilaian kinerja |
| penggajian | Data penggajian |
| dokumen | Dokumen pendukung |
| audit_logs | Log aktivitas |

### Relasi Database

```
users ──(1:1)── karyawan
karyawan ──(1:N)── absensi
karyawan ──(1:N)── pengajuan_izin
karyawan ──(1:N)── tugas (as assigned_to)
karyawan ──(1:N)── penilaian
karyawan ──(1:N)── penggajian
jabatan ──(1:N)── karyawan
satuan_kerja ──(1:N)── karyawan
```

## Struktur Folder

```
hris/
├── app/
│   ├── Http/Controllers/    # Controller
│   ├── Models/              # Model Eloquent
│   ├── Services/            # Logic bisnis
│   ├── Exports/             # Export Excel
│   └── Imports/             # Import Excel
├── resources/
│   ├── views/blade/         # Template Blade
│   ├── ts/components/       # Komponen React/TSX
│   ├── ts/types/            # TypeScript types
│   ├── ts/utils/            # Utility functions
│   └── css/                 # Custom CSS
├── routes/                  # Route definitions
├── database/                # Migrations & Seeders
└── docs/                    # Dokumentasi
```

## Component Architecture

### Blade Components
- `layouts/app.blade.php` - Main layout dengan sidebar
- `auth/login.blade.php` - Halaman login
- `dashboard.blade.php` - Dashboard utama

### React/TSX Components
- `LiveClock.tsx` - Jam real-time
- `NotificationBell.tsx` - Lonceng notifikasi
- `DataTable.tsx` - Tabel interaktif
- `SearchableSelect.tsx` - Select dengan pencarian
- `FileUploader.tsx` - Upload drag & drop
- `ConfirmDialog.tsx` - Modal konfirmasi
- `QuickStats.tsx` - Statistik dashboard
- `ChartWrapper.tsx` - Wrapper Chart.js
