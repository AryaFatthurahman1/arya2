<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\PengajuanIzin;
use App\Models\Tugas;
use App\Models\Penggajian;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function welcome()
    {
        $totalKaryawan = Karyawan::count();
        $absensiHariIni = Absensi::whereDate('tanggal', today())->count();
        $izinPending = PengajuanIzin::where('status', 'pending')->count();
        $tugasPending = Tugas::where('status', 'baru')->orWhere('status', 'diproses')->count();

        Log::info('Dashboard accessed', ['user_id' => auth()->id()]);

        return view('dashboard', compact('totalKaryawan', 'absensiHariIni', 'izinPending', 'tugasPending'));
    }

    public function index()
    {
        return $this->welcome();
    }

    public function analytics()
    {
        // Attendance analytics
        $attendanceData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $attendanceData[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('D'),
                'hadir' => Absensi::whereDate('tanggal', $date)->where('status', 'hadir')->count(),
                'izin' => Absensi::whereDate('tanggal', $date)->where('status', 'izin')->count(),
                'sakit' => Absensi::whereDate('tanggal', $date)->where('status', 'sakit')->count(),
                'alpha' => Absensi::whereDate('tanggal', $date)->where('status', 'alpha')->count(),
            ];
        }

        // Employee status distribution
        $employeeStatus = [
            'tetap' => Karyawan::where('status_karyawan', 'tetap')->count(),
            'kontrak' => Karyawan::where('status_karyawan', 'kontrak')->count(),
            'percobaan' => Karyawan::where('status_karyawan', 'percobaan')->count(),
        ];

        // Leave statistics
        $leaveStats = [
            'pending' => PengajuanIzin::where('status', 'pending')->count(),
            'approved' => PengajuanIzin::where('status', 'disetujui')->count(),
            'rejected' => PengajuanIzin::where('status', 'ditolak')->count(),
        ];

        // Task statistics
        $taskStats = [
            'baru' => Tugas::where('status', 'baru')->count(),
            'diproses' => Tugas::where('status', 'diproses')->count(),
            'selesai' => Tugas::where('status', 'selesai')->count(),
            'terlambat' => Tugas::where('status', 'terlambat')->count(),
        ];

        // Performance statistics
        $performanceStats = [
            'avg_rating' => Penilaian::avg('nilai') ?? 0,
            'total_reviews' => Penilaian::count(),
        ];

        // Payroll statistics
        $payrollStats = [
            'total_payroll' => Penggajian::whereMonth('periode_bulan', Carbon::now()->month)->sum('total_gaji_bersih'),
            'paid_count' => Penggajian::where('status', 'dibayar')->count(),
            'pending_count' => Penggajian::where('status', 'pending')->count(),
        ];

        Log::info('Analytics accessed', ['user_id' => auth()->id()]);

        return view('analytics', compact(
            'attendanceData',
            'employeeStatus',
            'leaveStats',
            'taskStats',
            'performanceStats',
            'payrollStats'
        ));
    }

    public function import()
    {
        return view('import.index');
    }
}
