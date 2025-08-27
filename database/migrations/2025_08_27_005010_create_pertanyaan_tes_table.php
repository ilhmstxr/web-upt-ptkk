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
        Schema::create('pertanyaan_tes', function (Blueprint $table) {
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->onDelete('cascade');
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            // Menjadikan keduanya sebagai primary key
            $table->primary(['pertanyaan_id', 'kuis_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_tes');
    }
};
