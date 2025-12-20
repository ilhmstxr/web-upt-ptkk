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

            // ======================
            // RELASI
            // ======================
            $table->foreignId('instansi_id')
                ->nullable()
                ->constrained('instansi')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // ======================
            // ATRIBUT PELATIHAN
            // ======================
            $table->integer('angkatan')->nullable();

            $table->enum('jenis_program', ['reguler', 'akselerasi', 'mtu'])
                ->default('reguler');

            $table->string('nama_pelatihan');
            $table->string('slug')->nullable()->unique();
            $table->string('gambar')->nullable();

            $table->enum('status', ['belum dimulai', 'aktif', 'selesai'])
                ->nullable()
                ->default('belum dimulai');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->text('deskripsi')->nullable();

            $table->integer('jumlah_peserta')->nullable();

            $table->enum('sasaran', ['siswa', 'guru', 'instruktur'])->nullable();

            $table->longText('syarat_ketentuan')->nullable();
            $table->longText('jadwal_text')->nullable();
            $table->longText('lokasi_text')->nullable();
            $table->string('nama_cp')->nullable();
            $table->string('no_cp')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};
