@extends('layouts.app')

@section('title', 'Detail Jabatan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Jabatan</h1>
    <p class="text-gray-600 mt-1">Informasi jabatan dan karyawan</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $jabatan->nama_jabatan }}</h2>
        <dl class="grid grid-cols-1 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <dt class="text-sm font-medium text-gray-500">Gaji Pokok</dt>
                <dd class="mt-1 text-lg font-bold text-gray-900">Rp {{ number_format($jabatan->gaji_pokok, 0, ',', '.') }}</dd>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500">Tunjangan Jabatan</dt>
                    <dd class="mt-1 font-semibold text-gray-900">Rp {{ number_format($jabatan->tunjangan_jabatan, 0, ',', '.') }}</dd>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500">Tunjangan Transport</dt>
                    <dd class="mt-1 font-semibold text-gray-900">Rp {{ number_format($jabatan->tunjangan_transport, 0, ',', '.') }}</dd>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500">Tunjangan Makan</dt>
                    <dd class="mt-1 font-semibold text-gray-900">Rp {{ number_format($jabatan->tunjangan_makan, 0, ',', '.') }}</dd>
                </div>
            </div>
        </dl>
    </div>
    <div class="p-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Karyawan di Jabatan Ini ({{ $jabatan->karyawan->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jabatan->karyawan as $k)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $k->nik }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $k->nama_lengkap }}</td>
                        <td class="px-4 py-3 text-sm"><span class="px-2 inline-flex text-xs rounded-full bg-green-100 text-green-800">{{ $k->status_karyawan }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('jabatan.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('jabatan.edit', $jabatan) }}" class="btn-primary">Edit</a>
    </div>
</div>
@endsection
