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
        Schema::create('percobaans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peserta_id')
                ->constrained('pesertas')
                ->cascadeOnDelete();

            $table->foreignId('kuis_id')
                ->constrained('kuis') // kalau tabelmu default plural, ganti jadi 'kuises'
                ->cascadeOnDelete();

            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->decimal('skor', 5, 2)->nullable(); // contoh: 100.00
            $table->text('pesan_kesan')->nullable();   // kesan/feedback dari peserta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percobaans');
    }
};
