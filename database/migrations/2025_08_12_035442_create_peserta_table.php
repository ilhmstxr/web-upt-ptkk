<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();

            // Relasi wajib
            $table->foreignId('user_id')
                  ->constrained('users') // Merujuk ke tabel 'users'
                  ->cascadeOnDelete();
            $table->foreignId('instansi_id')
                  ->constrained('instansi') // Merujuk ke tabel 'instansi'
                  ->cascadeOnDelete();

            // Relasi opsional: pelatihan_id
            $table->foreignId('pelatihan_id')
                  ->nullable()
                  // Diperbaiki: Merujuk secara eksplisit ke tabel 'pelatihan' (tunggal)
                  ->constrained('pelatihan') 
                  ->nullOnDelete();
                  
            // Relasi opsional: bidang_id
            $table->foreignId('bidang_id')
                  ->nullable()
                  // Diperbaiki: Merujuk secara eksplisit ke tabel 'bidang' (tunggal)
                  ->constrained('bidang') 
                  ->nullOnDelete();

            $table->string('nama', 150);
            $table->string('nik', 20)->unique();
            $table->string('jabatan', 150)->nullable(); // Menambahkan Jabatan
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama', 50);
            $table->text('alamat');
            $table->string('no_hp', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};