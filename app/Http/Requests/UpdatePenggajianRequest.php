<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenggajianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('penggajian.edit');
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|date_format:Y-m',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'potongan_absen' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,dibayar',
        ];
    }

    public function messages(): array
    {
        return [
            'periode.date_format' => 'Periode harus dalam format YYYY-MM.',
        ];
    }
}
