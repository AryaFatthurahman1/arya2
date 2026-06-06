<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_pernikahan',
        'alamat',
        'telepon',
        'email',
        'jabatan_id',
        'satuan_kerja_id',
        'tanggal_masuk',
        'status_karyawan',
        'foto_profil',
        'nomor_rekening',
        'nama_bank',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    protected function alamat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $this->safeDecrypt($value) : $value,
            set: fn ($value) => $value ? Crypt::encryptString($value) : $value,
        );
    }

    protected function telepon(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $this->safeDecrypt($value) : $value,
            set: fn ($value) => $value ? Crypt::encryptString($value) : $value,
        );
    }

    protected function nomorRekening(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $this->safeDecrypt($value) : $value,
            set: fn ($value) => $value ? Crypt::encryptString($value) : $value,
        );
    }

    private function safeDecrypt(string $value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function izin()
    {
        return $this->hasMany(PengajuanIzin::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'assigned_to', 'user_id');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function penggajian()
    {
        return $this->hasMany(Penggajian::class);
    }
}