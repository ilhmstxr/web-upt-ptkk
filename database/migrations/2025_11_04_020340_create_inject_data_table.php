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
        Schema::create('inject_data', function (Blueprint $table) {
            $table->id();
            // monev
            $table->string('email')->nullable();
            $table->string('nama')->nullable();
            $table->string('instansi')->nullable();
            $table->string('angkatan')->nullable();
            $table->string('kompetensi')->nullable();
            // test
            $table->Integer('skor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inject_data');
    }
};
