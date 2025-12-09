<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dulu: menambah kolom is_featured ke tabel banners.
        // Sekarang: kolom is_featured sudah dibuat langsung
        // di 2025_11_26_105610_create_banners_table,
        // jadi migration ini tidak perlu melakukan apa pun.
    }

    public function down(): void
    {
        // Karena up() tidak mengubah apa-apa, down() juga kosong.
    }
};
