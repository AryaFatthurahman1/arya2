@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('subtitle', 'Kelola data seluruh karyawan IT/IJK')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">Data Karyawan</h1>
        <p class="text-sm text-gray-600 mt-1">Total {{ $karyawan->total() }} karyawan terdaftar</p>
    </div>
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('karyawan.download-template') }}" class="btn-secondary">
            <i class="fa-solid fa-download mr-2"></i>Template
        </a>
        <a href="{{ route('karyawan.export') }}" class="btn-secondary">
            <i class="fa-solid fa-file-export mr-2"></i>Export
        </a>
        <a href="{{ route('karyawan.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Karyawan
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" placeholder="Cari karyawan berdasarkan nama, NIK, atau jabatan..." class="input-field pl-11" value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn-primary">
            <i class="fa-solid fa-search mr-2"></i>Cari
        </button>
        @if(request('search'))
            <a href="{{ route('karyawan.index') }}" class="btn-secondary">
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">NIK</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Satuan Kerja</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($karyawan as $item)
                <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ $item->nik }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900">{{ $item->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $item->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->jabatan?->nama_jabatan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->satuanKerja?->nama_satuan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $badgeClass = match($item->status_karyawan) {
                                'tetap' => 'badge-success',
                                'kontrak' => 'badge-warning',
                                'percobaan' => 'badge-info',
                                default => 'badge-primary'
                            };
                        @endphp
                        <span class="{{ $badgeClass }}">
                            {{ ucfirst($item->status_karyawan) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('karyawan.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 px-2 py-1 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('karyawan.edit', $item) }}" class="text-amber-600 hover:text-amber-900 hover:bg-amber-50 px-2 py-1 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('karyawan.destroy', $item) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
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
                                <i class="fa-solid fa-users text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data karyawan</p>
                            <a href="{{ route('karyawan.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">+ Tambah karyawan pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($karyawan->hasPages())
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50/50 to-indigo-50/30 border-t border-gray-100">
        {{ $karyawan->links() }}
    </div>
    @endif
</div>
@endsection
