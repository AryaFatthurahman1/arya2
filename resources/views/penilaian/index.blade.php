@extends('layouts.app')

@section('title', 'Penilaian Kinerja')
@section('subtitle', 'Kelola penilaian kinerja karyawan IT/IJK')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">Penilaian Kinerja</h1>
        <p class="text-sm text-gray-600 mt-1">Total {{ $penilaian->total() }} penilaian tercatat</p>
    </div>
    <a href="{{ route('penilaian.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus mr-2"></i>Tambah Penilaian
    </a>
</div>

<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
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
        @if(request('karyawan_id'))
            <a href="{{ route('penilaian.index') }}" class="btn-secondary">
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
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Total Nilai</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Predikat</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($penilaian as $item)
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
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-gray-900">{{ number_format($item->total_nilai, 1) }}</span>
                            <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $item->total_nilai >= 80 ? 'bg-gradient-to-r from-green-400 to-emerald-500' : ($item->total_nilai >= 60 ? 'bg-gradient-to-r from-yellow-400 to-amber-500' : 'bg-gradient-to-r from-red-400 to-pink-500') }}" style="width: {{ min(100, $item->total_nilai) }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->total_nilai >= 85)
                            <span class="badge-success"><i class="fa-solid fa-trophy mr-1"></i>Sangat Baik</span>
                        @elseif($item->total_nilai >= 70)
                            <span class="badge-primary"><i class="fa-solid fa-thumbs-up mr-1"></i>Baik</span>
                        @elseif($item->total_nilai >= 55)
                            <span class="badge-warning"><i class="fa-solid fa-minus mr-1"></i>Cukup</span>
                        @else
                            <span class="badge-danger"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Kurang</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('penilaian.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 px-2 py-1 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('penilaian.edit', $item) }}" class="text-amber-600 hover:text-amber-900 hover:bg-amber-50 px-2 py-1 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('penilaian.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus penilaian ini?')">
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
                                <i class="fa-solid fa-star text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada penilaian kinerja</p>
                            <a href="{{ route('penilaian.create') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">+ Tambah penilaian pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penilaian->hasPages())
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50/50 to-indigo-50/30 border-t border-gray-100">
        {{ $penilaian->links() }}
    </div>
    @endif
</div>
@endsection
