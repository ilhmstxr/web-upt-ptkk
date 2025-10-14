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
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')
                  ->nullable()
                  ->constrained('instansi')
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Relasi ke tabel instansi

            $table->Integer('angkatan');
            $table->string('nama_pelatihan');
            $table->enum('jenis_program',['reguler','akselerasi','mtu'])->default('reguler');
            $table->string('slug')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->string('status')->nullable()->default('belum dimulai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};
