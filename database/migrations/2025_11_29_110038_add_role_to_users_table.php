<?php
// database/migrations/xxxx_add_role_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['administrator', 'petugas', 'peminjam'])
                  ->default('peminjam')
                  ->after('password');
                $table->string('no_identitas')->nullable()->after('role');
                $table->text('alamat')->nullable()->after('no_identitas');
                $table->string('no_telepon')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'no_identitas', 'alamat', 'no_telepon']);
        });
    }
};