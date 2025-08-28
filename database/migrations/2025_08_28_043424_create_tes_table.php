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
        Schema::create('tes', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            // $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');
            $table->enum('tipe', ['tes', 'survei']);
            $table->enum('sub_tipe', ['pre-test', 'post-test'])->nullable();
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->foreignId('pelatihan_id')->constrained('pelatihans')->onDelete('cascade');
            $table->integer('durasi_menit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes');
    }
};