@extends('layouts.app')

@section('title', 'Penilaian Kinerja')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Penilaian Kinerja</h1>
        <p class="text-gray-600 mt-1">Kelola penilaian kinerja karyawan</p>
    </div>
    <a href="{{ route('penilaian.create') }}" class="btn-primary">Tambah Penilaian</a>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 flex gap-3">
            <select name="karyawan_id" class="input-field">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($penilaian as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->karyawan?->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->periode)->format('F Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->total_nilai >= 80 ? 'bg-green-100 text-green-800' : ($item->total_nilai >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $item->total_nilai }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('penilaian.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            <a href="{{ route('penilaian.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('penilaian.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $penilaian->links() }}</div>
    </div>
</div>
@endsection
