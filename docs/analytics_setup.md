# PANDUAN INTEGRASI ANALYTICS
## BI Dashboard (Metabase) & Time-Series DB (ClickHouse)

Panduan ini mendokumentasikan instalasi, sinkronisasi data, dan pembuatan metrik dashboard untuk visualisasi analitik HRIS menggunakan kombinasi **ClickHouse** (penyimpanan data absensi berkecepatan tinggi) dan **Metabase** (pembuatan bagan/visualisasi).

---

## 1. Arsitektur Aliran Data Analytics

```
┌─────────────────────┐      (Koneksi Sinkron)       ┌───────────────────┐
│     Laravel HRIS    │ ───────────────────────────> │ MySQL (hris_db)   │
│  (Aplikasi Utama)   │                              └───────────────────┘
└──────────┬──────────┘                                        ▲
           │                                                   │
     (Kirim Log)                                           (Link DB)
           │                                                   │
           ▼                                                   ▼
┌─────────────────────┐      (Visualisasi Data)      ┌───────────────────┐
│  ClickHouse DB      │ <─────────────────────────── │     Metabase      │
│ (Time-series Logs)  │                              │    (Dashboard)    │
└─────────────────────┘                              └───────────────────┘
```

---

## 2. Docker Compose Setup (Analytics Stack)

Tambahkan konfigurasi berikut ke berkas deployment stack analitik Anda:

```yaml
version: '3.8'

services:
  clickhouse:
    image: clickhouse/clickhouse-server:latest
    container_name: hris-clickhouse
    ports:
      - "8123:8123" # HTTP API
      - "9000:9000" # Native client
    volumes:
      - ch_data:/var/lib/clickhouse
      - ch_logs:/var/log/clickhouse
    ulimits:
      nofile:
        soft: 262144
        hard: 262144
    healthcheck:
      test: ["CMD", "wget", "--spider", "-q", "localhost:8123/ping"]
      interval: 10s
      timeout: 5s
      retries: 3

  metabase:
    image: metabase/metabase:latest
    container_name: hris-metabase
    ports:
      - "3000:3000"
    environment:
      - MB_DB_TYPE=postgres
      - MB_DB_DBNAME=metabase
      - MB_DB_PORT=5432
      - MB_DB_USER=metabase_user
      - MB_DB_PASS=metabase_secure_pass
      - MB_DB_HOST=metabase-db
    depends_on:
      - metabase-db

  metabase-db:
    image: postgres:15-alpine
    container_name: hris-metabase-db
    environment:
      - POSTGRES_DB=metabase
      - POSTGRES_USER=metabase_user
      - POSTGRES_PASSWORD=metabase_secure_pass
    volumes:
      - pg_metabase_data:/var/lib/postgresql/data

volumes:
  ch_data:
  ch_logs:
  pg_metabase_data:
```

---

## 3. Konfigurasi ClickHouse (Skema Tabel Absensi & Evaluasi)

ClickHouse menggunakan engine **MergeTree** untuk pemrosesan paralel query analitik dalam skala besar.

### 3.1 Skema Tabel Log Absensi (`hris_analytics.absensi_logs`)
Jalankan query berikut pada ClickHouse client:

```sql
CREATE DATABASE IF NOT EXISTS hris_analytics;

CREATE TABLE hris_analytics.absensi_logs (
    id UInt64,
    karyawan_id UInt64,
    nik String,
    nama_lengkap String,
    jabatan String,
    satuan_kerja String,
    tanggal Date,
    jam_masuk Nullable(DateTime),
    jam_keluar Nullable(DateTime),
    status Enum8('hadir' = 1, 'izin' = 2, 'sakit' = 3, 'alpha' = 4),
    terlambat UInt8, -- 0 jika tepat waktu, 1 jika terlambat
    latitude Decimal(10, 8),
    longitude Decimal(11, 8),
    event_time DateTime DEFAULT now()
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(tanggal)
ORDER BY (satuan_kerja, tanggal, karyawan_id);
```

### 3.2 Sinkronisasi dari Laravel ke ClickHouse
Di dalam aplikasi Laravel, buat event listener ketika absensi dilakukan untuk mencatatkan log ke ClickHouse secara asinkron lewat queue:

```php
namespace App\Listeners;

use App\Events\AbsensiRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;

class SendAbsensiToClickHouse implements ShouldQueue
{
    public function handle(AbsensiRecorded $event)
    {
        $absensi = $event->absensi;
        $karyawan = $absensi->karyawan;

        $data = [
            'id' => $absensi->id,
            'karyawan_id' => $absensi->karyawan_id,
            'nik' => $karyawan->nik,
            'nama_lengkap' => $karyawan->nama_lengkap,
            'jabatan' => $karyawan->jabatan->nama_jabatan,
            'satuan_kerja' => $karyawan->satuanKerja->nama_satuan_kerja,
            'tanggal' => $absensi->tanggal->format('Y-m-d'),
            'jam_masuk' => $absensi->jam_masuk ? $absensi->tanggal->format('Y-m-d') . ' ' . $absensi->jam_masuk : null,
            'jam_keluar' => $absensi->jam_keluar ? $absensi->tanggal->format('Y-m-d') . ' ' . $absensi->jam_keluar : null,
            'status' => $absensi->status,
            'terlambat' => $absensi->jam_masuk > '08:00:00' ? 1 : 0,
            'latitude' => $absensi->latitude,
            'longitude' => $absensi->longitude,
        ];

        // HTTP request ke ClickHouse HTTP Interface
        Http::withHeaders([
            'X-ClickHouse-User' => 'default',
            'X-ClickHouse-Key' => env('CLICKHOUSE_PASSWORD', '')
        ])->post('http://hris-clickhouse:8123/?query=INSERT+INTO+hris_analytics.absensi_logs+FORMAT+JSONEachRow', $data);
    }
}
```

---

## 4. Metabase Setup & Dashboard Metrics

### 4.1 Menghubungkan Metabase ke ClickHouse
1. Masuk ke halaman admin Metabase (`http://localhost:3000`).
2. Navigasi ke **Admin Settings** > **Databases** > **Add Database**.
3. Pilih jenis database **ClickHouse** (Jika opsi tidak muncul, unduh plugin clickhouse-metabase-driver dan letakkan di folder `/plugins` kontainer Metabase).
4. Masukkan parameter koneksi:
   * **Host**: `hris-clickhouse`
   * **Port**: `8123`
   * **Database**: `hris_analytics`
   * **Username**: `default`
   * **Password**: `[Password_ClickHouse_Anda]`

### 4.2 Query Metrik Utama untuk Dashboard BI

#### Metrik 1: Tingkat Kehadiran Karyawan (Persentase)
```sql
SELECT
    (countIf(status = 'hadir') / count()) * 100 AS tingkat_kehadiran
FROM hris_analytics.absensi_logs
WHERE tanggal >= today() - 30;
```

#### Metrik 2: Jumlah Karyawan Terlambat Per Departemen
```sql
SELECT
    satuan_kerja,
    sum(terlambat) AS jumlah_terlambat
FROM hris_analytics.absensi_logs
WHERE tanggal = today()
GROUP BY satuan_kerja
ORDER BY jumlah_terlambat DESC;
```

#### Metrik 3: Trend Keterlambatan Harian
```sql
SELECT
    tanggal,
    sum(terlambat) AS total_terlambat
FROM hris_analytics.absensi_logs
WHERE tanggal >= today() - 14
GROUP BY tanggal
ORDER BY tanggal ASC;
```

#### Metrik 4: Sebaran Lokasi Absensi GPS (Geographic Map)
Gunakan visualisasi peta (Pin Map) di Metabase dengan query koordinat:
```sql
SELECT
    latitude,
    longitude,
    nama_lengkap,
    status
FROM hris_analytics.absensi_logs
WHERE tanggal = today() AND latitude IS NOT NULL;
```

---
*Dokumen ini merupakan panduan operasional setup analytics pada infrastruktur HRIS Enterprise.*
