<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('percobaans')) {
            Schema::create('percobaans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
                $table->foreignId('tes_id')->constrained('tes')->cascadeOnDelete();
                $table->timestamp('waktu_mulai');
                $table->timestamp('waktu_selesai')->nullable();
                $table->decimal('skor', 5, 2)->nullable();
                $table->text('pesan_kesan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('percobaans')) {
            Schema::dropIfExists('percobaans');
        }
    }
};
