@extends('layouts.app')

@section('title', 'Penggajian')
@section('subtitle', 'Kelola perhitungan dan slip gaji karyawan')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">Manajemen Penggajian</h1>
        <p class="text-sm text-gray-600 mt-1">Total {{ $penggajian->total() }} data gaji tercatat</p>
    </div>
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('penggajian.export', ['periode' => request('periode'), 'karyawan_id' => request('karyawan_id')]) }}" class="btn-secondary">
            <i class="fa-solid fa-file-export mr-2"></i>Export
        </a>
        <a href="{{ route('penggajian.create') }}" class="btn-primary">
            <i class="fa-solid fa-calculator mr-2"></i>Hitung Gaji
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Periode</label>
            <input type="month" name="periode" class="input-field" value="{{ request('periode') }}">
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Karyawan</label>
            <select name="karyawan_id" class="input-field">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>
        @if(request()->hasAny(['periode', 'karyawan_id']))
            <a href="{{ route('penggajian.index') }}" class="btn-secondary">
                <i class="fa-solid fa-times mr-2"></i>Reset
            </a>
        @endif
    </form>
</div>

<!-- Data Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-indigo-50/80 to-purple-50/80">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Karyawan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Total Gaji</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($penggajian as $item)
                <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($item->karyawan?->nama_lengkap ?? '?', 0, 1)) }}
                            </div>
                            <div class="ml-3 text-sm font-semibold text-gray-900">{{ $item->karyawan?->nama_lengkap ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <i class="fa-regular fa-calendar text-indigo-400 mr-1"></i>
                        {{ \Carbon\Carbon::parse($item->periode)->translatedFormat('F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <i class="fa-solid fa-rupiah-sign text-green-500 mr-1 text-sm"></i>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($item->total_gaji, 0, ',', '.') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status == 'dibayar')
                            <span class="badge-success"><i class="fa-solid fa-circle-check mr-1"></i>Dibayar</span>
                        @else
                            <span class="badge-warning"><i class="fa-solid fa-hourglass-half mr-1"></i>Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('penggajian.slip', $item) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 px-2 py-1 rounded-lg transition-colors" target="_blank" title="Lihat Slip Gaji">
                                <i class="fa-solid fa-receipt"></i>
                            </a>
                            @if($item->status == 'pending')
                                <form method="GET" action="{{ route('penggajian.paid', $item) }}" class="inline">
                                    <button type="submit" class="text-green-600 hover:text-green-900 hover:bg-green-50 px-2 py-1 rounded-lg transition-colors" title="Tandai Dibayar">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-money-check-dollar text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data penggajian</p>
                            <a href="{{ route('penggajian.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">+ Hitung gaji pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penggajian->hasPages())
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50/50 to-indigo-50/30 border-t border-gray-100">
        {{ $penggajian->links() }}
    </div>
    @endif
</div>
@endsection
