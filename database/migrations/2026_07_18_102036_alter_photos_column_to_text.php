<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bencana', function (Blueprint $table) {
            $table->text('foto_awal')->change();
        });
        Schema::table('dokumentasi_relawan', function (Blueprint $table) {
            $table->text('foto_dokumentasi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bencana', function (Blueprint $table) {
            $table->string('foto_awal')->change();
        });
        Schema::table('dokumentasi_relawan', function (Blueprint $table) {
            $table->string('foto_dokumentasi')->nullable(false)->change();
        });
    }
};