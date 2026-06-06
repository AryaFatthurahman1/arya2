@extends('layouts.app')

@section('title', 'Penugasan Tugas')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Penugasan Tugas</h1>
        <p class="text-gray-600 mt-1">Kelola dan pantau tugas karyawan</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">Buat Tugas</a>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 flex gap-3">
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ditugaskan Ke</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenggat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($tugas as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->judul }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->assignedTo?->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $item->tanggal_tenggat ? \Carbon\Carbon::parse($item->tanggal_tenggat)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = ['baru'=>'bg-blue-100 text-blue-800','diproses'=>'bg-yellow-100 text-yellow-800','selesai'=>'bg-green-100 text-green-800','terlambat'=>'bg-red-100 text-red-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$item->status] }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('tasks.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            <a href="{{ route('tasks.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('tasks.destroy', $item) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $tugas->links() }}</div>
    </div>
</div>
@endsection
