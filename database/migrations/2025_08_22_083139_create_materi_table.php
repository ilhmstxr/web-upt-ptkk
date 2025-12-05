<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel materi
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->integer('order')->default(0)->index(); // kolom order untuk urutan
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->integer('durasi')->nullable(); // menit
            $table->longText('konten')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->timestamps();
        });

        // Tabel materi_progress
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('materi_id')->constrained('materi')->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->unique(['user_id', 'materi_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_progress');
        Schema::dropIfExists('materi');
    }
};
