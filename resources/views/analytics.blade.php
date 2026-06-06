@extends('layouts.app')

@section('title', 'Analytics & Reports')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Analytics & Reports</h1>
    <p class="text-gray-600 mt-1">Comprehensive HRIS analytics and insights</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Karyawan</p>
                <p class="text-3xl font-bold gradient-text mt-2">{{ array_sum($employeeStatus) }}</p>
            </div>
            <div class="rounded-xl bg-blue-100 p-3">
                <i class="fa-solid fa-users text-xl text-blue-600"></i>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Payroll (Bulan Ini)</p>
                <p class="text-3xl font-bold gradient-text mt-2">Rp {{ number_format($payrollStats['total_payroll'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl bg-green-100 p-3">
                <i class="fa-solid fa-money-bill-wave text-xl text-green-600"></i>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Avg Performance Rating</p>
                <p class="text-3xl font-bold gradient-text mt-2">{{ number_format($performanceStats['avg_rating'], 1) }}/5</p>
            </div>
            <div class="rounded-xl bg-purple-100 p-3">
                <i class="fa-solid fa-star text-xl text-purple-600"></i>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Task Completion Rate</p>
                <p class="text-3xl font-bold gradient-text mt-2">
                    @php
                        $totalTasks = array_sum($taskStats);
                        $completionRate = $totalTasks > 0 ? round(($taskStats['selesai'] / $totalTasks) * 100, 1) : 0;
                    @endphp
                    {{ $completionRate }}%
                </p>
            </div>
            <div class="rounded-xl bg-yellow-100 p-3">
                <i class="fa-solid fa-chart-line text-xl text-yellow-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Analytics -->
<div class="card p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Absensi 7 Hari Terakhir</h2>
    <canvas id="attendanceAnalyticsChart" height="120"></canvas>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Employee Status Distribution -->
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Karyawan</h2>
        <canvas id="employeeStatusChart" height="200"></canvas>
    </div>

    <!-- Leave Statistics -->
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pengajuan Izin</h2>
        <canvas id="leaveStatsChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Task Statistics -->
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Tugas</h2>
        <canvas id="taskStatsChart" height="200"></canvas>
    </div>

    <!-- Payroll Status -->
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Penggajian Bulan Ini</h2>
        <canvas id="payrollStatusChart" height="200"></canvas>
    </div>
</div>

<!-- Detailed Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Status Karyawan</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Tetap</span>
                <span class="text-lg font-bold text-green-600">{{ $employeeStatus['tetap'] }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Kontrak</span>
                <span class="text-lg font-bold text-blue-600">{{ $employeeStatus['kontrak'] }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Percobaan</span>
                <span class="text-lg font-bold text-yellow-600">{{ $employeeStatus['percobaan'] }}</span>
            </div>
        </div>
    </div>

    <div class="card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Status Tugas</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Baru</span>
                <span class="text-lg font-bold text-gray-600">{{ $taskStats['baru'] }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Diproses</span>
                <span class="text-lg font-bold text-blue-600">{{ $taskStats['diproses'] }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Selesai</span>
                <span class="text-lg font-bold text-green-600">{{ $taskStats['selesai'] }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Terlambat</span>
                <span class="text-lg font-bold text-red-600">{{ $taskStats['terlambat'] }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attendance Analytics Chart
    const attendanceCtx = document.getElementById('attendanceAnalyticsChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'bar',
        data: {
            labels: @php echo json_encode(array_column($attendanceData, 'label')); @endphp,
            datasets: [
                {
                    label: 'Hadir',
                    data: @php echo json_encode(array_column($attendanceData, 'hadir')); @endphp,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                },
                {
                    label: 'Izin',
                    data: @php echo json_encode(array_column($attendanceData, 'izin')); @endphp,
                    backgroundColor: 'rgba(251, 191, 36, 0.8)',
                    borderColor: 'rgb(251, 191, 36)',
                    borderWidth: 1
                },
                {
                    label: 'Sakit',
                    data: @php echo json_encode(array_column($attendanceData, 'sakit')); @endphp,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                },
                {
                    label: 'Alpha',
                    data: @php echo json_encode(array_column($attendanceData, 'alpha')); @endphp,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
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

    // Employee Status Chart
    const employeeStatusCtx = document.getElementById('employeeStatusChart').getContext('2d');
    new Chart(employeeStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tetap', 'Kontrak', 'Percobaan'],
            datasets: [{
                data: @php echo json_encode([$employeeStatus['tetap'], $employeeStatus['kontrak'], $employeeStatus['percobaan']]); @endphp,
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

    // Leave Stats Chart
    const leaveStatsCtx = document.getElementById('leaveStatsChart').getContext('2d');
    new Chart(leaveStatsCtx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Disetujui', 'Ditolak'],
            datasets: [{
                data: @php echo json_encode([$leaveStats['pending'], $leaveStats['approved'], $leaveStats['rejected']]); @endphp,
                backgroundColor: [
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(251, 191, 36)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
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
            }
        }
    });

    // Task Stats Chart
    const taskStatsCtx = document.getElementById('taskStatsChart').getContext('2d');
    new Chart(taskStatsCtx, {
        type: 'bar',
        data: {
            labels: ['Baru', 'Diproses', 'Selesai', 'Terlambat'],
            datasets: [{
                label: 'Jumlah Tugas',
                data: @php echo json_encode([$taskStats['baru'], $taskStats['diproses'], $taskStats['selesai'], $taskStats['terlambat']]); @endphp,
                backgroundColor: [
                    'rgba(107, 114, 128, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(107, 114, 128)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 1
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

    // Payroll Status Chart
    const payrollStatusCtx = document.getElementById('payrollStatusChart').getContext('2d');
    new Chart(payrollStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Dibayar', 'Pending'],
            datasets: [{
                data: @php echo json_encode([$payrollStats['paid_count'], $payrollStats['pending_count']]); @endphp,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 191, 36, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
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
