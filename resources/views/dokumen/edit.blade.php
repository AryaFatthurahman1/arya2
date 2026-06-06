@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-200">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Dokumen</h1>
            <p class="text-gray-500 text-sm">Perbarui metadata dokumen</p>
        </div>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="p-8">
        <form method="POST" action="{{ route('dokumen.update', $dokumen) }}" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fa-solid fa-tag mr-2 text-indigo-500"></i>Nama Dokumen</label>
                <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen', $dokumen->nama_dokumen) }}" required class="input-field">
                @error('nama_dokumen')<p class="mt-1 text-sm text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fa-solid fa-layer-group mr-2 text-indigo-500"></i>Tipe Dokumen</label>
                <select name="tipe_dokumen" required class="input-field">
                    <option value="kontrak" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="sk" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'sk' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                    <option value="sertifikat" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                    <option value="personal" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'personal' ? 'selected' : '' }}>Dokumen Personal</option>
                    <option value="lainnya" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('tipe_dokumen')<p class="mt-1 text-sm text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fa-solid fa-user mr-2 text-indigo-500"></i>Karyawan</label>
                <select name="karyawan_id" class="input-field">
                    <option value="">Dokumen Perusahaan</option>
                    @foreach($karyawanList as $k)
                        <option value="{{ $k->id }}" {{ old('karyawan_id', $dokumen->karyawan_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
                @error('karyawan_id')<p class="mt-1 text-sm text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fa-solid fa-align-left mr-2 text-indigo-500"></i>Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="input-field" placeholder="Keterangan tambahan...">{{ old('deskripsi', $dokumen->deskripsi) }}</textarea>
                @error('deskripsi')<p class="mt-1 text-sm text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>@enderror
            </div>

            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                <p class="text-sm text-gray-500"><i class="fa-solid fa-info-circle mr-2 text-indigo-400"></i>File tidak dapat diubah. Hapus dan upload ulang jika perlu mengganti file.</p>
                <p class="text-sm text-gray-600 mt-1"><span class="font-semibold">File:</span> {{ basename($dokumen->file_path) }}</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('dokumen.index') }}" class="btn-secondary"><i class="fa-solid fa-arrow-left mr-2"></i>Kembali</a>
                <button type="submit" class="btn-primary"><i class="fa-solid fa-save mr-2"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
