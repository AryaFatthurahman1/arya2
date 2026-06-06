@extends('layouts.app')

@section('title', 'Data Satuan Kerja')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Data Satuan Kerja</h1>
        <p class="text-gray-600 mt-1">Kelola data satuan kerja / departemen</p>
    </div>
    <a href="{{ route('satuan-kerja.create') }}" class="btn-primary">Tambah Satuan Kerja</a>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 flex gap-3">
            <input type="text" name="search" placeholder="Cari satuan kerja..." class="input-field" value="{{ request('search') }}">
            <button type="submit" class="btn-secondary">Cari</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Satuan Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kepala</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($satuanKerja as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama_satuan_kerja }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->lokasi ?: '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->kepalaSatuanKerja?->nama_lengkap ?: '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('satuan-kerja.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            <a href="{{ route('satuan-kerja.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('satuan-kerja.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $satuanKerja->links() }}</div>
    </div>
</div>
@endsection
