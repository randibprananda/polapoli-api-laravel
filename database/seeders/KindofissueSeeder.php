<?php

namespace Database\Seeders;

use App\Models\KindofIssue;
use Illuminate\Database\Seeder;

class KindofissueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KindofIssue::insert([
            'nama_jenis_isu' => 'Isu Lapangan',
        ]);
        KindofIssue::insert([
            'nama_jenis_isu' => 'Isu Media Online',
        ]);
    }
}
