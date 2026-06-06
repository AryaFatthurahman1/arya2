@extends('layouts.app')

@section('title', 'Penggajian')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Penggajian</h1>
        <p class="text-gray-600 mt-1">Kelola perhitungan dan slip gaji karyawan</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('penggajian.export', ['periode' => request('periode')]) }}" class="btn-secondary">Export Excel</a>
        <a href="{{ route('penggajian.create') }}" class="btn-primary">Hitung Gaji</a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 flex gap-3">
            <input type="month" name="periode" class="input-field" value="{{ request('periode') }}">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Gaji</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($penggajian as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->karyawan?->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->periode)->format('F Y') }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($item->total_gaji, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $item->status == 'dibayar' ? 'Dibayar' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('penggajian.slip', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3" target="_blank">Slip</a>
                            @if($item->status == 'pending')
                                <form method="GET" action="{{ route('penggajian.paid', $item) }}" class="inline">
                                    <button type="submit" class="text-green-600 hover:text-green-900">Tandai Dibayar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $penggajian->links() }}</div>
    </div>
</div>
@endsection
