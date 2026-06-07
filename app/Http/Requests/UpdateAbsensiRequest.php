<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('absensi.edit');
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'foto_absen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.exists' => 'Karyawan tidak ditemukan.',
            'jam_keluar.after' => 'Jam keluar harus setelah jam masuk.',
            'status.in' => 'Status absensi tidak valid.',
        ];
    }
}
