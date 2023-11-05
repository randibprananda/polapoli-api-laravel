<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TimRelawanSeeder::class,
            RolePermissionSeeder::class,
            RoleTimPermissionSeeder::class,
            PropinsiSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            // KelurahanSeeder::class,
            // UserSeeder::class,
            // DetailTimRelawanSeeder::class,
            // PaslonSeeder::class,
            // DPTSeeder::class,
            // JumlahDPTSeeder::class,
            KindofissueSeeder::class,
        ]);
    }
}
