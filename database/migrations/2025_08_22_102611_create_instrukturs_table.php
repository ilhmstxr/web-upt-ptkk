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
        Schema::create('instrukturs', function (Blueprint $table) {
            $table->id();
            // $table->string('kompetensi'); // bidang
            // $table->date('tanggal_mulai'); // pelatihan
            // $table->date('tanggal_akhir'); // pelatihan
            $table->foreignId('bidang_id')->constrained('bidangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('pelatihan')->constrained('pelatihans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_gelar');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin');
            $table->string('agama');
            $table->text('alamat_rumah');
            $table->string('no_hp');
            $table->string('instansi');
            $table->string('npwp');
            $table->string('nik');
            $table->string('nama_bank');
            $table->string('no_rekening');
            $table->string('pendidikan_terakhir');
            $table->text('pengalaman_kerja')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrukturs');
    }
};
