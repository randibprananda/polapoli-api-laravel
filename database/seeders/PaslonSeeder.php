<?php

namespace Database\Seeders;

use App\Models\Paslon;
use App\Models\TentangPaslon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaslonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        Paslon::insert([
            'tim_relawan_id' => 1,
            'jenis_pencalonan' => 'Calon Presiden dan Wakil Presiden',
            'nomor_urut' => 1,
            'nama_paslon' => 'Sukarno',
            'nama_wakil_paslon' => 'H. Soekarno',
            'is_usung' => 1,
            'paslon_profile_photo' => 'https://cdn0-production-images-kly.akamaized.net/uHVwL3_d0Bv3H1x4b7kNiSWoTmI=/1200x900/smart/filters:quality(75):strip_icc():format(jpeg)/kly-media-production/medias/1320253/original/085752100_1471431406-soekarno-ganyang-malaysia.jpg',
        ]);

        TentangPaslon::insert([
            'paslon_id' => 1,
            'slug' => $faker->lexify('????????????')
        ]);
        Paslon::insert([
            'tim_relawan_id' => 1,
            'jenis_pencalonan' => 'Calon Presiden dan Wakil Presiden',
            'nomor_urut' => 2,
            'nama_paslon' => 'Soeharto',
            'nama_wakil_paslon' => 'H. Soeharto',
            'is_usung' => 0,
            'paslon_profile_photo' => 'https://upload.wikimedia.org/wikipedia/commons/5/59/President_Suharto%2C_1993.jpg',
        ]);
        TentangPaslon::insert([
            'paslon_id' => 2,
            'slug' => $faker->lexify('????????????')
        ]);
        Paslon::insert([
            'tim_relawan_id' => 1,
            'jenis_pencalonan' => 'Calon Presiden dan Wakil Presiden',
            'nomor_urut' => 3,
            'nama_paslon' => 'BJ. Habibie',
            'nama_wakil_paslon' => 'H. Habibie',
            'is_usung' => 0,
            'paslon_profile_photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Foto_Presiden_Habibie_1998.jpg/640px-Foto_Presiden_Habibie_1998.jpg',
        ]);
        TentangPaslon::insert([
            'paslon_id' => 3,
            'slug' => $faker->lexify('????????????')
        ]);
        Paslon::insert([
            'tim_relawan_id' => 1,
            'jenis_pencalonan' => 'Calon Presiden dan Wakil Presiden',
            'nomor_urut' => 4,
            'nama_paslon' => 'Jokowi',
            'nama_wakil_paslon' => 'Pr. Jokowi',
            'is_usung' => 0,
            'paslon_profile_photo' => 'https://upload.wikimedia.org/wikipedia/commons/b/be/Joko_Widodo_2019_official_portrait.jpg',
        ]);
        TentangPaslon::insert([
            'paslon_id' => 4,
            'slug' => $faker->lexify('????????????')
        ]);
        Paslon::insert([
            'tim_relawan_id' => 1,
            'jenis_pencalonan' => 'Calon Presiden dan Wakil Presiden',
            'nomor_urut' => 5,
            'nama_paslon' => 'Prabowo',
            'nama_wakil_paslon' => 'H. Tjokroto',
            'is_usung' => 0,
            'paslon_profile_photo' => 'https://img.inews.co.id/media/822/files/inews_new/2022/05/05/prabowo_subianto.jpg',
        ]);
        TentangPaslon::insert([
            'paslon_id' => 5,
            'slug' => $faker->lexify('????????????')
        ]);
    }
}
