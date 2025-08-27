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
            $table->foreignId('pertanyaan_id')->constrained()->onDelete('cascade');
            $table->foreignId('tes_id')->constrained()->onDelete('cascade');
            // Menjadikan keduanya sebagai primary key
            $table->primary(['pertanyaan_id', 'tes_id']);
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
