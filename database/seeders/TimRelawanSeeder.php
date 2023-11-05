<?php

namespace Database\Seeders;

use App\Models\TimRelawan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimRelawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimRelawan::insert([
            'nama_tim_relawan' => 'RRQ HOSHI',
            'photo_tim_relawan' => env('APP_URL') . '/dummy/tim-relawan-image/rrq.png',
            'created_at' => now(),
            'updated_at' => now(),
            'tanggal_pemilihan' => '2023-08-03 19:00:00',
            'link_video' => 'https://youtu.be/3dnoKIpnQqc'
        ]);
        TimRelawan::insert([
            'nama_tim_relawan' => 'EVOS LEGEND',
            'photo_tim_relawan' => env('APP_URL') . '/dummy/tim-relawan-image/evos.png',
            'created_at' => now(),
            'updated_at' => now(),
            'tanggal_pemilihan' => '2024-08-03 19:00:00',
            'link_video' => 'https://youtu.be/yt900V_Fo-A'
        ]);
    }
}
