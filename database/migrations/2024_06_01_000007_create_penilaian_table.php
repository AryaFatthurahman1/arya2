<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->date('periode');
            $table->decimal('nilai_disiplin', 5, 2);
            $table->decimal('nilai_kualitas', 5, 2);
            $table->decimal('nilai_tanggung_jawab', 5, 2);
            $table->decimal('nilai_komunikasi', 5, 2);
            $table->decimal('nilai_inisiatif', 5, 2);
            $table->decimal('total_nilai', 5, 2);
            $table->text('catatan')->nullable();
            $table->foreignId('dinilai_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};