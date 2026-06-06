<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanKerja extends Model
{
    use HasFactory;

    protected $table = 'satuan_kerja';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'nama_satuan_kerja',
        'kepala_satuan_kerja_id',
        'lokasi',
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function kepalaSatuanKerja()
    {
        return $this->belongsTo(Karyawan::class, 'kepala_satuan_kerja_id');
    }
}