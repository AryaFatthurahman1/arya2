@extends('layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Penggajian</h1>
    <p class="text-gray-600 mt-1">Rincian perhitungan gaji karyawan</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold">{{ $penggajian->karyawan?->nama_lengkap }}</h2>
            <p class="text-sm text-gray-500">{{ $penggajian->karyawan?->jabatan?->nama_jabatan }} | {{ \Carbon\Carbon::parse($penggajian->periode)->format('F Y') }}</p>
        </div>

        <div class="space-y-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Pendapatan</h3>
                <div class="space-y-2">
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Gaji Pokok</span><span class="text-sm font-medium">Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Tunjangan Jabatan</span><span class="text-sm font-medium">Rp {{ number_format($penggajian->tunjangan_jabatan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Transport</span><span class="text-sm font-medium">Rp {{ number_format($penggajian->tunjangan_transport, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Makan</span><span class="text-sm font-medium">Rp {{ number_format($penggajian->tunjangan_makan, 0, ',', '.') }}</span></div>
                    @if($penggajian->tunjangan_lainnya > 0)
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Tunjangan Lainnya</span><span class="text-sm font-medium">Rp {{ number_format($penggajian->tunjangan_lainnya, 0, ',', '.') }}</span></div>
                    @endif
                </div>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Potongan</h3>
                <div class="space-y-2">
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Potongan Absensi</span><span class="text-sm font-medium text-red-600">- Rp {{ number_format($penggajian->potongan_absen, 0, ',', '.') }}</span></div>
                    @if($penggajian->potongan_lainnya > 0)
                    <div class="flex justify-between"><span class="text-sm text-gray-600">Potongan Lainnya</span><span class="text-sm font-medium text-red-600">- Rp {{ number_format($penggajian->potongan_lainnya, 0, ',', '.') }}</span></div>
                    @endif
                </div>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg flex justify-between items-center">
                <span class="text-lg font-bold text-blue-700">GAJI BERSIH</span>
                <span class="text-xl font-bold text-blue-700">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $penggajian->status == 'dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $penggajian->status == 'dibayar' ? 'Sudah Dibayar' : 'Menunggu Pembayaran' }}
                </span>
            </div>
        </div>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('penggajian.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('penggajian.slip', $penggajian) }}" class="btn-primary" target="_blank">Lihat Slip Gaji</a>
        @if($penggajian->status == 'pending')
            <form method="GET" action="{{ route('penggajian.paid', $penggajian) }}">
                <button type="submit" class="btn-primary">Tandai Sudah Dibayar</button>
            </form>
        @endif
    </div>
</div>
@endsection
