@extends('layouts.app')

@section('title', 'Hitung Gaji')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Hitung Gaji</h1>
    <p class="text-gray-600 mt-1">Buat perhitungan gaji berdasarkan absensi dan komponen</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <form method="POST" action="{{ route('penggajian.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="karyawan_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                    <select id="karyawan_id" name="karyawan_id" required class="input-field mt-1" onchange="hitungPreview(this)">
                        <option value="">Pilih Karyawan</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}
                                data-gaji="{{ $k->jabatan->gaji_pokok }}"
                                data-tunjangan="{{ $k->jabatan->tunjangan_jabatan }}"
                                data-transport="{{ $k->jabatan->tunjangan_transport }}"
                                data-makan="{{ $k->jabatan->tunjangan_makan }}">
                                {{ $k->nama_lengkap }} ({{ $k->nik }})
                            </option>
                        @endforeach
                    </select>
                    @error('karyawan_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                    <input id="periode" name="periode" type="month" required class="input-field mt-1" value="{{ old('periode') }}">
                    @error('periode')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div id="preview-gaji" class="hidden bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Preview Komponen Gaji</h3>
                    <div class="text-sm text-gray-600">
                        <p>Gaji Pokok: <span id="prev-gaji">0</span></p>
                        <p>Tunjangan Jabatan: <span id="prev-tunjangan">0</span></p>
                        <p>Transport: <span id="prev-transport">0</span></p>
                        <p>Makan: <span id="prev-makan">0</span></p>
                    </div>
                </div>
                <div>
                    <label for="tunjangan_lainnya" class="block text-sm font-medium text-gray-700">Tunjangan Lainnya</label>
                    <input id="tunjangan_lainnya" name="tunjangan_lainnya" type="number" step="0.01" class="input-field mt-1" value="{{ old('tunjangan_lainnya', 0) }}">
                </div>
                <div>
                    <label for="potongan_lainnya" class="block text-sm font-medium text-gray-700">Potongan Lainnya</label>
                    <input id="potongan_lainnya" name="potongan_lainnya" type="number" step="0.01" class="input-field mt-1" value="{{ old('potongan_lainnya', 0) }}">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('penggajian.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Hitung & Simpan</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function formatRupiah(num) {
    return 'Rp ' + parseFloat(num).toLocaleString('id-ID');
}
function hitungPreview(select) {
    const preview = document.getElementById('preview-gaji');
    const opt = select.options[select.selectedIndex];
    if (!opt.value) { preview.classList.add('hidden'); return; }
    const gaji = parseFloat(opt.dataset.gaji) || 0;
    const tunjangan = parseFloat(opt.dataset.tunjangan) || 0;
    const transport = parseFloat(opt.dataset.transport) || 0;
    const makan = parseFloat(opt.dataset.makan) || 0;
    document.getElementById('prev-gaji').textContent = formatRupiah(gaji);
    document.getElementById('prev-tunjangan').textContent = formatRupiah(tunjangan);
    document.getElementById('prev-transport').textContent = formatRupiah(transport);
    document.getElementById('prev-makan').textContent = formatRupiah(makan);
    preview.classList.remove('hidden');
}
</script>
@endpush
@endsection
