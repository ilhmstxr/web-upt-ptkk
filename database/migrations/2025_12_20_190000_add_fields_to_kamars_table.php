<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->unsignedTinyInteger('lantai')->nullable()->after('nomor_kamar');
            $table->unsignedInteger('available_beds')->default(0)->after('total_beds');
            $table->enum('status', ['Tersedia', 'Penuh', 'Rusak', 'Perbaikan'])->default('Tersedia')->after('available_beds');
        });

        DB::table('kamars')->update([
            'available_beds' => DB::raw('total_beds'),
        ]);
    }

    public function down(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn(['lantai', 'available_beds', 'status']);
        });
    }
};
