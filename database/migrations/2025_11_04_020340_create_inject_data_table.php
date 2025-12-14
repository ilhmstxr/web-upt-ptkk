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
            // $table->id();
            $table->unsignedBigInteger('unique_key')->primary();
            // monev
            $table->string('email')->nullable();
            $table->string('nama')->nullable();
            $table->string('instansi')->nullable();
            // $table->string('angkatan')->nullable();
            $table->string('pelatihan_id')->nullable();
            $table->string('tes_id')->nullable();
            $table->string('kompetensi_id')->nullable();
            // test
            $table->string('nilai_pre_test')->nullable();
            $table->string('nilai_post_test')->nullable();
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
