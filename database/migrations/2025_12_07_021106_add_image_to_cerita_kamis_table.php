<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom image kalau belum ada
        if (! Schema::hasColumn('cerita_kamis', 'image')) {
            Schema::table('cerita_kamis', function (Blueprint $table) {
                $table->string('image')
                    ->nullable()
                    ->after('slug'); // boleh diganti posisinya, bebas
            });
        }
    }

    public function down(): void
    {
        Schema::table('cerita_kamis', function (Blueprint $table) {
            if (Schema::hasColumn('cerita_kamis', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
