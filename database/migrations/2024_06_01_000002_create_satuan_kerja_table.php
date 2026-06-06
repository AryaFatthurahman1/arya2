<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan_kerja');
            $table->unsignedBigInteger('kepala_satuan_kerja_id')->nullable();
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan_kerja');
    }
};
