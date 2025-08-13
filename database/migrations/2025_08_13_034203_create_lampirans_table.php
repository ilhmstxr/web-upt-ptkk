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
        Schema::create('lampirans', function (Blueprint $table) {
            $table->id();
            // --- Berkas & Dokumen ---
            $table->unsignedBigInteger('peserta_id'); // foreign key ke tabel pesertas
            $table->foreign('peserta_id')->references('id')->on('pesertas')->cascadeOnDelete();
            $table->string('no_ktp');
            $table->string('no_surat_tugas');
            $table->string('fc_ktp'); // Path untuk file KTP
            $table->string('fc_ijazah'); // Path untuk file Ijazah
            $table->string('fc_surat_tugas'); // Path untuk file Surat Tugas
            $table->string('fc_surat_sehat'); // Path untuk file Surat Sehat
            $table->string('pas_foto'); // Path untuk pas foto

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampirans');
    }
};
