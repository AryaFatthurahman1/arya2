<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('penilaian.create');
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
            'karyawan_id.exists' => 'Karyawan tidak ditemukan.',
            'periode.date_format' => 'Periode harus dalam format YYYY-MM.',
            'nilai_disiplin.max' => 'Nilai disiplin maksimal 100.',
            'nilai_kualitas.max' => 'Nilai kualitas maksimal 100.',
            'nilai_tanggung_jawab.max' => 'Nilai tanggung jawab maksimal 100.',
            'nilai_komunikasi.max' => 'Nilai komunikasi maksimal 100.',
            'nilai_inisiatif.max' => 'Nilai inisiatif maksimal 100.',
        ];
    }
}
