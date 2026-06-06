<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas_karyawan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'assigned_by',
        'assigned_to',
        'tanggal_tenggat',
        'status',
        'bukti_penyelesaian',
        'catatan',
        'selesai_at',
    ];

    protected $casts = [
        'tanggal_tenggat' => 'date',
        'selesai_at' => 'datetime',
    ];

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
