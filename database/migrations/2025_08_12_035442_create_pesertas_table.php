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

            // --- Relasi ke tabel lain ---
            $table->foreignId('pelatihan_id')
                ->constrained('pelatihans')
                ->cascadeOnDelete();

            $table->foreignId('bidang_id')
                ->constrained('bidangs')
                ->cascadeOnDelete();

            $table->foreignId('instansi_id')
                ->constrained('instansis')
                ->cascadeOnDelete();

            // $table->foreignId('kamar_id')
            //     ->constrained('kamars')
            //     ->cascadeOnDelete()->nullable();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // --- Data Diri Peserta ---
            // $table->string('token')->nullable()->unique(); // token udah dirubah di tabel pendaftaran_pelatihan
            $table->string('nama', 150);
            $table->string('nik', 20)->unique();
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama', 50);
            $table->text('alamat');
            $table->string('no_hp', 20);
            
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
