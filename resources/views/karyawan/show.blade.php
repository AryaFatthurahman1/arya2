@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Karyawan</h1>
    <p class="text-gray-600 mt-1">Informasi lengkap karyawan</p>
</div>

<div class="card overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center gap-6">
            <div class="flex-shrink-0">
                @if($karyawan->foto_profil)
                    <img src="{{ asset('storage/' . $karyawan->foto_profil) }}" alt="{{ $karyawan->nama_lengkap }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-gray-200">
                @else
                    <div class="h-24 w-24 rounded-full bg-blue-100 flex items-center justify-center ring-4 ring-gray-200">
                        <i class="fa-solid fa-user text-3xl text-blue-600"></i>
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $karyawan->nama_lengkap }}</h2>
                <p class="text-gray-600">{{ $karyawan->jabatan?->nama_jabatan }} | {{ $karyawan->satuanKerja?->nama_satuan_kerja }}</p>
                <p class="text-gray-500 text-sm mt-1">NIK: {{ $karyawan->nik }}</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->email }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->telepon }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->alamat }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Tempat Lahir</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->tempat_lahir }}, {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d F Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Agama</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->agama }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status Pernikahan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $karyawan->status_pernikahan)) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Tanggal Masuk</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d F Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status Karyawan</dt>
                <dd class="mt-1">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($karyawan->status_karyawan) }}</span>
                </dd>
            </div>
            @if($karyawan->nama_bank)
            <div>
                <dt class="text-sm font-medium text-gray-500">Bank</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $karyawan->nama_bank }} - {{ $karyawan->nomor_rekening }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('karyawan.index') }}" class="btn-secondary">Kembali</a>
</div>
@endsection
