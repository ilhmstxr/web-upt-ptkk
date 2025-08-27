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
        Schema::create('pivot__pertanyaan_tes', function (Blueprint $table) {
            $table->foreignId('id_pertanyaan')->constrained('pertanyaan')->onDelete('cascade');
            $table->foreignId('id_tes')->constrained('tes')->onDelete('cascade');
            // Menjadikan keduanya sebagai primary key
            $table->primary(['id_pertanyaan', 'id_tes']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot__pertanyaan_tes');
    }
};
