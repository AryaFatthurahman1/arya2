<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('satuan_kerja', function (Blueprint $table) {
            $table->foreign('kepala_satuan_kerja_id')->references('id')->on('karyawan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('satuan_kerja', function (Blueprint $table) {
            $table->dropForeign(['kepala_satuan_kerja_id']);
        });
    }
};
