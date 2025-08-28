<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tes_pertanyaan')) {
            Schema::create('tes_pertanyaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tes_id')->constrained('tes')->onDelete('cascade');
                $table->foreignId('pertanyaan_id')->constrained('pertanyaans')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tes_pertanyaan')) {
            Schema::dropIfExists('tes_pertanyaan');
        }
    }
};
