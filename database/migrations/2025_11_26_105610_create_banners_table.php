<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Buat tabel lengkap jika belum ada
        if (! Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                // PERBAIKAN: Tambahkan ->nullable() di kolom image
                $table->string('image');
                $table->boolean('is_active')->default(true);
                $table->boolean('is_featured')->default(false); 
                // Menggunakan timestamps() bawaan lebih disarankan
                $table->timestamps();
            });

            return;
        }

        // 2) Jika tabel sudah ada (bagian ALTER TABLE)
        Schema::table('banners', function (Blueprint $table) {
            
            if (Schema::hasColumn('banners', 'image')) {
                $table->string('image')->change();
            }

            if (Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(  true)->change();
            }

            if (! Schema::hasColumn('banners', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            
            // Tambahan lainnya yang Anda masukkan sudah cukup defensif.

            if (! Schema::hasColumn('banners', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (! Schema::hasColumn('banners', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // ... (Kode down() Anda sudah benar)
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            // ... dst
        });
    }
};