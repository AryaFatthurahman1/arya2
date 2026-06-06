<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class AbsensiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $karyawan = Karyawan::where('nik', $row['nik'])->first();
        if (!$karyawan) {
            return null;
        }

        $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');
        $jamMasuk = !empty($row['jam_masuk']) ? Carbon::parse($row['jam_masuk'])->format('H:i:s') : null;
        $jamKeluar = !empty($row['jam_keluar']) ? Carbon::parse($row['jam_keluar'])->format('H:i:s') : null;

        $absensi = Absensi::where('karyawan_id', $karyawan->id)->whereDate('tanggal', $tanggal)->first();

        if ($absensi) {
            $absensi->update([
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => $jamKeluar,
                'status' => strtolower($row['status']),
                'keterangan' => $row['keterangan'] ?? null,
                'updated_by' => auth()->id(),
            ]);
            return null;
        }

        return new Absensi([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $tanggal,
            'jam_masuk' => $jamMasuk,
            'jam_keluar' => $jamKeluar,
            'status' => strtolower($row['status']),
            'keterangan' => $row['keterangan'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|exists:karyawan,nik',
            'tanggal' => 'required',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
            'status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alpha', 'Hadir', 'Izin', 'Sakit', 'Alpha'])],
            'keterangan' => 'nullable|string|max:500',
        ];
    }
}
