@extends('layouts.app')

@section('title', 'Manajemen Dokumen')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Dokumen</h1>
        <p class="text-gray-600 mt-1">Kelola dokumen karyawan</p>
    </div>
    <a href="{{ route('dokumen.create') }}" class="btn-primary">Upload Dokumen</a>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="text" name="search" placeholder="Cari dokumen..." class="input-field" value="{{ request('search') }}">
            <select name="tipe_dokumen" class="input-field">
                <option value="">Semua Tipe</option>
                <option value="kontrak" {{ request('tipe_dokumen') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                <option value="sk" {{ request('tipe_dokumen') == 'sk' ? 'selected' : '' }}>SK</option>
                <option value="sertifikat" {{ request('tipe_dokumen') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                <option value="personal" {{ request('tipe_dokumen') == 'personal' ? 'selected' : '' }}>Personal</option>
                <option value="lainnya" {{ request('tipe_dokumen') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Dokumen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($dokumen as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama_dokumen }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                {{ ucfirst($item->tipe_dokumen) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->karyawan?->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @php
                                $size = $item->file_size;
                                if ($size >= 1073741824) {
                                    $formatted = number_format($size / 1073741824, 2) . ' GB';
                                } elseif ($size >= 1048576) {
                                    $formatted = number_format($size / 1048576, 2) . ' MB';
                                } elseif ($size >= 1024) {
                                    $formatted = number_format($size / 1024, 2) . ' KB';
                                } else {
                                    $formatted = $size . ' B';
                                }
                            @endphp
                            {{ $formatted }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('dokumen.download', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                            <form method="POST" action="{{ route('dokumen.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $dokumen->links() }}</div>
    </div>
</div>
@endsection