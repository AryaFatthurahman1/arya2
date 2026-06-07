<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('karyawan.create');
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|string|max:50|unique:karyawan,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'status_pernikahan' => 'required|in:belum_menikah,menikah,cerai',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:karyawan,email',
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
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah digunakan.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
            'status_pernikahan.in' => 'Status pernikahan tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jabatan_id.exists' => 'Jabatan tidak valid.',
            'satuan_kerja_id.exists' => 'Satuan kerja tidak valid.',
            'status_karyawan.in' => 'Status karyawan harus tetap, kontrak, atau percobaan.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
