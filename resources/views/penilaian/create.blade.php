@extends('layouts.app')

@section('title', 'Tambah Penilaian')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Penilaian Kinerja</h1>
    <p class="text-gray-600 mt-1">Beri penilaian kinerja untuk karyawan</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('penilaian.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="karyawan_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                    <select id="karyawan_id" name="karyawan_id" required class="input-field mt-1">
                        <option value="">Pilih Karyawan</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    @error('karyawan_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                    <input id="periode" name="periode" type="month" required class="input-field mt-1" value="{{ old('periode') }}">
                    @error('periode')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Kriteria Penilaian (0-100)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nilai_disiplin" class="block text-sm font-medium text-gray-700">Disiplin</label>
                        <input id="nilai_disiplin" name="nilai_disiplin" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_disiplin') }}">
                    </div>
                    <div>
                        <label for="nilai_kualitas" class="block text-sm font-medium text-gray-700">Kualitas Kerja</label>
                        <input id="nilai_kualitas" name="nilai_kualitas" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_kualitas') }}">
                    </div>
                    <div>
                        <label for="nilai_tanggung_jawab" class="block text-sm font-medium text-gray-700">Tanggung Jawab</label>
                        <input id="nilai_tanggung_jawab" name="nilai_tanggung_jawab" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_tanggung_jawab') }}">
                    </div>
                    <div>
                        <label for="nilai_komunikasi" class="block text-sm font-medium text-gray-700">Komunikasi</label>
                        <input id="nilai_komunikasi" name="nilai_komunikasi" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_komunikasi') }}">
                    </div>
                    <div>
                        <label for="nilai_inisiatif" class="block text-sm font-medium text-gray-700">Inisiatif</label>
                        <input id="nilai_inisiatif" name="nilai_inisiatif" type="number" min="0" max="100" required class="input-field mt-1" value="{{ old('nilai_inisiatif') }}">
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-500">Total Nilai (rata-rata): <span id="preview-nilai">-</span></p>
            </div>
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" class="input-field mt-1">{{ old('catatan') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('penilaian.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.querySelectorAll('input[name^="nilai_"]').forEach(input => {
    input.addEventListener('input', function() {
        const nilai_disiplin = parseFloat(document.querySelector('[name="nilai_disiplin"]')?.value) || 0;
        const nilai_kualitas = parseFloat(document.querySelector('[name="nilai_kualitas"]')?.value) || 0;
        const nilai_tanggung_jawab = parseFloat(document.querySelector('[name="nilai_tanggung_jawab"]')?.value) || 0;
        const nilai_komunikasi = parseFloat(document.querySelector('[name="nilai_komunikasi"]')?.value) || 0;
        const nilai_inisiatif = parseFloat(document.querySelector('[name="nilai_inisiatif"]')?.value) || 0;
        const total = ((nilai_disiplin + nilai_kualitas + nilai_tanggung_jawab + nilai_komunikasi + nilai_inisiatif) / 5).toFixed(2);
        document.getElementById('preview-nilai').textContent = isNaN(total) ? '-' : total;
    });
});
</script>
@endpush
@endsection
