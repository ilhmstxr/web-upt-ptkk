<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DULU: nambah kolom created_at & updated_at ke tabel banners.
        // SEKARANG: kolom timestamps SUDAH dibuat langsung
        // di 2025_11_26_105610_create_banners_table,
        // jadi migration ini tidak perlu melakukan apa pun.
    }

    public function down(): void
    {
        // Karena up() tidak melakukan perubahan apa-apa,
        // down() juga dikosongkan.
    }
};
