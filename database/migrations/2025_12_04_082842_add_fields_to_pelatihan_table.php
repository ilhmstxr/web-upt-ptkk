<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pelatihan', function (Blueprint $table) {
        $table->text('syarat_ketentuan')->nullable();
        $table->text('jadwal_text')->nullable();
        $table->text('lokasi_text')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('pelatihan', function (Blueprint $table) {
        $table->dropColumn(['syarat_ketentuan', 'jadwal_text', 'lokasi_text']);
    });
}

};
