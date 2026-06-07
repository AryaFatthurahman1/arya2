@extends('layouts.app')

@section('title', 'Data Absensi')
@section('subtitle', 'Kelola dan pantau data absensi karyawan IT/IJK')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">Manajemen Absensi</h1>
        <p class="text-sm text-gray-600 mt-1">Total {{ $absensi->total() }} data absensi tercatat</p>
    </div>
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('absensi.online') }}" class="btn-primary">
            <i class="fa-solid fa-clock mr-2"></i>Absen Online
        </a>
        <a href="{{ route('absensi.create') }}" class="btn-secondary">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Manual
        </a>
        <a href="{{ route('absensi.export', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}" class="btn-secondary">
            <i class="fa-solid fa-file-export mr-2"></i>Export
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" class="input-field" value="{{ request('tanggal_mulai') }}">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" class="input-field" value="{{ request('tanggal_selesai') }}">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Karyawan</label>
            <select name="karyawan_id" class="input-field">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1">
                <i class="fa-solid fa-filter mr-2"></i>Filter
            </button>
            @if(request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'karyawan_id', 'status']))
                <a href="{{ route('absensi.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-indigo-50/80 to-purple-50/80">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Karyawan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Jam Masuk</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Jam Keluar</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($absensi as $item)
                <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <i class="fa-regular fa-calendar text-indigo-400 mr-1"></i>
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($item->karyawan?->nama_lengkap ?? '?', 0, 1)) }}
                            </div>
                            <div class="ml-3 text-sm font-semibold text-gray-900">{{ $item->karyawan?->nama_lengkap ?? 'Karyawan tidak ditemukan' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <i class="fa-solid fa-arrow-right-to-bracket text-green-500 mr-1"></i>
                        {{ $item->jam_masuk ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <i class="fa-solid fa-arrow-right-from-bracket text-red-500 mr-1"></i>
                        {{ $item->jam_keluar ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = [
                                'hadir' => 'badge-success',
                                'izin' => 'badge-warning',
                                'sakit' => 'badge-info',
                                'alpha' => 'badge-danger'
                            ];
                        @endphp
                        <span class="{{ $statusClass[$item->status] ?? 'badge-primary' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('absensi.edit', $item) }}" class="text-amber-600 hover:text-amber-900 hover:bg-amber-50 px-2 py-1 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('absensi.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus data absensi ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-calendar-check text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data absensi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($absensi->hasPages())
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50/50 to-indigo-50/30 border-t border-gray-100">
        {{ $absensi->links() }}
    </div>
    @endif
</div>
@endsection
