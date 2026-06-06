<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen', 255);
            $table->enum('jenis', ['penambahan', 'potongan']);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->timestamps();
            $table->index('nama_komponen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_gaji');
    }
};