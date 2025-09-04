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
        Schema::create('pelatihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')
                  ->nullable()
                  ->constrained('instansis')
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Relasi ke tabel instansis

            $table->string('nama_pelatihan');
            $table->enum('jenis_program',['akselerasi','reguler','mtu']);
            $table->string('slug')->nullable()->unique();
            $table->string('gambar')->nullable();
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
        Schema::dropIfExists('pelatihans');
    }
};
