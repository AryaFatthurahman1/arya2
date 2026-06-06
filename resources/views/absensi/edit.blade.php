@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Absensi</h1>
    <p class="text-gray-600 mt-1">Koreksi data absensi karyawan</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('absensi.update', $absensi) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Karyawan</label>
                    <input type="text" value="{{ $absensi->karyawan?->nama_lengkap }}" disabled class="input-field mt-1 bg-gray-100">
                </div>
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input id="tanggal" name="tanggal" type="date" required class="input-field mt-1" value="{{ old('tanggal', $absensi->tanggal) }}">
                    @error('tanggal')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                    <input id="jam_masuk" name="jam_masuk" type="time" class="input-field mt-1" value="{{ old('jam_masuk', $absensi->jam_masuk) }}">
                    @error('jam_masuk')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="jam_keluar" class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                    <input id="jam_keluar" name="jam_keluar" type="time" class="input-field mt-1" value="{{ old('jam_keluar', $absensi->jam_keluar) }}">
                    @error('jam_keluar')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required class="input-field mt-1">
                        @foreach(['hadir'=>'Hadir','izin'=>'Izin','sakit'=>'Sakit','alpha'=>'Alpha'] as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $absensi->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="foto_absen" class="block text-sm font-medium text-gray-700">Bukti Absensi</label>
                    <input id="foto_absen" name="foto_absen" type="file" accept="image/*" class="input-field mt-1">
                    @if($absensi->foto_absen)
                        <p class="mt-1 text-xs text-gray-500">File saat ini ada, upload baru untuk mengganti</p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="input-field mt-1">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('absensi.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
