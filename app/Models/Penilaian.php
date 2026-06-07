<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'karyawan_id',
        'periode',
        'nilai_disiplin',
        'nilai_kualitas',
        'nilai_tanggung_jawab',
        'nilai_komunikasi',
        'nilai_inisiatif',
        'total_nilai',
        'catatan',
        'dinilai_by',
    ];

    protected $casts = [
        'periode' => 'date',
        'nilai_disiplin' => 'decimal:2',
        'nilai_kualitas' => 'decimal:2',
        'nilai_tanggung_jawab' => 'decimal:2',
        'nilai_komunikasi' => 'decimal:2',
        'nilai_inisiatif' => 'decimal:2',
        'total_nilai' => 'decimal:2',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function dinilaiBy()
    {
        return $this->belongsTo(User::class, 'dinilai_by');
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'dinilai_by');
    }

    public function getGradeAttribute(): string
    {
        if ($this->total_nilai >= 90) return 'A (Sangat Baik)';
        if ($this->total_nilai >= 80) return 'B (Baik)';
        if ($this->total_nilai >= 70) return 'C (Cukup)';
        if ($this->total_nilai >= 60) return 'D (Perlu Perbaikan)';
        return 'E (Tidak Memuaskan)';
    }
}
