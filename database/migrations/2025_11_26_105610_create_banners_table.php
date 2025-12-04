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
        // 1) Buat tabel lengkap jika belum ada
        if (! Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('image');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);

                // timestamps lengkap agar Eloquent / Livewire tidak error saat insert/update
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });

            return;
        }

        // 2) Jika tabel sudah ada -> tambahkan kolom yang hilang (aman & nullable)
        Schema::table('banners', function (Blueprint $table) {
            // Pastikan kolom utama ada (jika tidak, tambahkan nullable agar tidak merusak data)
            if (! Schema::hasColumn('banners', 'image')) {
                $table->string('image')->nullable()->after('id');
            }
            if (! Schema::hasColumn('banners', 'title')) {
                $table->string('title')->nullable()->after('image');
            }
            if (! Schema::hasColumn('banners', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (! Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
            if (! Schema::hasColumn('banners', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }

            // timestamps: created_at / updated_at (penting untuk Eloquent / Livewire)
            if (! Schema::hasColumn('banners', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('sort_order');
            }
            if (! Schema::hasColumn('banners', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hati-hati: hanya menghapus kolom created_at/updated_at jika ada.
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('banners', 'created_at')) {
                $table->dropColumn('created_at');
            }
            // jangan drop kolom data utama secara otomatis untuk mengurangi risiko kehilangan data
        });
    }
};
