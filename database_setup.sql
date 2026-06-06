-- ============================================================
-- ENTERPRISE HRIS DATABASE SETUP SCRIPT (MySQL 8.0 Hardened)
-- Aplikasi Web Manajemen Karyawan Berbasis Laravel 11
-- ============================================================

CREATE DATABASE IF NOT EXISTS hris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hris_db;

-- SET foreign key checks to 0 for clean re-creation
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables to prevent conflicts
DROP TABLE IF EXISTS `dokumen`;
DROP TABLE IF EXISTS `penggajian`;
DROP TABLE IF EXISTS `komponen_gaji`;
DROP TABLE IF EXISTS `penilaian_kinerja`;
DROP TABLE IF EXISTS `tugas_karyawan`;
DROP TABLE IF EXISTS `pengajuan_izin`;
DROP TABLE IF EXISTS `absensi`;
DROP TABLE IF EXISTS `karyawan`;
DROP TABLE IF EXISTS `satuan_kerja`;
DROP TABLE IF EXISTS `jabatan`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `model_has_permissions`;
DROP TABLE IF EXISTS `model_has_roles`;
DROP TABLE IF EXISTS `role_has_permissions`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 1. TABEL USERS (Laravel Default + Custom 2FA)
-- ============================================================
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `users_email_idx` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sessions_user_id_index` (`user_id`),
  INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABEL JABATAN (Master Jabatan & Komponen Gaji Pokok)
-- ============================================================
CREATE TABLE `jabatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(255) NOT NULL UNIQUE,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_jabatan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_transport` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_makan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `jabatan_nama_idx` (`nama_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABEL SATUAN KERJA (Departemen / Divisi)
-- ============================================================
CREATE TABLE `satuan_kerja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_satuan_kerja` varchar(255) NOT NULL UNIQUE,
  `kepala_satuan_kerja_id` bigint unsigned DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `satuan_kerja_nama_idx` (`nama_satuan_kerja`),
  CONSTRAINT `satuan_kerja_kepala_foreign` FOREIGN KEY (`kepala_satuan_kerja_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABEL KARYAWAN (Detail Data Karyawan dengan Kolom Enkripsi)
-- ============================================================
CREATE TABLE `karyawan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL UNIQUE,
  `nik` varchar(50) NOT NULL UNIQUE,
  `nama_lengkap` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `agama` varchar(50) NOT NULL,
  `status_pernikahan` enum('belum_menikah','menikah','cerai') NOT NULL,
  `alamat` text NOT NULL, -- Di-encrypt AES-256 oleh Laravel
  `telepon` varchar(255) NOT NULL, -- Di-encrypt AES-256 oleh Laravel
  `email` varchar(255) NOT NULL UNIQUE,
  `jabatan_id` bigint unsigned NOT NULL,
  `satuan_kerja_id` bigint unsigned NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `status_karyawan` enum('tetap','kontrak','percobaan') NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL, -- Di-encrypt AES-256 oleh Laravel
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `karyawan_nik_idx` (`nik`),
  INDEX `karyawan_nama_idx` (`nama_lengkap`),
  CONSTRAINT `karyawan_user_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_jabatan_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`id`),
  CONSTRAINT `karyawan_satuan_kerja_foreign` FOREIGN KEY (`satuan_kerja_id`) REFERENCES `satuan_kerja` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABEL ABSENSI (Data Log Absensi Harian + GPS)
-- ============================================================
CREATE TABLE `absensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('hadir','izin','sakit','alpha') NOT NULL DEFAULT 'hadir',
  `keterangan` varchar(500) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `foto_absen` varchar(255) DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `absensi_karyawan_tanggal_unique` (`karyawan_id`,`tanggal`),
  INDEX `absensi_tanggal_idx` (`tanggal`),
  CONSTRAINT `absensi_karyawan_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TABEL PENGAJUAN IZIN (Cuti / Sakit dengan Upload Bukti)
-- ============================================================
CREATE TABLE `pengajuan_izin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint unsigned NOT NULL,
  `jenis_izin` enum('sakit','cuti','izin_khusus') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `bukti_dokumen` varchar(255) DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `catatan_verifikasi` text,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `izin_status_idx` (`status`),
  CONSTRAINT `izin_karyawan_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `izin_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TABEL TUGAS KARYAWAN (Penugasan & Upload Bukti Penyelesaian)
-- ============================================================
CREATE TABLE `tugas_karyawan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text,
  `assigned_by` bigint unsigned DEFAULT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `tanggal_tenggat` date DEFAULT NULL,
  `status` enum('baru','diproses','selesai','terlambat') NOT NULL DEFAULT 'baru',
  `bukti_penyelesaian` varchar(255) DEFAULT NULL,
  `catatan` text,
  `selesai_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `tugas_status_idx` (`status`),
  CONSTRAINT `tugas_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tugas_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. TABEL PENILAIAN KINERJA (KPI Periodik)
-- ============================================================
CREATE TABLE `penilaian_kinerja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint unsigned NOT NULL,
  `periode` date NOT NULL,
  `nilai_disiplin` decimal(5,2) NOT NULL DEFAULT 0,
  `nilai_kualitas` decimal(5,2) NOT NULL DEFAULT 0,
  `nilai_tanggung_jawab` decimal(5,2) NOT NULL DEFAULT 0,
  `nilai_komunikasi` decimal(5,2) NOT NULL DEFAULT 0,
  `nilai_inisiatif` decimal(5,2) NOT NULL DEFAULT 0,
  `total_nilai` decimal(5,2) NOT NULL DEFAULT 0,
  `catatan` text,
  `dinilai_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `penilaian_karyawan_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_dinilai_by_foreign` FOREIGN KEY (`dinilai_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. TABEL KOMPONEN GAJI (Tunjangan / Potongan Spesifik)
-- ============================================================
CREATE TABLE `komponen_gaji` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_komponen` varchar(255) NOT NULL,
  `jenis` enum('penambahan','potongan') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `komponen_nama_idx` (`nama_komponen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. TABEL PENGGAJIAN (Slip Gaji & Perhitungan Otomatis)
-- ============================================================
CREATE TABLE `penggajian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint unsigned NOT NULL,
  `periode` date NOT NULL,
  `gaji_pokok` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_jabatan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_transport` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_makan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tunjangan_lainnya` decimal(15,2) NOT NULL DEFAULT 0.00,
  `potongan_absen` decimal(15,2) NOT NULL DEFAULT 0.00,
  `potongan_lainnya` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_gaji` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','dibayar') NOT NULL DEFAULT 'pending',
  `slip_gaji` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penggajian_karyawan_periode_unique` (`karyawan_id`,`periode`),
  CONSTRAINT `penggajian_karyawan_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10B. TABEL DOKUMEN (Manajemen Berkas Multiformat)
-- ============================================================
CREATE TABLE `dokumen` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `karyawan_id` bigint unsigned DEFAULT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `tipe_dokumen` enum('kontrak','sk','sertifikat','personal','lainnya') NOT NULL DEFAULT 'lainnya',
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `deskripsi` text,
  `uploaded_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `dokumen_karyawan_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dokumen_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. SPATIE PERMISSION TABLES (RBAC)
-- ============================================================
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INSERT SEED DATA (Admin, Atasan, Karyawan)
-- ============================================================

-- Password: password (hash bcrypt)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Arya Fatthurahman', 'admin@hr.test', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMeO2lVHYfOXg1HJHbMBFkKN7mHGq.HzrGe', NOW(), NOW()),
(2, 'Budi Santoso', 'budi@hr.test', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMeO2lVHYfOXg1HJHbMBFkKN7mHGq.HzrGe', NOW(), NOW()),
(3, 'Siti Aminah', 'siti@hr.test', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMeO2lVHYfOXg1HJHbMBFkKN7mHGq.HzrGe', NOW(), NOW()),
(4, 'Rahman Hadi', 'rahman@hr.test', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMeO2lVHYfOXg1HJHbMBFkKN7mHGq.HzrGe', NOW(), NOW()),
(5, 'Dewi Lestari', 'dewi@hr.test', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMeO2lVHYfOXg1HJHbMBFkKN7mHGq.HzrGe', NOW(), NOW());

INSERT INTO `jabatan` (`id`, `nama_jabatan`, `gaji_pokok`, `tunjangan_jabatan`, `tunjangan_transport`, `tunjangan_makan`) VALUES
(1, 'Direktur', 15000000.00, 5000000.00, 1500000.00, 1000000.00),
(2, 'General Manager', 12000000.00, 4000000.00, 1200000.00, 900000.00),
(3, 'Manager', 10000000.00, 3000000.00, 1000000.00, 800000.00),
(4, 'Supervisor', 7000000.00, 2000000.00, 750000.00, 600000.00),
(5, 'Staff Senior', 5500000.00, 1500000.00, 500000.00, 500000.00),
(6, 'Staff', 4500000.00, 1000000.00, 400000.00, 400000.00),
(7, 'Admin', 4000000.00, 800000.00, 350000.00, 350000.00);

INSERT INTO `satuan_kerja` (`id`, `nama_satuan_kerja`, `lokasi`) VALUES
(1, 'IT Development', 'Jakarta'),
(2, 'Human Resources', 'Jakarta'),
(3, 'Finance & Accounting', 'Jakarta'),
(4, 'Marketing', 'Bandung'),
(5, 'Sales', 'Surabaya');

-- Sample Karyawan (Note: Alamat, Telepon, Nomor Rekening will be decrypted safely by Laravel's Attribute custom decrypt/encrypt helper)
INSERT INTO `karyawan` (`id`, `user_id`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_pernikahan`, `alamat`, `telepon`, `email`, `jabatan_id`, `satuan_kerja_id`, `tanggal_masuk`, `status_karyawan`) VALUES
(1, 1, 'ADM001', 'Muhammad Arya Fatthurahman', 'Jakarta', '2004-05-26', 'L', 'Islam', 'belum_menikah', 'Jakarta', '081234567890', 'admin@hr.test', 1, 1, '2024-01-01', 'tetap'),
(2, 2, 'BUD001', 'Budi Santoso', 'Jakarta', '1990-03-15', 'L', 'Islam', 'menikah', 'Jl. Contoh No. 1, Jakarta', '08111222333', 'budi@hr.test', 2, 2, '2024-03-01', 'tetap'),
(3, 3, 'SIT001', 'Siti Aminah', 'Jakarta', '1992-07-20', 'P', 'Islam', 'menikah', 'Jl. Contoh No. 2, Jakarta', '08222333444', 'siti@hr.test', 3, 1, '2024-02-15', 'tetap'),
(4, 4, 'RAH001', 'Rahman Hadi', 'Jakarta', '1995-11-10', 'L', 'Islam', 'menikah', 'Jl. Contoh No. 3, Jakarta', '08333444555', 'rahman@hr.test', 4, 3, '2024-04-01', 'kontrak'),
(5, 5, 'DEW001', 'Dewi Lestari', 'Jakarta', '1998-01-05', 'P', 'Islam', 'belum_menikah', 'Jl. Contoh No. 4, Jakarta', '08444555666', 'dewi@hr.test', 5, 2, '2024-05-01', 'kontrak');

-- Seed Komponen Gaji
INSERT INTO `komponen_gaji` (`id`, `nama_komponen`, `jenis`, `jumlah`) VALUES
(1, 'BPJS Kesehatan', 'potongan', 150000.00),
(2, 'BPJS Ketenagakerjaan', 'potongan', 200000.00),
(3, 'Tunjangan Khusus Hari Raya', 'penambahan', 1000000.00),
(4, 'Potongan Absen Terlambat', 'potongan', 50000.00);

-- Assign head of department
UPDATE `satuan_kerja` SET `kepala_satuan_kerja_id` = 1 WHERE `id` = 1;
UPDATE `satuan_kerja` SET `kepala_satuan_kerja_id` = 2 WHERE `id` = 2;

-- RBAC Permissions Setup
INSERT INTO `permissions` (`name`, `guard_name`) VALUES
('manage users', 'web'),
('view karyawan', 'web'), ('create karyawan', 'web'), ('edit karyawan', 'web'), ('delete karyawan', 'web'),
('view jabatan', 'web'), ('create jabatan', 'web'), ('edit jabatan', 'web'), ('delete jabatan', 'web'),
('view satuan-kerja', 'web'), ('create satuan-kerja', 'web'), ('edit satuan-kerja', 'web'), ('delete satuan-kerja', 'web'),
('view absensi', 'web'), ('create absensi', 'web'), ('edit absensi', 'web'), ('delete absensi', 'web'),
('view izin', 'web'), ('approve izin', 'web'), ('create izin', 'web'),
('view tugas', 'web'), ('manage tasks', 'web'), ('create tugas', 'web'),
('view penilaian', 'web'), ('create penilaian', 'web'), ('edit penilaian', 'web'), ('delete penilaian', 'web'),
('view penggajian', 'web'), ('manage penggajian', 'web'), ('create penggajian', 'web'),
('view dokumen', 'web'), ('create dokumen', 'web'), ('edit dokumen', 'web'), ('delete dokumen', 'web'), ('download dokumen', 'web');

-- Create Roles
INSERT INTO `roles` (`name`, `guard_name`) VALUES
('Admin', 'web'),
('Atasan', 'web'),
('Karyawan', 'web');

-- Admin Role Perms
INSERT INTO `role_has_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM permissions;

-- Atasan Role Perms
INSERT INTO `role_has_permissions` (`role_id`, `permission_id`)
SELECT 2, id FROM permissions WHERE name IN (
  'view karyawan', 'create karyawan', 'edit karyawan',
  'view jabatan', 'view satuan-kerja',
  'view absensi', 'create absensi', 'edit absensi',
  'view izin', 'approve izin',
  'view tugas', 'manage tasks', 'create tugas',
  'view penilaian', 'create penilaian', 'edit penilaian',
  'view penggajian', 'manage penggajian', 'create penggajian',
  'view dokumen', 'create dokumen', 'edit dokumen', 'delete dokumen', 'download dokumen'
);

-- Karyawan Role Perms
INSERT INTO `role_has_permissions` (`role_id`, `permission_id`)
SELECT 3, id FROM permissions WHERE name IN (
  'view karyawan', 'view absensi', 'create absensi',
  'view izin', 'create izin',
  'view tugas', 'view penilaian', 'view penggajian',
  'view dokumen', 'download dokumen'
);

-- Assign Roles
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5);
