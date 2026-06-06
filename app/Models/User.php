<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'phone',
        'address',
        'position_id',
        'department_id',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Crypt::decryptString($value) : $value,
            set: fn ($value) => $value ? Crypt::encryptString($value) : $value,
        );
    }

    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Crypt::decryptString($value) : $value,
            set: fn ($value) => $value ? Crypt::encryptString($value) : $value,
        );
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id', 'user_id');
    }

    public function izin()
    {
        return $this->hasMany(PengajuanIzin::class, 'karyawan_id', 'user_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'assigned_to');
    }

    public function tugasDibuat()
    {
        return $this->hasMany(Tugas::class, 'assigned_by');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'dinilai_by');
    }
}