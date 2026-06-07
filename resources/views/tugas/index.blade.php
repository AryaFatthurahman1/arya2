@extends('layouts.app')

@section('title', 'Penugasan Tugas')
@section('subtitle', 'Kelola dan pantau tugas karyawan IT/IJK')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">Penugasan Tugas</h1>
        <p class="text-sm text-gray-600 mt-1">Total {{ $tugas->total() }} tugas tercatat</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus mr-2"></i>Buat Tugas
    </a>
</div>

<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Tugas</label>
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>
        @if(request('status'))
            <a href="{{ route('tasks.index') }}" class="btn-secondary">
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Judul Tugas</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Ditugaskan Ke</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Tenggat</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($tugas as $item)
                <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-sm">
                                <i class="fa-solid fa-list-check text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900">{{ $item->judul }}</div>
                                <div class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($item->deskripsi, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ strtoupper(substr($item->assignedTo?->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="ml-2 text-sm text-gray-700">{{ $item->assignedTo?->name ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        @if($item->tanggal_tenggat)
                            <i class="fa-regular fa-calendar text-indigo-400 mr-1"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal_tenggat)->format('d M Y') }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = [
                                'baru' => 'badge-info',
                                'diproses' => 'badge-warning',
                                'selesai' => 'badge-success',
                                'terlambat' => 'badge-danger'
                            ];
                            $statusIcon = [
                                'baru' => 'fa-circle-plus',
                                'diproses' => 'fa-spinner',
                                'selesai' => 'fa-circle-check',
                                'terlambat' => 'fa-circle-exclamation'
                            ];
                        @endphp
                        <span class="{{ $statusClass[$item->status] ?? 'badge-primary' }}">
                            <i class="fa-solid {{ $statusIcon[$item->status] ?? 'fa-circle' }} mr-1"></i>
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('tasks.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 px-2 py-1 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('tasks.edit', $item) }}" class="text-amber-600 hover:text-amber-900 hover:bg-amber-50 px-2 py-1 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-list-check text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada tugas</p>
                            <a href="{{ route('tasks.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">+ Buat tugas pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tugas->hasPages())
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50/50 to-indigo-50/30 border-t border-gray-100">
        {{ $tugas->links() }}
    </div>
    @endif
</div>
@endsection
