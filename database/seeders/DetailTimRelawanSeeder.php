<?php

namespace Database\Seeders;

use App\Models\DetailTimRelawan;
use Illuminate\Database\Seeder;

class DetailTimRelawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetailTimRelawan::insert([
            'pm' => 1,
            'tim_relawan_id' => 1,
        ]);
        DetailTimRelawan::insert([
            'pm' => 1,
            'tim_relawan_id' => 2,
        ]);
    }
}