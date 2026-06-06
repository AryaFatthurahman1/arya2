@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Upload Dokumen</h1>
    <p class="text-gray-600 mt-1">Upload dokumen baru</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('dokumen.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama_dokumen" class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
                    <input type="text" id="nama_dokumen" name="nama_dokumen" class="input-field mt-1" value="{{ old('nama_dokumen') }}" required>
                    @error('nama_dokumen')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tipe_dokumen" class="block text-sm font-medium text-gray-700">Tipe Dokumen</label>
                    <select id="tipe_dokumen" name="tipe_dokumen" class="input-field mt-1" required>
                        <option value="">Pilih Tipe</option>
                        <option value="kontrak" {{ old('tipe_dokumen') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="sk" {{ old('tipe_dokumen') == 'sk' ? 'selected' : '' }}>SK</option>
                        <option value="sertifikat" {{ old('tipe_dokumen') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                        <option value="personal" {{ old('tipe_dokumen') == 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="lainnya" {{ old('tipe_dokumen') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('tipe_dokumen')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="karyawan_id" class="block text-sm font-medium text-gray-700">Karyawan (opsional)</label>
                    <select id="karyawan_id" name="karyawan_id" class="input-field mt-1">
                        <option value="">Umum</option>
                        @foreach ($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    @error('karyawan_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                    <input type="file" id="file" name="file" class="input-field mt-1 py-2" required>
                    @error('file')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi (opsional)</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="input-field mt-1">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('dokumen.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection