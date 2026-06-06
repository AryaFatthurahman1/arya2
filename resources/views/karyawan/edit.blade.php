@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Karyawan</h1>
    <p class="text-gray-600 mt-1">Perbarui data karyawan</p>
</div>

<div class="card max-w-4xl">
    <div class="p-6">
        <form method="POST" action="{{ route('karyawan.update', $karyawan) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                    <input id="nik" name="nik" type="text" required class="input-field mt-1" value="{{ old('nik', $karyawan->nik) }}">
                    @error('nik')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" required class="input-field mt-1" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}">
                    @error('nama_lengkap')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input id="tempat_lahir" name="tempat_lahir" type="text" required class="input-field mt-1" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}">
                    @error('tempat_lahir')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input id="tanggal_lahir" name="tanggal_lahir" type="date" required class="input-field mt-1" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}">
                    @error('tanggal_lahir')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="input-field mt-1">
                        <option value="" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == '' ? 'selected' : '' }}>Pilih</option>
                        <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="agama" class="block text-sm font-medium text-gray-700">Agama</label>
                    <input id="agama" name="agama" type="text" required class="input-field mt-1" value="{{ old('agama', $karyawan->agama) }}">
                    @error('agama')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status_pernikahan" class="block text-sm font-medium text-gray-700">Status Pernikahan</label>
                    <select id="status_pernikahan" name="status_pernikahan" required class="input-field mt-1">
                        @foreach(['belum_menikah'=>'Belum Menikah','menikah'=>'Menikah','cerai'=>'Cerai'] as $key => $label)
                            <option value="{{ $key }}" {{ old('status_pernikahan', $karyawan->status_pernikahan) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status_pernikahan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input id="telepon" name="telepon" type="text" required class="input-field mt-1" value="{{ old('telepon', $karyawan->telepon) }}">
                    @error('telepon')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" required class="input-field mt-1">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    @error('alamat')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required class="input-field mt-1" value="{{ old('email', $karyawan->email) }}">
                    @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="jabatan_id" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select id="jabatan_id" name="jabatan_id" required class="input-field mt-1">
                        @foreach(\App\Models\Jabatan::all() as $jabatanItem)
                            <option value="{{ $jabatanItem->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $jabatanItem->id ? 'selected' : '' }}>{{ $jabatanItem->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="satuan_kerja_id" class="block text-sm font-medium text-gray-700">Satuan Kerja</label>
                    <select id="satuan_kerja_id" name="satuan_kerja_id" required class="input-field mt-1">
                        @foreach(\App\Models\SatuanKerja::all() as $satuan)
                            <option value="{{ $satuan->id }}" {{ old('satuan_kerja_id', $karyawan->satuan_kerja_id) == $satuan->id ? 'selected' : '' }}>{{ $satuan->nama_satuan_kerja }}</option>
                        @endforeach
                    </select>
                    @error('satuan_kerja_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                    <input id="tanggal_masuk" name="tanggal_masuk" type="date" required class="input-field mt-1" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk) }}">
                    @error('tanggal_masuk')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status_karyawan" class="block text-sm font-medium text-gray-700">Status Karyawan</label>
                    <select id="status_karyawan" name="status_karyawan" required class="input-field mt-1">
                        @foreach(['tetap'=>'Tetap','kontrak'=>'Kontrak','percobaan'=>'Percobaan'] as $key => $label)
                            <option value="{{ $key }}" {{ old('status_karyawan', $karyawan->status_karyawan) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status_karyawan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="nama_bank" class="block text-sm font-medium text-gray-700">Nama Bank</label>
                    <input id="nama_bank" name="nama_bank" type="text" class="input-field mt-1" value="{{ old('nama_bank', $karyawan->nama_bank) }}">
                </div>
                <div>
                    <label for="nomor_rekening" class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                    <input id="nomor_rekening" name="nomor_rekening" type="text" class="input-field mt-1" value="{{ old('nomor_rekening', $karyawan->nomor_rekening) }}">
                </div>
                <div>
                    <label for="foto_profil" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                    <input id="foto_profil" name="foto_profil" type="file" accept="image/*" class="input-field mt-1">
                    @if($karyawan->foto_profil)
                        <p class="mt-1 text-xs text-gray-500">File saat ini: {{ basename($karyawan->foto_profil) }}</p>
                    @endif
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('karyawan.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
