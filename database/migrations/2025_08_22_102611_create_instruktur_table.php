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
        Schema::create('instruktur', function (Blueprint $table) {
            $table->id();
            // $table->string('kompetensi'); // kompetensi
            // $table->date('tanggal_mulai'); // pelatihan
            // $table->date('tanggal_akhir'); // pelatihan
            $table->foreignId('kompetensi_id')->constrained('kompetensi')->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // $table->foreignId('kamar_id')
            //     ->constrained('kamar')
            //     ->cascadeOnDelete()->nullable();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin');
            $table->string('agama');
            $table->string('no_hp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruktur');
    }
};
