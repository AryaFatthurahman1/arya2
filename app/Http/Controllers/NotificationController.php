<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanIzin;
use App\Models\Tugas;
use App\Models\Absensi;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = [];

        // Get pending leave requests (for Atasan/Admin)
        if ($user->hasRole(['admin', 'atasan'])) {
            $pendingIzin = PengajuanIzin::with('karyawan')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
            
            foreach ($pendingIzin as $izin) {
                $notifications[] = [
                    'id' => 'izin-' . $izin->id,
                    'type' => 'izin',
                    'title' => 'Pengajuan Izin Baru',
                    'message' => $izin->karyawan->nama_lengkap . ' mengajukan izin ' . $izin->tipe_izin,
                    'time' => $izin->created_at->diffForHumans(),
                    'url' => route('leaves.show', $izin->id),
                    'icon' => 'fa-file-medical',
                    'color' => 'yellow'
                ];
            }
        }

        // Get new tasks (for Karyawan)
        if ($user->hasRole(['admin', 'atasan'])) {
            $newTasks = Tugas::where('assigned_to', $user->id)
                ->whereIn('status', ['baru', 'diproses'])
                ->latest()
                ->take(5)
                ->get();
            
            foreach ($newTasks as $task) {
                $notifications[] = [
                    'id' => 'tugas-' . $task->id,
                    'type' => 'tugas',
                    'title' => 'Tugas Baru',
                    'message' => $task->judul,
                    'time' => $task->created_at->diffForHumans(),
                    'url' => route('tasks.show', $task->id),
                    'icon' => 'fa-tasks',
                    'color' => 'purple'
                ];
            }
        }

        // Get attendance alerts (for Admin/Atasan)
        if ($user->hasRole(['admin', 'atasan'])) {
            $todayAbsensi = Absensi::with('karyawan')
                ->whereDate('tanggal', today())
                ->where('status', 'alpha')
                ->latest()
                ->take(5)
                ->get();
            
            foreach ($todayAbsensi as $absen) {
                $notifications[] = [
                    'id' => 'absensi-' . $absen->id,
                    'type' => 'absensi',
                    'title' => 'Absensi Alpha',
                    'message' => $absen->karyawan->nama_lengkap . ' tidak hadir hari ini',
                    'time' => 'Hari ini',
                    'url' => route('attendance.show', $absen->id),
                    'icon' => 'fa-calendar-xmark',
                    'color' => 'red'
                ];
            }
        }

        // Sort by time
        usort($notifications, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return response()->json([
            'notifications' => array_slice($notifications, 0, 10),
            'unread_count' => count($notifications)
        ]);
    }

    public function markAsRead(Request $request)
    {
        // In a real implementation, you would store read notifications in the database
        // For now, we'll just return success
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // In a real implementation, you would mark all notifications as read
        return response()->json(['success' => true]);
    }
}
