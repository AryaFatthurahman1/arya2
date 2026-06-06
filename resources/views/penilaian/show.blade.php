@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Penilaian Kinerja</h1>
    <p class="text-gray-600 mt-1">Informasi lengkap penilaian | Periode: {{ \Carbon\Carbon::parse($penilaian->periode)->format('F Y') }}</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Karyawan: {{ $penilaian->karyawan?->nama_lengkap }} ({{ $penilaian->karyawan?->nik }})</h2>
            <p class="text-sm text-gray-500">Dinilai oleh: {{ $penilaian->dinilaiBy?->name }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr><td class="px-4 py-3 text-sm text-gray-700">Disiplin</td><td class="px-4 py-3 text-sm text-center font-semibold">{{ $penilaian->nilai_disiplin }}</td></tr>
                    <tr><td class="px-4 py-3 text-sm text-gray-700">Kualitas Kerja</td><td class="px-4 py-3 text-sm text-center font-semibold">{{ $penilaian->nilai_kualitas }}</td></tr>
                    <tr><td class="px-4 py-3 text-sm text-gray-700">Tanggung Jawab</td><td class="px-4 py-3 text-sm text-center font-semibold">{{ $penilaian->nilai_tanggung_jawab }}</td></tr>
                    <tr><td class="px-4 py-3 text-sm text-gray-700">Komunikasi</td><td class="px-4 py-3 text-sm text-center font-semibold">{{ $penilaian->nilai_komunikasi }}</td></tr>
                    <tr><td class="px-4 py-3 text-sm text-gray-700">Inisiatif</td><td class="px-4 py-3 text-sm text-center font-semibold">{{ $penilaian->nilai_inisiatif }}</td></tr>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-4 py-3 text-sm font-bold text-gray-900">Total Nilai</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 inline-flex text-sm font-bold rounded-full {{ $penilaian->total_nilai >= 80 ? 'bg-green-100 text-green-800' : ($penilaian->total_nilai >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $penilaian->total_nilai }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($penilaian->catatan)
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700">Catatan</h3>
            <p class="mt-1 text-sm text-gray-900">{{ $penilaian->catatan }}</p>
        </div>
        @endif
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('penilaian.index') }}" class="btn-secondary">Kembali</a>
        <a href="{{ route('penilaian.edit', $penilaian) }}" class="btn-primary">Edit</a>
    </div>
</div>
@endsection
