<?php

namespace App\Imports;

use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KaryawanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Karyawan([
            'nik' => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => Carbon::parse($row['tanggal_lahir']),
            'jenis_kelamin' => $row['jenis_kelamin'],
            'agama' => $row['agama'],
            'status_pernikahan' => $row['status_pernikahan'],
            'alamat' => $row['alamat'],
            'telepon' => $row['telepon'],
            'email' => $row['email'],
            'jabatan_id' => $row['jabatan_id'],
            'satuan_kerja_id' => $row['satuan_kerja_id'],
            'tanggal_masuk' => Carbon::parse($row['tanggal_masuk']),
            'status_karyawan' => $row['status_karyawan'],
            'nama_bank' => $row['nama_bank'] ?? null,
            'nomor_rekening' => $row['nomor_rekening'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|string|max:20|unique:karyawan,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'status_pernikahan' => 'required|in:belum_menikah,menikah,cerai',
            'alamat' => 'required|string|max:1000',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:karyawan,email',
            'jabatan_id' => 'required|exists:jabatan,id',
            'satuan_kerja_id' => 'required|exists:satuan_kerja,id',
            'tanggal_masuk' => 'required|date',
            'status_karyawan' => 'required|in:tetap,kontrak,percobaan',
        ];
    }
}
