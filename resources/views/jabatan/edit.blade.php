@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Jabatan</h1>
    <p class="text-gray-600 mt-1">Perbarui data jabatan</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('jabatan.update', $jabatan) }}" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nama_jabatan" class="block text-sm font-medium text-gray-700">Nama Jabatan</label>
                    <input id="nama_jabatan" name="nama_jabatan" type="text" required class="input-field mt-1" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}">
                    @error('nama_jabatan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                    <input id="gaji_pokok" name="gaji_pokok" type="number" step="0.01" required class="input-field mt-1" value="{{ old('gaji_pokok', $jabatan->gaji_pokok) }}">
                    @error('gaji_pokok')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tunjangan_jabatan" class="block text-sm font-medium text-gray-700">Tunjangan Jabatan</label>
                    <input id="tunjangan_jabatan" name="tunjangan_jabatan" type="number" step="0.01" required class="input-field mt-1" value="{{ old('tunjangan_jabatan', $jabatan->tunjangan_jabatan) }}">
                    @error('tunjangan_jabatan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tunjangan_transport" class="block text-sm font-medium text-gray-700">Tunjangan Transport</label>
                    <input id="tunjangan_transport" name="tunjangan_transport" type="number" step="0.01" required class="input-field mt-1" value="{{ old('tunjangan_transport', $jabatan->tunjangan_transport) }}">
                    @error('tunjangan_transport')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tunjangan_makan" class="block text-sm font-medium text-gray-700">Tunjangan Makan</label>
                    <input id="tunjangan_makan" name="tunjangan_makan" type="number" step="0.01" required class="input-field mt-1" value="{{ old('tunjangan_makan', $jabatan->tunjangan_makan) }}">
                    @error('tunjangan_makan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('jabatan.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
