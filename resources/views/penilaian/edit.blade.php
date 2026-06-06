@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Penilaian Kinerja</h1>
    <p class="text-gray-600 mt-1">Perbarui nilai penilaian kinerja</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('penilaian.update', $penilaian) }}" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Karyawan</label>
                    <input type="text" value="{{ $penilaian->karyawan?->nama_lengkap }}" disabled class="input-field mt-1 bg-gray-100">
                </div>
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                    <input id="periode" name="periode" type="month" required class="input-field mt-1" value="{{ old('periode', \Carbon\Carbon::parse($penilaian->periode)->format('Y-m')) }}">
                    @error('periode')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Kriteria Penilaian (0-100)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nilai_disiplin" class="block text-sm font-medium text-gray-700">Disiplin</label>
                        <input id="nilai_disiplin" name="nilai_disiplin" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_disiplin', $penilaian->nilai_disiplin) }}">
                    </div>
                    <div>
                        <label for="nilai_kualitas" class="block text-sm font-medium text-gray-700">Kualitas Kerja</label>
                        <input id="nilai_kualitas" name="nilai_kualitas" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_kualitas', $penilaian->nilai_kualitas) }}">
                    </div>
                    <div>
                        <label for="nilai_tanggung_jawab" class="block text-sm font-medium text-gray-700">Tanggung Jawab</label>
                        <input id="nilai_tanggung_jawab" name="nilai_tanggung_jawab" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_tanggung_jawab', $penilaian->nilai_tanggung_jawab) }}">
                    </div>
                    <div>
                        <label for="nilai_komunikasi" class="block text-sm font-medium text-gray-700">Komunikasi</label>
                        <input id="nilai_komunikasi" name="nilai_komunikasi" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_komunikasi', $penilaian->nilai_komunikasi) }}">
                    </div>
                    <div>
                        <label for="nilai_inisiatif" class="block text-sm font-medium text-gray-700">Inisiatif</label>
                        <input id="nilai_inisiatif" name="nilai_inisiatif" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_inisiatif', $penilaian->nilai_inisiatif) }}">
                    </div>
                </div>
            </div>
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" class="input-field mt-1">{{ old('catatan', $penilaian->catatan) }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('penilaian.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
