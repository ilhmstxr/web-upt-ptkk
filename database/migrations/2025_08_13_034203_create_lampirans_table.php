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
            $table->foreignId('peserta_id')->constrained()->cascadeOnDelete();
            $table->string('no_surat_tugas')->nullable() ;
            $table->string('fc_ktp');
            $table->string('fc_ijazah'); 
            $table->string('fc_surat_tugas')->nullable(); 
            $table->string('fc_surat_sehat')->nullable(); 
            $table->string('pas_foto'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampirans');
    }
};
