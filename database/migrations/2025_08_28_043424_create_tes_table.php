<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tes', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();

            $table->enum('tipe', ['post-test', 'pre-test', 'survei']);

            $table->foreignId('kompetensi_id')
                ->nullable()
                ->constrained('kompetensi')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->integer('durasi_menit')->nullable();
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tes');
    }
};
