<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tahun;
    protected $bulan;

    public function __construct($tahun = null, $bulan = null)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function collection()
    {
        $query = Absensi::with('karyawan');
        if ($this->tahun) {
            $query->whereYear('tanggal', $this->tahun);
        }
        if ($this->bulan) {
            $query->whereMonth('tanggal', $this->bulan);
        }
        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'NIK', 'Nama Karyawan', 'Tanggal', 'Jam Masuk',
            'Jam Keluar', 'Status', 'Keterangan',
        ];
    }

    public function map($absensi): array
    {
        return [
            $absensi->karyawan?->nik,
            $absensi->karyawan?->nama_lengkap,
            $absensi->tanggal,
            $absensi->jam_masuk,
            $absensi->jam_keluar,
            ucfirst($absensi->status),
            $absensi->keterangan,
        ];
    }
}
