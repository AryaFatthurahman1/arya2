<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('karyawan.edit');
    }

    public function rules(): array
    {
        $karyawanId = $this->route('karyawan');

        return [
            'nik' => [
                'required', 'string', 'max:50',
                Rule::unique('karyawan', 'nik')->ignore($karyawanId),
            ],
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'status_pernikahan' => 'required|in:belum_menikah,menikah,cerai',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('karyawan', 'email')->ignore($karyawanId),
            ],
            'jabatan_id' => 'required|exists:jabatan,id',
            'satuan_kerja_id' => 'required|exists:satuan_kerja,id',
            'tanggal_masuk' => 'required|date',
            'status_karyawan' => 'required|in:tetap,kontrak,percobaan',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_bank' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.unique' => 'NIK sudah digunakan karyawan lain.',
            'email.unique' => 'Email sudah digunakan karyawan lain.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jabatan_id.exists' => 'Jabatan tidak valid.',
            'satuan_kerja_id.exists' => 'Satuan kerja tidak valid.',
        ];
    }
}
