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
        Schema::create('percobaan', function (Blueprint $table) {
            $table->id();
            // IMPROVE: percobaan terkena RESTRICT
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete()->nullable();
            $table->foreignId('pesertaSurvei_id')->constrained('peserta_survei')->cascadeOnDelete()->nullable();
            $table->foreignId('tes_id')->constrained('tes')->onDelete('cascade');
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->decimal('skor', 5, 2)->nullable(); // 5 digit total, 2 di belakang koma
            $table->boolean('lulus')->default(false); // kolom baru untuk status lulus
            $table->text('pesan_kesan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percobaan');
    }
};
