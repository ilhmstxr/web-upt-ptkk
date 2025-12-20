<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materi_pelatihan', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelatihan (wajib)
            $table->foreignId('pelatihan_id')
                ->constrained('pelatihan')
                ->cascadeOnDelete();

            /**
             * Relasi ke sesi/kompetensi yang spesifik di pelatihan (opsional)
             * contoh: kompetensi per pelatihan punya urutan/sesi sendiri
             */
            $table->foreignId('kompetensi_pelatihan_id')
                ->nullable()
                ->constrained('kompetensi_pelatihan')
                ->nullOnDelete();

            /**
             * Relasi ke master kompetensi (opsional)
             * contoh: referensi kompetensi umum
             */
            $table->foreignId('kompetensi_id')
                ->nullable()
                ->constrained('kompetensi')
                ->nullOnDelete();

            // Data inti materi
            $table->string('judul');
            $table->enum('tipe', ['video', 'file', 'link', 'teks'])->default('file');

            $table->text('deskripsi')->nullable();

            // Konten materi (tergantung tipe)
            $table->string('file_path')->nullable();   // pdf/ppt/doc
            $table->string('video_url')->nullable();   // youtube/gdrive link
            $table->string('link_url')->nullable();    // link referensi
            $table->longText('teks')->nullable();      // kalau tipe = teks

            // Metadata urutan & estimasi
            $table->unsignedInteger('urutan')->default(1);
            $table->unsignedInteger('estimasi_hari')->nullable();

            // Publikasi
            $table->boolean('is_published')->default(true);

            $table->timestamps();

            // Opsional: biar urutan tidak dobel dalam satu pelatihan
            $table->unique(['pelatihan_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_pelatihan');
    }
};
