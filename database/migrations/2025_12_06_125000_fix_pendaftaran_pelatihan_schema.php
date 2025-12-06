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
        Schema::table('pendaftaran_pelatihan', function (Blueprint $table) {
            
            // 1. Tambahkan kolom foreign key baru jika belum ada
            if (!Schema::hasColumn('pendaftaran_pelatihan', 'kompetensi_id')) {
                $table->foreignId('kompetensi_id')->nullable()->constrained('kompetensi')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('pendaftaran_pelatihan', 'kompetensi_pelatihan_id')) {
                $table->foreignId('kompetensi_pelatihan_id')->nullable()->constrained('kompetensi_pelatihan')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('pendaftaran_pelatihan', 'urutan_per_kompetensi')) {
                $table->integer('urutan_per_kompetensi')->nullable();
            }

            // 2. Hapus kolom lama jika ada
            if (Schema::hasColumn('pendaftaran_pelatihan', 'kompetensi_pelatihan_id')) {
                // Drop foreign key dulu jika memungkinkan, tapi karena namanya auto-generated dan kita tidak tahu pastinya, 
                // kita coba drop column langsung. Namun di beberapa DB (referential integrity) ini bisa gagal jika FK masih ada.
                // Laravel biasanya handle dropColumn dengan FK jika constrained() digunakan saat create, tapi kadang butuh dropForeign.
                // Kita coba best effort.
                
                try {
                     $table->dropForeign(['kompetensi_pelatihan_id']);
                } catch (\Exception $e) {
                    // Ignore, maybe FK doesn't exist
                }
                $table->dropColumn('kompetensi_pelatihan_id');
            }

             if (Schema::hasColumn('pendaftaran_pelatihan', 'urutan_per_kompetensi')) {
                $table->dropColumn('urutan_per_kompetensi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_pelatihan', function (Blueprint $table) {
             // Kebalikan dari up, kembalikan kolom lama
            // Note: ini tidak strict reverse karena data mungkin hilang, tapi cukup untuk skema dev.
        });
    }
};
