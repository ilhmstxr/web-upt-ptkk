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
        Schema::create('jawaban_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opsi_jawaban_id')->nullable()->constrained('opsi_jawabans')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->onDelete('cascade');
            $table->foreignId('percobaan_id')->constrained('percobaans')->onDelete('cascade');
            $table->tinyInteger('nilai_jawaban')->nullable(); // Untuk skala likert (1-5)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_users');
    }
};
