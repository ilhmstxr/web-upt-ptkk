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
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->constrained()->cascadeOnDelete(); // foreign key ke tabel pelatihan
            
            // --- Data Diri ---
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('email')->unique();

            // --- Data Instansi ---
            $table->string('asal_instansi');
            $table->text('alamat_instansi');
            $table->string('bidang_keahlian');
            $table->string('kelas');
            $table->string('cabang_dinas_wilayah');
            
            // --- Berkas & Dokumen ---
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
        Schema::dropIfExists('pesertas');
    }
};
