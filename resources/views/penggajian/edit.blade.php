@extends('layouts.app')

@section('title', 'Edit Penggajian')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Penggajian</h1>
    <p class="text-gray-600 mt-1">Perbarui data penggajian</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('penggajian.update', $penggajian) }}" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Karyawan</label>
                    <input type="text" value="{{ $penggajian->karyawan?->nama_lengkap }}" disabled class="input-field mt-1 bg-gray-100">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                        <input id="gaji_pokok" name="gaji_pokok" type="number" step="0.01" required class="input-field mt-1" value="{{ old('gaji_pokok', $penggajian->gaji_pokok) }}">
                    </div>
                    <div>
                        <label for="tunjangan_jabatan" class="block text-sm font-medium text-gray-700">Tunjangan Jabatan</label>
                        <input id="tunjangan_jabatan" name="tunjangan_jabatan" type="number" step="0.01" required class="input-field mt-1" value="{{ old('tunjangan_jabatan', $penggajian->tunjangan_jabatan) }}">
                    </div>
                    <div>
                        <label for="tunjangan_transport" class="block text-sm font-medium text-gray-700">Tunjangan Transport</label>
                        <input id="tunjangan_transport" name="tunjangan_transport" type="number" step="0.01" class="input-field mt-1" value="{{ old('tunjangan_transport', $penggajian->tunjangan_transport) }}">
                    </div>
                    <div>
                        <label for="tunjangan_makan" class="block text-sm font-medium text-gray-700">Tunjangan Makan</label>
                        <input id="tunjangan_makan" name="tunjangan_makan" type="number" step="0.01" class="input-field mt-1" value="{{ old('tunjangan_makan', $penggajian->tunjangan_makan) }}">
                    </div>
                    <div>
                        <label for="tunjangan_lainnya" class="block text-sm font-medium text-gray-700">Tunjangan Lainnya</label>
                        <input id="tunjangan_lainnya" name="tunjangan_lainnya" type="number" step="0.01" class="input-field mt-1" value="{{ old('tunjangan_lainnya', $penggajian->tunjangan_lainnya) }}">
                    </div>
                    <div>
                        <label for="potongan_absen" class="block text-sm font-medium text-gray-700">Potongan Absen</label>
                        <input id="potongan_absen" name="potongan_absen" type="number" step="0.01" class="input-field mt-1" value="{{ old('potongan_absen', $penggajian->potongan_absen) }}">
                    </div>
                    <div>
                        <label for="potongan_lainnya" class="block text-sm font-medium text-gray-700">Potongan Lainnya</label>
                        <input id="potongan_lainnya" name="potongan_lainnya" type="number" step="0.01" class="input-field mt-1" value="{{ old('potongan_lainnya', $penggajian->potongan_lainnya) }}">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="input-field mt-1">
                            <option value="pending" {{ $penggajian->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dibayar" {{ $penggajian->status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('penggajian.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
