@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Absensi</h1>
    <p class="text-gray-600 mt-1">Informasi lengkap absensi karyawan</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <dl class="grid grid-cols-1 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Karyawan</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $absensi->karyawan?->nama_lengkap }} ({{ $absensi->karyawan?->nik }})</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d F Y') }}</dd>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jam Masuk</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $absensi->jam_masuk ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jam Keluar</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $absensi->jam_keluar ?: '-' }}</dd>
                </div>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @php $statusClass = ['hadir'=>'bg-green-100 text-green-800','izin'=>'bg-yellow-100 text-yellow-800','sakit'=>'bg-blue-100 text-blue-800','alpha'=>'bg-red-100 text-red-800']; @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$absensi->status] }}">
                        {{ ucfirst($absensi->status) }}
                    </span>
                </dd>
            </div>
            @if($absensi->keterangan)
            <div>
                <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $absensi->keterangan }}</dd>
            </div>
            @endif
            @if($absensi->foto_absen)
            <div>
                <dt class="text-sm font-medium text-gray-500">Bukti Foto</dt>
                <dd class="mt-1"><a href="{{ asset('storage/' . $absensi->foto_absen) }}" target="_blank" class="text-blue-600 hover:text-blue-900">Lihat Foto</a></dd>
            </div>
            @endif
        </dl>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('absensi.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('absensi.edit', $absensi) }}" class="btn-primary">Edit</a>
    </div>
</div>
@endsection
