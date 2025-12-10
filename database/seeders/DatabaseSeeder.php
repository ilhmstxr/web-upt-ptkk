<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Database\Seeders\AsramaKamarSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AsramaKamarSeeder::class,
            AdminUserSeeder::class,
            // PelatihanSeeder::class,
            // CabangDinasSeeder::class,
            // PelatihanSeeder::class,
            // KompetensiSeeder::class,
            // PesertaSeeder::class,
            // SurveySeeder::class,
            // ProductionSeeder::class,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
        // User::create([
        //     'name' => 'Admin UPT',
        //     'email' => 'admin@upt.com',
        //     'password' => bcrypt('adminadmin'),
        // ]);

    }
}
