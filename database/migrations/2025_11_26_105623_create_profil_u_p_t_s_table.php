<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada, buat langsung lengkap
        if (! Schema::hasTable('profil_u_p_t_s')) {
            Schema::create('profil_u_p_t_s', function (Blueprint $table) {
                $table->id();
                $table->string('kepala_upt_name');
                $table->string('kepala_upt_photo')->nullable();
                $table->text('sambutan')->nullable();
                $table->text('sejarah')->nullable();
                $table->text('visi')->nullable();
                $table->text('misi')->nullable();
                $table->string('alamat')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();

                // timestamps (created_at, updated_at) — penting untuk Eloquent/Livewire
                $table->timestamps();
            });

            return;
        }

        // Jika tabel sudah ada — pastikan kolom ada, jika belum tambahkan
        Schema::table('profil_u_p_t_s', function (Blueprint $table) {
            if (! Schema::hasColumn('profil_u_p_t_s', 'kepala_upt_name')) {
                $table->string('kepala_upt_name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'kepala_upt_photo')) {
                $table->string('kepala_upt_photo')->nullable()->after('kepala_upt_name');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'sambutan')) {
                $table->text('sambutan')->nullable()->after('kepala_upt_photo');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'sejarah')) {
                $table->text('sejarah')->nullable()->after('sambutan');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'visi')) {
                $table->text('visi')->nullable()->after('sejarah');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'misi')) {
                $table->text('misi')->nullable()->after('visi');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'alamat')) {
                $table->string('alamat')->nullable()->after('misi');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'email')) {
                $table->string('email')->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('profil_u_p_t_s', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            // pastikan timestamps ada
            if (! Schema::hasColumn('profil_u_p_t_s', 'created_at') || ! Schema::hasColumn('profil_u_p_t_s', 'updated_at')) {
                // Jika salah satu/tidak ada, tambahkan timestamps
                // note: ->after() kadang gagal di beberapa driver; jika error, tambahkan tanpa after()
                if (! Schema::hasColumn('profil_u_p_t_s', 'created_at')) {
                    $table->timestamp('created_at')->nullable()->after('phone');
                }
                if (! Schema::hasColumn('profil_u_p_t_s', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->after('created_at');
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('profil_u_p_t_s')) {
            return;
        }

        Schema::table('profil_u_p_t_s', function (Blueprint $table) {
            if (Schema::hasColumn('profil_u_p_t_s', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('profil_u_p_t_s', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });

        // optional: jika ingin hapus tabel seluruhnya saat rollback uncomment berikut
        // Schema::dropIfExists('profil_u_p_t_s');
    }
};
