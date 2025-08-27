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
        Schema::create('tes__jawaban_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percobaan_tes_id')->constrained('tes__percobaans')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('tes__pertanyaans')->onDelete('cascade');
            $table->foreignId('opsi_jawaban_id')->constrained('tes__opsi_jawabans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes__jawaban_users');
    }
};
