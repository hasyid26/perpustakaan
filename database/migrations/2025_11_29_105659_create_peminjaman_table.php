<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian_rencana');
            $table->date('tanggal_pengembalian_aktual')->nullable();
            $table->enum('status', ['pending', 'dipinjam', 'ditolak', 'dikembalikan'])->default('pending');
            $table->foreignId('petugas_pinjam_id')->nullable()->constrained('users');
            $table->foreignId('petugas_kembali_id')->nullable()->constrained('users');
            $table->integer('denda')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
