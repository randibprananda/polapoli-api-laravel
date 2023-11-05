<?php

namespace Database\Seeders;

use App\Models\DaftarAnggota;
use App\Models\DetailUser;
use App\Models\TingkatKoordinator;
use App\Models\User;
use App\Models\UserRoleTim;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pm = User::create([
            'name' => "Pola Poli",
            'username' => "pm polapoli",
            'email' => "polapoli@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "12223423434",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);

        UserRoleTim::create([
            'user_id' => $pm->id,
            'role_id' => 1,
            'tim_relawan_id' => 1,
        ]);
        $pm->timRelawans()->attach(1);


        $konsultan = User::create([
            'name' => "The Legend of Aang",
            'username' => "konsultan tloa",
            'email' => "tloa@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);
        UserRoleTim::create([
            'user_id' => $konsultan->id,
            'role_id' => 2,
            'tim_relawan_id' => 1,
        ]);
        $konsultan->timRelawans()->attach(1);

        // Relawan
        $relawan1 = User::create([
            'name' => "Relawan no Luffy",
            'username' => "relawan",
            'email' => "relawan@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);
        UserRoleTim::create([
            'user_id' => $relawan1->id,
            'role_id' => 4,
            'tim_relawan_id' => 1,
        ]);
        $relawan1->timRelawans()->attach(1);

        $detailUserRelawan1 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'kecamatan_id' => 1102022,
            'kelurahan_id' => 1102022002,
            'no_hp' => "098212836123",
            'keterangan' => "Ini Saksi",
            'user_id' => $relawan1->id,
            'jenis_kelamin' => "L",
            'rt' => "02",
            'rw' => "04",
        ]);


        // Saksi
        $saksi1 = User::create([
            'name' => "Saksi no Luffy",
            'username' => "saksi",
            'email' => "saksi@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);
        UserRoleTim::create([
            'user_id' => $saksi1->id,
            'role_id' => 5,
            'tim_relawan_id' => 1,
        ]);
        $saksi1->timRelawans()->attach(1);

        $detailUserSaksi1 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'kecamatan_id' => 1102022,
            'kelurahan_id' => 1102022002,
            'no_hp' => "098217836123",
            'keterangan' => "Ini Saksi",
            'user_id' => $saksi1->id,
            'jenis_kelamin' => "L",
            'rt' => "02",
            'rw' => "04",
            'tps' => "03",
        ]);

        $saksiRelawan = User::create([
            'name' => "Saksi no Relawan",
            'username' => "saksirelawan",
            'email' => "saksirelawan@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);
        UserRoleTim::create([
            'user_id' => $saksiRelawan->id,
            'role_id' => 4,
            'tim_relawan_id' => 1,
        ]);
        UserRoleTim::create([
            'user_id' => $saksiRelawan->id,
            'role_id' => 5,
            'tim_relawan_id' => 1,
        ]);
        $saksiRelawan->timRelawans()->attach(1);


        $detailUserSaksiRelawan1 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'kecamatan_id' => 1102022,
            'kelurahan_id' => 1102022002,
            'no_hp' => "098217836123",
            'keterangan' => "Ini Saksi dan Relawan",
            'user_id' => $saksiRelawan->id,
            'jenis_kelamin' => "P",
            'rt' => "016",
            'rw' => "04",
            'tps' => "03",
        ]);

        // Koordinator
        $koordinator1 = User::create([
            'name' => "Koordinator RT RW",
            'username' => "koordinatorrtrw",
            'email' => "koordinatorrtrw@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);

        UserRoleTim::create([
            'user_id' => $koordinator1->id,
            'role_id' => 3,
            'tim_relawan_id' => 1,
        ]);
        $koordinator1->timRelawans()->attach(1);

        $detailUser1 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'kecamatan_id' => 1102022,
            'kelurahan_id' => 1102022002,
            'no_hp' => "098217836123",
            'keterangan' => "Ini koordinator tingkat rt/rw",
            'user_id' => $koordinator1->id,
            'jenis_kelamin' => "L",
            'rt' => "02",
            'rw' => "04",
        ]);

        $tingkatKoordinator1 = TingkatKoordinator::create([
            'detail_user_id' => $detailUser1->id,
            'nama_tingkat_koordinator' => 'RT/RW',
        ]);

        DaftarAnggota::create([
            'detail_user_id' => $detailUser1->id,
            'user_id' => $saksi1->id
        ]);
        DaftarAnggota::create([
            'detail_user_id' => $detailUser1->id,
            'user_id' => $relawan1->id
        ]);
        DaftarAnggota::create([
            'detail_user_id' => $detailUser1->id,
            'user_id' => $saksiRelawan->id
        ]);

        // Koordinator 2
        $koordinator2 = User::create([
            'name' => "Koordinator Porpinsi",
            'username' => "koordinatorpropinsi",
            'email' => "koordinatorpropinsi@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);

        UserRoleTim::create([
            'user_id' => $koordinator2->id,
            'role_id' => 3,
            'tim_relawan_id' => 1,
        ]);
        $koordinator2->timRelawans()->attach(1);

        $detailUser2 = DetailUser::create([
            'propinsi_id' => 11,
            'no_hp' => "098217836123",
            'keterangan' => "Ini koordinator tingkat provinsi",
            'user_id' => $koordinator2->id,
            'jenis_kelamin' => "L",
        ]);

        $tingkatKoordinator2 = TingkatKoordinator::create([
            'detail_user_id' => $detailUser2->id,
            'nama_tingkat_koordinator' => 'Provinsi',
        ]);

        // Koordinator 3
        $koordinator3 = User::create([
            'name' => "Koordinator kecamatan",
            'username' => "koordinatorkecamatan",
            'email' => "koordinatorkecamatan@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);

        UserRoleTim::create([
            'user_id' => $koordinator3->id,
            'role_id' => 3,
            'tim_relawan_id' => 1,
        ]);
        $koordinator3->timRelawans()->attach(1);

        $detailUser3 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'kecamatan_id' => 1102022,
            'no_hp' => "098217836123",
            'keterangan' => "Ini koordinator tingkat kecamatan",
            'user_id' => $koordinator3->id,
            'jenis_kelamin' => "L",
        ]);

        $tingkatKoordinator2 = TingkatKoordinator::create([
            'detail_user_id' => $detailUser3->id,
            'nama_tingkat_koordinator' => 'Kecamatan',
        ]);

        // Koordinator 4
        $koordinator4 = User::create([
            'name' => "Koordinator kelurahan",
            'username' => "koordinatorkelurahan",
            'email' => "koordinatorkelurahan@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);

        UserRoleTim::create([
            'user_id' => $koordinator4->id,
            'role_id' => 3,
            'tim_relawan_id' => 1,
        ]);
        $koordinator4->timRelawans()->attach(1);

        $detailUser4 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'kecamatan_id' => 1102022,
            'kelurahan_id' => 1102022002,
            'no_hp' => "098217836123",
            'keterangan' => "Ini koordinator tingkat kelurahan",
            'user_id' => $koordinator4->id,
            'jenis_kelamin' => "L",
        ]);

        $tingkatKoordinator2 = TingkatKoordinator::create([
            'detail_user_id' => $detailUser4->id,
            'nama_tingkat_koordinator' => 'Kelurahan',
        ]);

        // Koordinator 5
        $koordinator5 = User::create([
            'name' => "Koordinator kabupaten",
            'username' => "koordinatorkabupaten",
            'email' => "koordinatorkabupaten@gmail.com",
            'address' => "Jl. Joyosuko Metro 4, No.50 C Merjosari, Kota Malang",
            'phonenumber' => "433243242342",
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
        ]);

        UserRoleTim::create([
            'user_id' => $koordinator5->id,
            'role_id' => 3,
            'tim_relawan_id' => 1,
        ]);
        $koordinator5->timRelawans()->attach(1);

        $detailUser5 = DetailUser::create([
            'propinsi_id' => 11,
            'kabupaten_id' => 1102,
            'no_hp' => "098217836123",
            'keterangan' => "Ini koordinator tingkat Kabupaten",
            'user_id' => $koordinator5->id,
            'jenis_kelamin' => "L",
        ]);

        $tingkatKoordinator2 = TingkatKoordinator::create([
            'detail_user_id' => $detailUser5->id,
            'nama_tingkat_koordinator' => 'Kota/Kab',
        ]);
    }
}
