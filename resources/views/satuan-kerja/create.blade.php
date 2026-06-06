@extends('layouts.app')

@section('title', 'Tambah Satuan Kerja')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Satuan Kerja</h1>
    <p class="text-gray-600 mt-1">Isi formulir berikut untuk menambah satuan kerja</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('satuan-kerja.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nama_satuan_kerja" class="block text-sm font-medium text-gray-700">Nama Satuan Kerja</label>
                    <input id="nama_satuan_kerja" name="nama_satuan_kerja" type="text" required class="input-field mt-1" value="{{ old('nama_satuan_kerja') }}">
                    @error('nama_satuan_kerja')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="kepala_satuan_kerja_id" class="block text-sm font-medium text-gray-700">Kepala Satuan Kerja</label>
                    <select id="kepala_satuan_kerja_id" name="kepala_satuan_kerja_id" class="input-field mt-1">
                        <option value="">- Tidak Ada -</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('kepala_satuan_kerja_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }} ({{ $k->nik }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input id="lokasi" name="lokasi" type="text" class="input-field mt-1" value="{{ old('lokasi') }}">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('satuan-kerja.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
