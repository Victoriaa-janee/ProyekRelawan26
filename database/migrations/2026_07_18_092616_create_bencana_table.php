<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_bencana')->onDelete('cascade');
            $table->string('judul_laporan');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->date('tanggal_kejadian');
            $table->time('jam_kejadian');
            $table->string('foto_awal');
            $table->enum('status', ['pending', 'ditangani', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bencana');
    }
};