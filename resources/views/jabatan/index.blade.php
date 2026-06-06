@extends('layouts.app')

@section('title', 'Data Jabatan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Data Jabatan</h1>
        <p class="text-gray-600 mt-1">Kelola data jabatan dan komponen gaji</p>
    </div>
    <a href="{{ route('jabatan.create') }}" class="btn-primary">Tambah Jabatan</a>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 flex gap-3">
            <input type="text" name="search" placeholder="Cari jabatan..." class="input-field" value="{{ request('search') }}">
            <button type="submit" class="btn-secondary">Cari</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gaji Pokok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tunjangan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($jabatan as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama_jabatan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Transport: Rp {{ number_format($item->tunjangan_transport, 0, ',', '.') }} |
                            Makan: Rp {{ number_format($item->tunjangan_makan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('jabatan.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            <a href="{{ route('jabatan.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('jabatan.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $jabatan->links() }}</div>
    </div>
</div>
@endsection
