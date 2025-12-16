<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->string('warna', 16)->default('#1524AF')->after('nama_pelatihan');
            $table->string('warna_inactive', 16)->default('#000000')->after('warna');
        });
    }

    public function down(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn(['warna', 'warna_inactive']);
        });
    }
};
