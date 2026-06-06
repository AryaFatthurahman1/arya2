@extends('layouts.app')

@section('title', 'Detail Satuan Kerja')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Satuan Kerja</h1>
    <p class="text-gray-600 mt-1">Informasi lengkap satuan kerja</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $satuanKerja->nama_satuan_kerja }}</h2>
        <dl class="grid grid-cols-1 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $satuanKerja->lokasi ?: '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Kepala Satuan Kerja</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $satuanKerja->kepalaSatuanKerja?->nama_lengkap ?: '-' }}</dd>
            </div>
        </dl>
    </div>
    <div class="p-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Karyawan di Satuan Kerja Ini ({{ $satuanKerja->karyawan->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($satuanKerja->karyawan as $k)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $k->nik }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $k->nama_lengkap }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $k->jabatan?->nama_jabatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('satuan-kerja.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('satuan-kerja.edit', $satuanKerja) }}" class="btn-primary">Edit</a>
    </div>
</div>
@endsection
