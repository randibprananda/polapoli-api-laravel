<?php

namespace Database\Seeders;

use App\Models\JumlahDpt;
use Illuminate\Database\Seeder;

class JumlahDPTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JumlahDpt::insert([
            'propinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'kelurahan_id' => 1101010001,
            'perempuan' => 100,
            'laki_laki' => 200,
            'tim_relawan_id' => 1,
        ]);
        JumlahDpt::insert([
            'propinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'kelurahan_id' => 1101010002,
            'perempuan' => 110,
            'laki_laki' => 230,
            'tim_relawan_id' => 1,
        ]);
        JumlahDpt::insert([
            'propinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'kelurahan_id' => 1101010003,
            'perempuan' => 190,
            'laki_laki' => 270,
            'tim_relawan_id' => 1,
        ]);
        JumlahDpt::insert([
            'propinsi_id' => 35,
            'kabupaten_id' => 3501,
            'kecamatan_id' => 3501010,
            'kelurahan_id' => 3501010001,
            'perempuan' => 150,
            'laki_laki' => 300,
            'tim_relawan_id' => 1,
        ]);
        JumlahDpt::insert([
            'propinsi_id' => 35,
            'kabupaten_id' => 3501,
            'kecamatan_id' => 3501010,
            'kelurahan_id' => 3501010002,
            'perempuan' => 200,
            'laki_laki' => 500,
            'tim_relawan_id' => 1,
        ]);
    }
}