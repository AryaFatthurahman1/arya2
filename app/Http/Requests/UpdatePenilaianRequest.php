<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('penilaian.edit');
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|date_format:Y-m',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kualitas' => 'required|numeric|min:0|max:100',
            'nilai_tanggung_jawab' => 'required|numeric|min:0|max:100',
            'nilai_komunikasi' => 'required|numeric|min:0|max:100',
            'nilai_inisiatif' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'periode.date_format' => 'Periode harus dalam format YYYY-MM.',
        ];
    }
}
