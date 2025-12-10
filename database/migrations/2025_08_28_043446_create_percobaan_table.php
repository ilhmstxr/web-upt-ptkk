<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('percobaan', function (Blueprint $table) {
            $table->id();

            // tetap dua relasi ini
            $table->foreignId('peserta_id')
                ->nullable()
                ->constrained('peserta')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // snake_case biar konsisten
            $table->foreignId('peserta_survei_id')
                ->nullable()
                ->constrained('peserta_survei')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('tes_id')
                ->constrained('tes')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // kolom tambahan sesuai controller
            $table->foreignId('pelatihan_id')
                ->nullable()
                ->constrained('pelatihan')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('tipe')->nullable(); // pre-test / post-test / survei
            $table->boolean('is_legacy')->default(false);

            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->decimal('skor', 5, 2)->nullable();
            $table->boolean('lulus')->default(false);
            $table->text('pesan_kesan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('percobaan');
    }
};
