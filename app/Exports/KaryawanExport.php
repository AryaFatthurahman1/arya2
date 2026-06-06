<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Karyawan::with(['jabatan', 'satuanKerja'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'NIK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir',
            'Jenis Kelamin', 'Agama', 'Status Pernikahan', 'Alamat',
            'Telepon', 'Email', 'Jabatan', 'Satuan Kerja',
            'Tanggal Masuk', 'Status Karyawan',
        ];
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->nik,
            $karyawan->nama_lengkap,
            $karyawan->tempat_lahir,
            $karyawan->tanggal_lahir,
            $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            $karyawan->agama,
            $karyawan->status_pernikahan,
            $karyawan->alamat,
            $karyawan->telepon,
            $karyawan->email,
            $karyawan->jabatan?->nama_jabatan,
            $karyawan->satuanKerja?->nama_satuan_kerja,
            $karyawan->tanggal_masuk,
            $karyawan->status_karyawan,
        ];
    }
}
