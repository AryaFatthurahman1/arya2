<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIzinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('izin.create');
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:karyawan,id',
            'jenis_izin' => 'required|in:sakit,cuti,izin_khusus',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|min:10',
            'bukti_dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_izin.in' => 'Jenis izin harus sakit, cuti, atau izin khusus.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
            'alasan.min' => 'Alasan minimal 10 karakter.',
            'bukti_dokumen.max' => 'Ukuran bukti dokumen maksimal 5MB.',
        ];
    }
}
