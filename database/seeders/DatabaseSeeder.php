<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use App\Models\Absensi;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Mulai seeding HRIS ===');

        $this->seedPermsAndRoles();

        $jabatanIds = $this->seedJabatan();
        $satuanKerjaIds = $this->seedSatuanKerja();
        $karyawanIds = $this->seedUsersAndKaryawan($jabatanIds, $satuanKerjaIds);
        $this->seedAbsensi($karyawanIds);
        $this->seedKomponenGaji();

        $this->command->info('=== SELESAI ===');
        $this->command->info('Login: admin@hr.test / password');
    }

    private function seedPermsAndRoles(): void
    {
        $perms = [
            'manage users', 'view karyawan', 'create karyawan', 'edit karyawan', 'delete karyawan',
            'view jabatan', 'create jabatan', 'edit jabatan', 'delete jabatan',
            'view satuan-kerja', 'create satuan-kerja', 'edit satuan-kerja', 'delete satuan-kerja',
            'view absensi', 'create absensi', 'edit absensi', 'delete absensi',
            'view izin', 'approve izin', 'create izin',
            'view tugas', 'manage tasks', 'create tugas',
            'view penilaian', 'create penilaian', 'edit penilaian', 'delete penilaian',
            'view penggajian', 'manage penggajian', 'create penggajian',
            'view dokumen', 'create dokumen', 'edit dokumen', 'delete dokumen', 'download dokumen',
        ];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'Atasan', 'guard_name' => 'web'])
            ->syncPermissions([
                'view karyawan', 'create karyawan', 'edit karyawan',
                'view jabatan', 'view satuan-kerja',
                'view absensi', 'create absensi', 'edit absensi',
                'view izin', 'approve izin',
                'view tugas', 'manage tasks', 'create tugas',
                'view penilaian', 'create penilaian', 'edit penilaian',
                'view penggajian', 'manage penggajian', 'create penggajian',
                'view dokumen', 'create dokumen', 'edit dokumen', 'delete dokumen', 'download dokumen',
            ]);

        Role::firstOrCreate(['name' => 'Karyawan', 'guard_name' => 'web'])
            ->syncPermissions([
                'view karyawan', 'view absensi', 'create absensi',
                'view izin', 'create izin',
                'view tugas', 'view penilaian', 'view penggajian',
                'view dokumen', 'download dokumen',
            ]);

        $this->command->info('✓ Roles & Permissions');
    }

    private function seedJabatan(): array
    {
        $rows = [
            ['Direktur', 15000000, 5000000, 1500000, 1000000],
            ['General Manager', 12000000, 4000000, 1200000, 900000],
            ['Manager', 10000000, 3000000, 1000000, 800000],
            ['Supervisor', 7000000, 2000000, 750000, 600000],
            ['Staff Senior', 5500000, 1500000, 500000, 500000],
            ['Staff', 4500000, 1000000, 400000, 400000],
            ['Admin', 4000000, 800000, 350000, 350000],
        ];
        foreach ($rows as [$nama, $gp, $tj, $tt, $tm]) {
            Jabatan::firstOrCreate(['nama_jabatan' => $nama], [
                'gaji_pokok' => $gp,
                'tunjangan_jabatan' => $tj,
                'tunjangan_transport' => $tt,
                'tunjangan_makan' => $tm,
            ]);
        }
        $this->command->info('✓ Jabatan');
        return Jabatan::pluck('id')->toArray();
    }

    private function seedSatuanKerja(): array
    {
        $rows = [
            ['IT Development', 'Jakarta'],
            ['Human Resources', 'Jakarta'],
            ['Finance & Accounting', 'Jakarta'],
            ['Marketing', 'Bandung'],
            ['Sales', 'Surabaya'],
        ];
        foreach ($rows as [$nama, $lokasi]) {
            SatuanKerja::firstOrCreate(['nama_satuan_kerja' => $nama], ['lokasi' => $lokasi]);
        }
        $this->command->info('✓ Satuan Kerja');
        return SatuanKerja::all()->pluck('id')->toArray();
    }

    private function seedUsersAndKaryawan(array $jabatanIds, array $satuanKerjaIds): array
    {
        $adminUser = User::firstOrCreate(['email' => 'admin@hr.test'], [
            'name' => 'Muhammad Arya',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole('Admin');

        $jabatanModel = Jabatan::first();
        $satuanKerja = SatuanKerja::first();

        Karyawan::updateOrCreate(['email' => 'admin@hr.test'], [
            'user_id' => $adminUser->id,
            'nik' => 'ADM001',
            'nama_lengkap' => 'Muhammad Arya Fatthurahman',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2004-05-26',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_pernikahan' => 'belum_menikah',
            'alamat' => 'Jakarta',
            'telepon' => '081234567890',
            'jabatan_id' => $jabatanModel->id,
            'satuan_kerja_id' => $satuanKerja->id,
            'tanggal_masuk' => '2024-01-01',
            'status_karyawan' => 'tetap',
        ]);

        $names = [
            ['Budi Santoso', 'L', 'tetap', 'Atasan'],
            ['Siti Aminah', 'P', 'tetap', 'Atasan'],
            ['Rahman Hadi', 'L', 'kontrak', 'Atasan'],
            ['Dewi Lestari', 'P', 'kontrak', 'Karyawan'],
            ['Fajar Pratama', 'L', 'percobaan', 'Karyawan'],
            ['Gita Wulandari', 'P', 'percobaan', 'Karyawan'],
            ['Rina Kusuma', 'P', 'tetap', 'Karyawan'],
            ['Andi Wijaya', 'L', 'kontrak', 'Karyawan'],
            ['Maya Putri', 'P', 'tetap', 'Karyawan'],
            ['Toni Hartono', 'L', 'percobaan', 'Karyawan'],
        ];

        $allKaryawanIds = Karyawan::pluck('id')->toArray();

        foreach ($names as $idx => [$nama, $jk, $status, $role]) {
            $first = explode(' ', $nama)[0];
            $email = strtolower(str_replace(' ', '.', $first)) . ($idx + 1) . '@hr.test';

            $u = User::firstOrCreate(['email' => $email], [
                'name' => $nama,
                'password' => Hash::make('password'),
            ]);

            if (!$u->hasRole($role)) {
                $u->syncRoles([$role]);
            }

            $skIdx = min($idx % count($satuanKerjaIds), count($satuanKerjaIds) - 1);
            $jIdx = min($idx % count($jabatanIds), count($jabatanIds) - 1);

            $existing = Karyawan::where('email', $email)->first();
            if ($existing) {
                $existing->update([
                    'nama_lengkap' => $nama,
                    'jabatan_id' => $jabatanIds[$jIdx],
                    'satuan_kerja_id' => $satuanKerjaIds[$skIdx],
                    'status_karyawan' => $status,
                ]);
            } else {
                Karyawan::create([
                    'user_id' => $u->id,
                    'nik' => strtoupper(substr($nama, 0, 3)) . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                    'nama_lengkap' => $nama,
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '199' . rand(0, 9) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'jenis_kelamin' => $jk,
                    'agama' => 'Islam',
                    'status_pernikahan' => 'menikah',
                    'alamat' => 'Jl. Contoh No. ' . ($idx + 1) . ', Jakarta',
                    'telepon' => '08' . str_pad(rand(11, 99), 2, '0', STR_PAD_LEFT) . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'jabatan_id' => $jabatanIds[$jIdx],
                    'satuan_kerja_id' => $satuanKerjaIds[$skIdx],
                    'tanggal_masuk' => '2024-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'status_karyawan' => $status,
                    'email' => $email,
                ]);
            }
        }

        $this->command->info('✓ 11 Karyawan');
        return Karyawan::pluck('id')->toArray();
    }

    private function seedAbsensi(array $karyawanIds): void
    {
        $this->command->info('Membuat absensi 30 hari terakhir...');
        $now = now();
        $count = 0;

        foreach ($karyawanIds as $kid) {
            for ($d = 30; $d >= 1; $d--) {
                $t = Carbon::today()->subDays($d);
                if ($t->isWeekend()) continue;
                $r = rand(1, 100);
                if ($r <= 80) {
                    $st = 'hadir';
                    $jm = sprintf('%02d:%02d:00', rand(7, 8), rand(0, 59));
                    $jk = sprintf('%02d:%02d:00', rand(17, 18), rand(0, 59));
                } elseif ($r <= 90) { $st = 'izin'; $jm = null; $jk = null; }
                elseif ($r <= 95) { $st = 'sakit'; $jm = null; $jk = null; }
                else { $st = 'alpha'; $jm = null; $jk = null; }

                Absensi::firstOrCreate(
                    ['karyawan_id' => $kid, 'tanggal' => $t->format('Y-m-d')],
                    ['jam_masuk' => $jm, 'jam_keluar' => $jk, 'status' => $st, 'created_at' => $now, 'updated_at' => $now]
                );
                $count++;
            }
        }

        $this->command->info("✓ $count data absensi");
    }

    private function seedKomponenGaji(): void
    {
        $rows = [
            ['BPJS Kesehatan', 'potongan', 150000.00],
            ['BPJS Ketenagakerjaan', 'potongan', 200000.00],
            ['Tunjangan Hari Raya', 'penambahan', 1000000.00],
            ['Potongan Terlambat', 'potongan', 50000.00],
            ['Tunjangan Kinerja', 'penambahan', 500000.00],
        ];

        foreach ($rows as [$nama, $jenis, $jumlah]) {
            DB::table('komponen_gaji')->updateOrInsert(
                ['nama_komponen' => $nama, 'jenis' => $jenis],
                ['jumlah' => $jumlah, 'created_at' => now(), 'updated_at' => now()]
            );
        }
        $this->command->info('✓ Komponen Gaji');
    }
}
