<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('tugas.create');
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'tanggal_tenggat' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:baru,diproses,selesai,terlambat',
            'bukti_penyelesaian' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul tugas wajib diisi.',
            'assigned_to.exists' => 'Penerima tugas tidak ditemukan.',
            'tanggal_tenggat.after_or_equal' => 'Tenggat waktu tidak boleh di masa lalu.',
            'status.in' => 'Status tugas tidak valid.',
        ];
    }
}
