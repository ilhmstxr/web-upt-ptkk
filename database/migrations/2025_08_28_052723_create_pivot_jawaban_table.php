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
        Schema::create('pivot_jawaban', function (Blueprint $table) {
            // Kolom untuk ID pertanyaan yang akan menggunakan template.
            // Kolom ini juga dijadikan sebagai Primary Key.
            $table->foreignId('pertanyaan_id')
                ->primary() // Menjadikan kolom ini sebagai primary key
                ->constrained('pertanyaans') // Menambahkan foreign key ke tabel 'pertanyaans'
                ->onDelete('cascade'); // Jika pertanyaan master dihapus, link ini juga akan terhapus

            // Kolom untuk ID pertanyaan yang menjadi template/master opsi jawaban.
            $table->foreignId('template_pertanyaan_id')
                ->constrained('pertanyaans') // Menambahkan foreign key ke tabel 'pertanyaans'
                ->onDelete('cascade'); // Jika pertanyaan template dihapus, link ini juga akan terhapus

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_jawaban');
    }
};
