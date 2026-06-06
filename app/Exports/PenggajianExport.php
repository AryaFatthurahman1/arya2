<?php

namespace App\Exports;

use App\Models\Penggajian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenggajianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $periode;

    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = Penggajian::with('karyawan.jabatan', 'karyawan.satuanKerja');
        if ($this->periode) {
            $query->where('periode', $this->periode);
        }
        return $query->orderBy('periode', 'desc')->orderBy('karyawan_id')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Satuan Kerja',
            'Periode',
            'Gaji Pokok',
            'Tunj. Jabatan',
            'Tunj. Transport',
            'Tunj. Makan',
            'Tunj. Lainnya',
            'Potongan Absen',
            'Potongan Lainnya',
            'Total Pendapatan',
            'Total Potongan',
            'GAJI BERSIH',
            'Status',
        ];
    }

    public function map($penggajian): array
    {
        static $no = 0;
        $no++;
        $totalPendapatan = $penggajian->gaji_pokok + $penggajian->tunjangan_jabatan
            + $penggajian->tunjangan_transport + $penggajian->tunjangan_makan
            + $penggajian->tunjangan_lainnya;
        $totalPotongan = $penggajian->potongan_absen + $penggajian->potongan_lainnya;
        return [
            $no,
            $penggajian->karyawan?->nik,
            $penggajian->karyawan?->nama_lengkap,
            $penggajian->karyawan?->jabatan?->nama_jabatan,
            $penggajian->karyawan?->satuanKerja?->nama_satuan_kerja,
            $penggajian->periode ? \Carbon\Carbon::parse($penggajian->periode)->format('F Y') : '-',
            $penggajian->gaji_pokok,
            $penggajian->tunjangan_jabatan,
            $penggajian->tunjangan_transport,
            $penggajian->tunjangan_makan,
            $penggajian->tunjangan_lainnya,
            $penggajian->potongan_absen,
            $penggajian->potongan_lainnya,
            $totalPendapatan,
            $totalPotongan,
            $penggajian->total_gaji,
            ucfirst($penggajian->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }

    public function title(): string
    {
        return 'Penggajian' . ($this->periode ? '-' . $this->periode : '');
    }
}
