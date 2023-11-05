<?php

namespace Database\Seeders;

use App\Models\DPT;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DPTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = Faker::create();
        DPT::insert([
            'propinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'kelurahan_id' => 1101010001,
            'nik' =>  $faker->unique()->numerify('################'),
            'nama' => 'Nama DPT 1',
            'tempat_lahir' => 'Tempat Lahir DPT',
            'tanggal_lahir' => '2020-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat DPT',
            'tps' => 'TPS DPT',
            'rt' => '03',
            'rw' => '02',
            'tim_relawan_id' => 1,
            'is_pendukung' => 1,
            'agama' => 'Agama DPT',
            'suku' => 'Suku DPT',
            'keterangan' => 'Keterangan DPT',
            'referal_relawan' => 3,
            'no_hp' => $faker->phoneNumber(),
            'no_hp_lainnya' => $faker->phoneNumber(),
            'email' => $faker->email(),
            'foto' => 'https://imgsrv2.voi.id/F1_GNLDieziOyrURmaIv_ClKRewlqvWDK0xCRJR2w-Q/auto/1200/675/sm/1/bG9jYWw6Ly8vcHVibGlzaGVycy84MDc3My8yMDIxMDgzMTE2NDctbWFpbi5jcm9wcGVkXzE2MzA0MDMyNTUuanBn.jpg',
            'foto_ktp' => 'https://akcdn.detik.net.id/visual/2019/02/26/60057df6-b526-4732-8e75-c07948cd5e39_169.jpeg?w=650',
        ]);

        DPT::insert([
            'propinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'kelurahan_id' => 1101010001,
            'nik' =>  $faker->unique()->numerify('################'),
            'nama' => 'Nama DPT 2',
            'tempat_lahir' => 'Tempat Lahir DPT',
            'tanggal_lahir' => '2020-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat DPT',
            'tps' => 'TPS DPT',
            'rt' => '03',
            'rw' => '02',
            'tim_relawan_id' => 1,
            'is_pendukung' => 1,
            'agama' => 'Agama DPT',
            'suku' => 'Suku DPT',
            'keterangan' => 'Keterangan DPT',
            'referal_relawan' => 3,
            'no_hp' => $faker->phoneNumber(),
            'no_hp_lainnya' => $faker->phoneNumber(),
            'email' => $faker->email(),
            'foto' => 'https://imgsrv2.voi.id/F1_GNLDieziOyrURmaIv_ClKRewlqvWDK0xCRJR2w-Q/auto/1200/675/sm/1/bG9jYWw6Ly8vcHVibGlzaGVycy84MDc3My8yMDIxMDgzMTE2NDctbWFpbi5jcm9wcGVkXzE2MzA0MDMyNTUuanBn.jpg',
            'foto_ktp' => 'https://akcdn.detik.net.id/visual/2019/02/26/60057df6-b526-4732-8e75-c07948cd5e39_169.jpeg?w=650',

        ]);

        DPT::insert([
            'propinsi_id' => 35,
            'kabupaten_id' => 3501,
            'kecamatan_id' => 3501010,
            'kelurahan_id' => 3501010001,
            'nik' =>  $faker->unique()->numerify('################'),
            'nama' => 'Nama DPT 3',
            'tempat_lahir' => 'Tempat Lahir DPT',
            'tanggal_lahir' => '2020-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat DPT',
            'tps' => 'TPS DPT',
            'rt' => '03',
            'rw' => '02',
            'tim_relawan_id' => 1,
            'is_pendukung' => 1,
            'agama' => 'Agama DPT',
            'suku' => 'Suku DPT',
            'keterangan' => 'Keterangan DPT',
            'referal_relawan' => 3,
            'no_hp' => $faker->phoneNumber(),
            'no_hp_lainnya' => $faker->phoneNumber(),
            'email' => $faker->email(),
            'foto' => 'https://imgsrv2.voi.id/F1_GNLDieziOyrURmaIv_ClKRewlqvWDK0xCRJR2w-Q/auto/1200/675/sm/1/bG9jYWw6Ly8vcHVibGlzaGVycy84MDc3My8yMDIxMDgzMTE2NDctbWFpbi5jcm9wcGVkXzE2MzA0MDMyNTUuanBn.jpg',
            'foto_ktp' => 'https://akcdn.detik.net.id/visual/2019/02/26/60057df6-b526-4732-8e75-c07948cd5e39_169.jpeg?w=650',

        ]);
        DPT::insert([
            'propinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'kelurahan_id' => 1101010001,
            'nik' =>  $faker->unique()->numerify('################'),
            'nama' => 'Nama DPT 4',
            'tempat_lahir' => 'Tempat Lahir DPT',
            'tanggal_lahir' => '2020-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat DPT',
            'tps' => 'TPS DPT',
            'rt' => '03',
            'rw' => '02',
            'tim_relawan_id' => 1,
            'is_pendukung' => 0,
        ]);
        DPT::insert([
            'propinsi_id' => 35,
            'kabupaten_id' => 3501,
            'kecamatan_id' => 3501010,
            'kelurahan_id' => 3501010002,
            'nik' =>  $faker->unique()->numerify('################'),
            'nama' => 'Nama DPT 5',
            'tempat_lahir' => 'Tempat Lahir DPT',
            'tanggal_lahir' => '2020-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat DPT',
            'tps' => 'TPS DPT',
            'rt' => '03',
            'rw' => '02',
            'tim_relawan_id' => 1,
            'is_pendukung' => 0,
        ]);
    }
}