<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nik', 50)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 50);
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai']);
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->string('email')->unique();
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->foreignId('satuan_kerja_id')->constrained('satuan_kerja');
            $table->date('tanggal_masuk');
            $table->enum('status_karyawan', ['tetap', 'kontrak', 'percobaan']);
            $table->string('foto_profil')->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};