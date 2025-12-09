<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dikosongkan.
        // Dulu: menambah kolom di tabel 'bidang'
        // Sekarang: tabel 'bidang' sudah diganti penuh menjadi 'kompetensi',
        // dan kolom 'gambar' sudah didefinisikan di migration create_kompetensi_table.
    }

    public function down(): void
    {
        // Dikosongkan juga, karena up() tidak melakukan perubahan apa pun.
    }
};

