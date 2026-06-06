@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Manajemen Absensi</h1>
    <p class="text-gray-600 mt-1">Kelola dan pantau data absensi karyawan</p>
</div>

<div class="card overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('absensi.online') }}" class="btn-primary flex items-center"><i class="fa-solid fa-clock me-2"></i>Absen Online</a>
            <a href="{{ route('absensi.create') }}" class="btn-secondary flex items-center"><i class="fa-solid fa-plus me-2"></i>Tambah Manual</a>
            <a href="{{ route('absensi.export', ['tahun' => request('tahun'), 'bulan' => request('bulan')]) }}" class="btn-secondary flex items-center"><i class="fa-solid fa-download me-2"></i>Export Excel</a>
        </div>
        <form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file" class="input-field text-sm py-1.5" accept=".xlsx,.xls,.csv" required>
            <button type="submit" class="btn-secondary flex items-center"><i class="fa-solid fa-file-import me-2"></i>Import Excel</button>
        </form>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="date" name="tanggal_mulai" class="input-field" value="{{ request('tanggal_mulai') }}" placeholder="Dari tanggal">
            <input type="date" name="tanggal_selesai" class="input-field" value="{{ request('tanggal_selesai') }}" placeholder="Sampai tanggal">
            <select name="karyawan_id" class="input-field">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                @endforeach
            </select>
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($absensi as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->karyawan?->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->jam_masuk ?: '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->jam_keluar ?: '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = ['hadir'=>'bg-green-100 text-green-800','izin'=>'bg-yellow-100 text-yellow-800','sakit'=>'bg-blue-100 text-blue-800','alpha'=>'bg-red-100 text-red-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('absensi.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('absensi.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $absensi->links() }}</div>
    </div>
</div>
@endsection
