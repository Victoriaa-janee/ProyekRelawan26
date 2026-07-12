<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disaster_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disaster_report_id')->constrained()->onDelete('cascade');
            $table->foreignId('action_tag_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_tag');
    }
};