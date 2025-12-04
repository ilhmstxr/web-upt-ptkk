<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();

            // Relasi instansi
            $table->foreignId('instansi_id')
                  ->nullable()
                  ->constrained('instansi')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();

            // Atribut pelatihan
            $table->integer('angkatan')->nullable();
            $table->enum('jenis_program', ['reguler','akselerasi','mtu'])
                  ->default('reguler');

            // Relasi ke asrama (kolom baru yang kamu perlukan)
            $table->foreignId('asrama_id')
                  ->nullable()
                  ->constrained('asrama')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();

            // Field utama
            $table->string('nama_pelatihan');
            $table->string('slug')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->string('status')->nullable()->default('belum dimulai');

            // Tanggal
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            // Deskripsi
            $table->text('deskripsi')->nullable();

            // Data tambahan
            $table->integer('jumlah_peserta')->nullable();
            $table->enum('sasaran', ['siswa','guru','instruktur'])->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};
