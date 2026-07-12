<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disaster_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->integer('required_volunteers');
            $table->integer('joined_volunteers')->default(0);
            $table->date('incident_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_reports');
    }
};