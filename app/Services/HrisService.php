<?php

namespace App\Services;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Penggajian;
use App\Models\PengajuanIzin;
use App\Models\Tugas;
use App\Models\Penilaian;
use Carbon\Carbon;

class HrisService
{
    public function getDashboardStats(): array
    {
        return [
            'totalKaryawan' => Karyawan::count(),
            'absensiHariIni' => Absensi::whereDate('tanggal', today())->where('status', 'hadir')->count(),
            'izinPending' => PengajuanIzin::where('status', 'pending')->count(),
            'tugasPending' => Tugas::whereIn('status', ['baru', 'diproses'])->count(),
        ];
    }

    public function getAttendanceStats(int $days = 7): array
    {
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = Absensi::whereDate('tanggal', $date)->where('status', 'hadir')->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getEmployeeStatusDistribution(): array
    {
        return [
            'labels' => ['Tetap', 'Kontrak', 'Percobaan'],
            'data' => [
                Karyawan::where('status_karyawan', 'tetap')->count(),
                Karyawan::where('status_karyawan', 'kontrak')->count(),
                Karyawan::where('status_karyawan', 'percobaan')->count(),
            ],
        ];
    }

    public function calculatePayroll(int $karyawanId, string $periode): array
    {
        $karyawan = Karyawan::with('jabatan')->findOrFail($karyawanId);

        $periodeCarbon = Carbon::parse($periode);
        $hariKerja = Absensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $periodeCarbon->month)
            ->whereYear('tanggal', $periodeCarbon->year)
            ->where('status', 'hadir')
            ->count();

        $hariAlpha = Absensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $periodeCarbon->month)
            ->whereYear('tanggal', $periodeCarbon->year)
            ->where('status', 'alpha')
            ->count();

        $jabatan = $karyawan->jabatan;
        $gajiPokok = $jabatan ? $jabatan->gaji_pokok : 0;
        $tunjanganJabatan = $jabatan ? $jabatan->tunjangan_jabatan : 0;
        $tunjanganTransport = $jabatan ? $jabatan->tunjangan_transport : 0;
        $tunjanganMakan = $jabatan ? $jabatan->tunjangan_makan : 0;
        $potonganAbsen = $hariAlpha * ($gajiPokok > 0 ? $gajiPokok / 22 : 0);

        $totalGaji = $gajiPokok + $tunjanganJabatan + $tunjanganTransport + $tunjanganMakan - $potonganAbsen;

        return [
            'karyawan_id' => $karyawanId,
            'periode' => $periode,
            'gaji_pokok' => $gajiPokok,
            'tunjangan_jabatan' => $tunjanganJabatan,
            'tunjangan_transport' => $tunjanganTransport,
            'tunjangan_makan' => $tunjanganMakan,
            'tunjangan_lainnya' => 0,
            'potongan_absen' => $potonganAbsen,
            'potongan_lainnya' => 0,
            'total_gaji' => $totalGaji,
        ];
    }

    public function getPerformanceStats(int $karyawanId): array
    {
        $penilaian = Penilaian::where('karyawan_id', $karyawanId)->latest()->first();

        if (!$penilaian) {
            return ['avg' => 0, 'total' => 0, 'latest' => null];
        }

        return [
            'avg' => Penilaian::where('karyawan_id', $karyawanId)->avg('total_nilai'),
            'total' => Penilaian::where('karyawan_id', $karyawanId)->count(),
            'latest' => $penilaian,
        ];
    }
}
