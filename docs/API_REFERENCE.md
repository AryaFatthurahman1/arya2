# API REFERENCE HRIS IT/IJK

## Base URL

```
http://localhost:8000/api
```

## Autentikasi

Semua endpoint memerlukan autentikasi via session cookie.

## Endpoint

### Karyawan

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/karyawan | Daftar semua karyawan |
| POST | /api/karyawan | Tambah karyawan baru |
| GET | /api/karyawan/{id} | Detail karyawan |
| PUT | /api/karyawan/{id} | Update karyawan |
| DELETE | /api/karyawan/{id} | Hapus karyawan |

### Absensi

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/absensi | Daftar semua absensi |
| POST | /api/absensi | Tambah data absensi |
| POST | /api/absensi/clock-in | Absen masuk |
| POST | /api/absensi/clock-out | Absen keluar |

### Pengajuan Izin

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/izin | Daftar pengajuan izin |
| POST | /api/izin | Ajukan izin baru |
| PATCH | /api/izin/{id}/approve | Setujui izin |
| PATCH | /api/izin/{id}/reject | Tolak izin |

### Tugas

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/tugas | Daftar semua tugas |
| POST | /api/tugas | Buat tugas baru |
| PATCH | /api/tugas/{id}/status | Update status tugas |

### Penilaian

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/penilaian | Daftar penilaian |
| POST | /api/penilaian | Buat penilaian baru |

### Penggajian

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/penggajian | Daftar penggajian |
| POST | /api/penggajian | Hitung gaji |
| GET | /api/penggajian/{id}/slip | Lihat slip gaji |

### Export

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/karyawan/export | Export karyawan (XLSX) |
| GET | /api/absensi/export | Export absensi (XLSX) |
| GET | /api/penggajian/export | Export penggajian (XLSX) |

### Notifications

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | /api/notifications | Daftar notifikasi |
| POST | /api/notifications/read-all | Tandai semua dibaca |

## Response Format

### Sukses

```json
{
    "success": true,
    "data": { ... },
    "message": "Berhasil"
}
```

### Error

```json
{
    "success": false,
    "message": "Gagal",
    "errors": {
        "field": ["Pesan error"]
    }
}
```
