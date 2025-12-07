<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            // Tambah created_at kalau belum ada
            if (!Schema::hasColumn('beritas', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            // Tambah updated_at kalau belum ada
            if (!Schema::hasColumn('beritas', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            if (Schema::hasColumn('beritas', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('beritas', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};
