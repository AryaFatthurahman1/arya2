@extends('layouts.app')

@section('title', 'Edit Izin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Pengajuan Izin</h1>
    <p class="text-gray-600 mt-1">Ubah data pengajuan izin</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('leaves.update', $izin) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PATCH')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="karyawan_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                    <select id="karyawan_id" name="karyawan_id" required class="input-field mt-1">
                        <option value="">Pilih Karyawan</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id', $izin->karyawan_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }} ({{ $k->nik }})</option>
                        @endforeach
                    </select>
                    @error('karyawan_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="jenis_izin" class="block text-sm font-medium text-gray-700">Jenis Izin</label>
                    <select id="jenis_izin" name="jenis_izin" required class="input-field mt-1">
                        @foreach(['sakit'=>'Sakit','cuti'=>'Cuti','izin_khusus'=>'Izin Khusus'] as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis_izin', $izin->jenis_izin) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_izin')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input id="tanggal_mulai" name="tanggal_mulai" type="date" required class="input-field mt-1" value="{{ old('tanggal_mulai', $izin->tanggal_mulai) }}">
                        @error('tanggal_mulai')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input id="tanggal_selesai" name="tanggal_selesai" type="date" required class="input-field mt-1" value="{{ old('tanggal_selesai', $izin->tanggal_selesai) }}">
                        @error('tanggal_selesai')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="alasan" class="block text-sm font-medium text-gray-700">Alasan</label>
                    <textarea id="alasan" name="alasan" rows="3" required class="input-field mt-1">{{ old('alasan', $izin->alasan) }}</textarea>
                    @error('alasan')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="bukti_dokumen" class="block text-sm font-medium text-gray-700">Bukti Dokumen</label>
                    @if($izin->bukti_dokumen)
                        <p class="text-sm text-gray-500 mb-2">File saat ini: <a href="{{ asset('storage/' . $izin->bukti_dokumen) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">Lihat</a></p>
                    @endif
                    <input id="bukti_dokumen" name="bukti_dokumen" type="file" accept=".pdf,.jpg,.jpeg,.png" class="input-field mt-1">
                    @error('bukti_dokumen')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('leaves.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
