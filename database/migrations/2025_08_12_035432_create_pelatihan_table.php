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
        // Diperbaiki: Menggunakan nama tabel tunggal 'pelatihan' 
        // agar sesuai dengan $table->constrained('pelatihan') di tabel 'peserta'.
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

            // Relasi ke asrama
            $table->foreignId('asrama_id')
                  ->nullable()
                  ->constrained('asrama')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();

            // Field utama
            $table->string('nama_pelatihan');
            $table->string('slug')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->enum('status',['belum dimulai','aktif','selesai'])->nullable()->default('belum dimulai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('deskripsi')->nullable();
            $table->Integer('jumlah_peserta')->nullable();
            $table->Enum('sasaran',['siswa','guru','instruktur'])->nullable();
            $table->longText('syarat_ketentuan')->nullable();
            $table->longText('jadwal_text')->nullable();
            $table->longText('lokasi_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Diperbaiki: Menghapus tabel tunggal 'pelatihan'
        Schema::dropIfExists('pelatihan');
    }
};