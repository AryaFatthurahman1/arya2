<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $penggajian->karyawan?->nama_lengkap }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * { visibility: hidden; }
            #slip-gaji, #slip-gaji * { visibility: visible; }
            #slip-gaji { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="max-w-2xl mx-auto my-10">
    <div id="slip-gaji" class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-blue-700 text-white p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">SLIP GAJI</h1>
                    <p class="text-blue-100 mt-1">{{ \Carbon\Carbon::parse($penggajian->periode)->format('F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">No:</p>
                    <p class="font-semibold">{{ str_pad($penggajian->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nama Karyawan</p>
                    <p class="font-semibold text-gray-900">{{ $penggajian->karyawan?->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">NIK</p>
                    <p class="font-semibold text-gray-900">{{ $penggajian->karyawan?->nik }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Jabatan</p>
                    <p class="font-semibold text-gray-900">{{ $penggajian->karyawan?->jabatan?->nama_jabatan }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Satuan Kerja</p>
                    <p class="font-semibold text-gray-900">{{ $penggajian->karyawan?->satuanKerja?->nama_satuan_kerja }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase mb-3">Pendapatan</h3>
                <table class="w-full">
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Gaji Pokok</td>
                            <td class="py-2 text-sm text-right font-medium text-gray-900">Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Tunjangan Jabatan</td>
                            <td class="py-2 text-sm text-right font-medium text-gray-900">Rp {{ number_format($penggajian->tunjangan_jabatan, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Tunjangan Transport</td>
                            <td class="py-2 text-sm text-right font-medium text-gray-900">Rp {{ number_format($penggajian->tunjangan_transport, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Tunjangan Makan</td>
                            <td class="py-2 text-sm text-right font-medium text-gray-900">Rp {{ number_format($penggajian->tunjangan_makan, 0, ',', '.') }}</td>
                        </tr>
                        @if($penggajian->tunjangan_lainnya > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Tunjangan Lainnya</td>
                            <td class="py-2 text-sm text-right font-medium text-gray-900">Rp {{ number_format($penggajian->tunjangan_lainnya, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-right mt-2 pt-2 border-t-2 border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Total Pendapatan: </span>
                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($penggajian->gaji_pokok + $penggajian->tunjangan_jabatan + $penggajian->tunjangan_transport + $penggajian->tunjangan_makan + $penggajian->tunjangan_lainnya, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase mb-3">Potongan</h3>
                <table class="w-full">
                    <tbody>
                        @if($penggajian->potongan_absen > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Potongan Absensi</td>
                            <td class="py-2 text-sm text-right font-medium text-red-600">- Rp {{ number_format($penggajian->potongan_absen, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($penggajian->potongan_lainnya > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-sm text-gray-600">Potongan Lainnya</td>
                            <td class="py-2 text-sm text-right font-medium text-red-600">- Rp {{ number_format($penggajian->potongan_lainnya, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-right mt-2 pt-2 border-t-2 border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Total Potongan: </span>
                    <span class="text-lg font-bold text-red-600">- Rp {{ number_format($penggajian->potongan_absen + $penggajian->potongan_lainnya, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">GAJI BERSIH</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-blue-700">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
            <p class="text-xs text-gray-500">Dicetak: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
            <button onclick="window.print()" class="btn-primary text-sm"><i class="fa-solid fa-print me-2"></i>Cetak</button>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('penggajian.index') }}" class="btn-secondary">Kembali ke Penggajian</a>
    </div>
</div>

</body>
