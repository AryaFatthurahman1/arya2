<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 50)->unique()->nullable()->after('id');
            $table->string('phone')->nullable()->after('name');
            $table->text('address')->nullable()->after('phone');
            $table->unsignedBigInteger('position_id')->nullable()->after('address');
            $table->unsignedBigInteger('department_id')->nullable()->after('position_id');
            $table->string('photo')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'phone', 'address', 'position_id', 'department_id', 'photo']);
        });
    }
};
