@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-1">Selamat datang di HR Management System</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card p-6 hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Karyawan</p>
                <p class="text-3xl font-bold gradient-text mt-2">{{ $totalKaryawan }}</p>
            </div>
            <div class="rounded-xl bg-blue-100 p-3">
                <i class="fa-solid fa-users text-xl text-blue-600"></i>
            </div>
        </div>
    </div>
    <div class="card p-6 hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Absensi Hari Ini</p>
                <p class="text-3xl font-bold gradient-text mt-2">{{ $absensiHariIni }}</p>
            </div>
            <div class="rounded-xl bg-green-100 p-3">
                <i class="fa-solid fa-calendar-check text-xl text-green-600"></i>
            </div>
        </div>
    </div>
    <div class="card p-6 hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Izin Pending</p>
                <p class="text-3xl font-bold gradient-text mt-2">{{ $izinPending }}</p>
            </div>
            <div class="rounded-xl bg-yellow-100 p-3">
                <i class="fa-solid fa-file-medical text-xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    <div class="card p-6 hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Tugas Pending</p>
                <p class="text-3xl font-bold gradient-text mt-2">{{ $tugasPending }}</p>
            </div>
            <div class="rounded-xl bg-purple-100 p-3">
                <i class="fa-solid fa-tasks text-xl text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-6 lg:col-span-2">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Absensi 7 Hari Terakhir</h2>
        <canvas id="attendanceChart" height="120"></canvas>
    </div>
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Karyawan</h2>
        <canvas id="statusChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Absensi Hari Ini</h2>
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
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($absensiHariIniList as $absen)
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-900">{{ $absen->karyawan?->nama_lengkap }}</td>
                            <td class="px-3 py-2 text-sm text-gray-500">{{ $absen->jam_masuk ?: '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $absen->status == 'hadir' ? 'badge-success' : ($absen->status == 'izin' ? 'badge-warning' : ($absen->status == 'sakit' ? 'badge-info' : 'badge-danger')) }}">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada absensi hari ini</p>
        @endif
    </div>
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Akses Cepat</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('absensi.online') }}" class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 text-center hover:scale-105">
                <i class="fa-solid fa-clock text-2xl text-blue-600 mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Absen Online</p>
            </a>
            <a href="{{ route('leaves.create') }}" class="p-4 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl hover:from-yellow-100 hover:to-amber-100 transition-all duration-300 text-center hover:scale-105">
                <i class="fa-solid fa-file-medical text-2xl text-yellow-600 mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Ajukan Izin</p>
            </a>
            <a href="{{ route('tasks.index') }}" class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-300 text-center hover:scale-105">
                <i class="fa-solid fa-tasks text-2xl text-purple-600 mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Tugas Saya</p>
            </a>
            <a href="{{ route('penggajian.index') }}" class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-300 text-center hover:scale-105">
                <i class="fa-solid fa-money-check-dollar text-2xl text-green-600 mb-2"></i>
                <p class="text-sm font-medium text-gray-700">Penggajian</p>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
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
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
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
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Chart
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
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(251, 191, 36)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            cutout: '60%'
        }
    });
});
</script>
@endpush
@endsection
