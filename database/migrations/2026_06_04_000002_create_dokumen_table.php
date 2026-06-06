<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawan')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->enum('tipe_dokumen', ['kontrak', 'sk', 'sertifikat', 'personal', 'lainnya'])->default('lainnya');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('file_type');
            $table->text('deskripsi')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
