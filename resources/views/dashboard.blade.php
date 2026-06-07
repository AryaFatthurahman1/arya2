@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, ' . Auth::user()->name . '! 👋')

@section('content')
<!-- Welcome Banner -->
<div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 p-8 text-white shadow-2xl shadow-indigo-500/30">
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang di HRIS IT/IJK</h1>
            <p class="mt-2 text-indigo-100 text-sm">Sistem Manajemen Sumber Daya Manusia terpadu untuk karyawan IT/IJK</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold"><i class="fa-solid fa-user-tie mr-1"></i> {{ ucfirst(Auth::user()->role ?? 'User') }}</span>
            </div>
        </div>
        <div class="hidden md:block">
            <div class="w-32 h-32 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center ring-4 ring-white/20">
                <i class="fa-solid fa-gauge-high text-5xl text-white/80"></i>
            </div>
        </div>
    </div>
</div>

@php $statsData = [
    ["label" => "Total Karyawan", "value" => $totalKaryawan, "suffix" => "", "color" => "blue", "icon" => "users"],
    ["label" => "Absensi Hari Ini", "value" => $absensiHariIni, "suffix" => "", "color" => "green", "icon" => "calendar-check"],
    ["label" => "Izin Pending", "value" => $izinPending, "suffix" => "", "color" => "amber", "icon" => "clock"],
    ["label" => "Tugas Pending", "value" => $tugasPending, "suffix" => "", "color" => "purple", "icon" => "list-check"],
]; @endphp
<div id="quick-stats-root" data-stats='{{ json_encode($statsData) }}'></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-6 lg:col-span-2 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/30 blur-2xl -mr-16 -mt-16"></div>
        <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-indigo-500"></i>
            Statistik Absensi 7 Hari Terakhir
        </h2>
        <p class="text-xs text-gray-500 mb-4">Tren kehadiran karyawan</p>
        <canvas id="attendanceChart" height="120"></canvas>
    </div>
    <div class="card p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 rounded-full bg-gradient-to-br from-purple-100/30 to-pink-100/30 blur-2xl -mr-16 -mt-16"></div>
        <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-purple-500"></i>
            Status Karyawan
        </h2>
        <p class="text-xs text-gray-500 mb-4">Distribusi jenis status</p>
        <canvas id="statusChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fa-solid fa-calendar-day text-green-500"></i>
            Absensi Hari Ini
        </h2>
        <p class="text-xs text-gray-500 mb-4">5 absensi terbaru</p>
        @php
            $absensiHariIniList = \App\Models\Absensi::with('karyawan')
                ->whereDate('tanggal', today())
                ->latest()
                ->take(5)
                ->get();
        @endphp
        @if($absensiHariIniList->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-indigo-50/30">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-bold text-indigo-700 uppercase">Karyawan</th>
                            <th class="px-3 py-2 text-left text-xs font-bold text-indigo-700 uppercase">Jam</th>
                            <th class="px-3 py-2 text-left text-xs font-bold text-indigo-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($absensiHariIniList as $absen)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-3 py-2 text-sm font-medium text-gray-900">{{ $absen->karyawan?->nama_lengkap ?? 'N/A' }}</td>
                            <td class="px-3 py-2 text-sm text-gray-500">{{ $absen->jam_masuk ?: '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="badge-{{ $absen->status == 'hadir' ? 'success' : ($absen->status == 'izin' ? 'warning' : ($absen->status == 'sakit' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-12 h-12 mx-auto rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-2">
                    <i class="fa-regular fa-calendar text-indigo-400"></i>
                </div>
                <p class="text-gray-500 text-sm">Belum ada absensi hari ini</p>
            </div>
        @endif
    </div>
    <div class="card p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
            <i class="fa-solid fa-bolt text-amber-500"></i>
            Akses Cepat
        </h2>
        <p class="text-xs text-gray-500 mb-4">Pintasan ke fitur utama</p>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('absensi.online') }}" class="group p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 text-center hover:scale-105 border border-blue-100/50">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-2 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clock text-white text-lg"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">Absen Online</p>
            </a>
            <a href="{{ route('leaves.create') }}" class="group p-4 bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl hover:from-amber-100 hover:to-yellow-100 transition-all duration-300 text-center hover:scale-105 border border-amber-100/50">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center mx-auto mb-2 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-medical text-white text-lg"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">Ajukan Izin</p>
            </a>
            <a href="{{ route('tasks.index') }}" class="group p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-300 text-center hover:scale-105 border border-purple-100/50">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mx-auto mb-2 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-list-check text-white text-lg"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">Tugas Saya</p>
            </a>
            <a href="{{ route('penggajian.index') }}" class="group p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl hover:from-emerald-100 hover:to-green-100 transition-all duration-300 text-center hover:scale-105 border border-emerald-100/50">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center mx-auto mb-2 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-money-check-dollar text-white text-lg"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">Penggajian</p>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const gradient = attendanceCtx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: @php
                $labels = [];
                $hadirData = [];
                for($i = 6; $i >= 0; $i--) {
                    $date = \Carbon\Carbon::today()->subDays($i);
                    $labels[] = $date->format('D');
                    $hadirData[] = \App\Models\Absensi::whereDate('tanggal', $date)->where('status', 'hadir')->count();
                }
                echo json_encode($labels);
            @endphp,
            datasets: [{
                label: 'Kehadiran',
                data: @php echo json_encode($hadirData); @endphp,
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(99, 102, 241)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tetap', 'Kontrak', 'Percobaan'],
            datasets: [{
                data: @php
                    $tetap = \App\Models\Karyawan::where('status_karyawan', 'tetap')->count();
                    $kontrak = \App\Models\Karyawan::where('status_karyawan', 'kontrak')->count();
                    $percobaan = \App\Models\Karyawan::where('status_karyawan', 'percobaan')->count();
                    echo json_encode([$tetap, $kontrak, $percobaan]);
                @endphp,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(251, 191, 36, 0.8)'
                ],
                borderColor: ['rgb(34, 197, 94)', 'rgb(59, 130, 246)', 'rgb(251, 191, 36)'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 11, weight: '600' } } }
            },
            cutout: '60%'
        }
    });
});
</script>
@endpush
@endsection
