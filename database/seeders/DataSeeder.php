<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            DetailTimRelawanSeeder::class,
            PaslonSeeder::class,
            DPTSeeder::class,
            JumlahDPTSeeder::class,
        ]);
    }
}