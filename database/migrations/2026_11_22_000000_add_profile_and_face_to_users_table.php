<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim', 30)->nullable()->unique()->after('name');
            $table->string('prodi', 100)->nullable()->after('nim');
            $table->string('role', 20)->default('pengguna')->after('email');
            $table->longText('face_descriptor')->nullable()->after('password');
            $table->timestamp('face_registered_at')->nullable()->after('face_descriptor');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nim']);
            $table->dropColumn(['nim', 'prodi', 'role', 'face_descriptor', 'face_registered_at']);
        });
    }
};
