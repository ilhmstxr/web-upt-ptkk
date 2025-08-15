<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('biodata_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ktp_path')->nullable();
            $table->string('ijazah_path')->nullable();
            $table->string('surat_tugas_path')->nullable();
            $table->string('surat_tugas_nomor')->nullable();
            $table->string('surat_sehat_path')->nullable();
            $table->string('pas_foto_path')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // satu biodata dokumen per user
        });
    }

    public function down(): void {
        Schema::dropIfExists('biodata_dokumen');
    }
};
