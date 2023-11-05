<?php

namespace Database\Seeders;

use App\Models\RoleTimPermission;
use Illuminate\Database\Seeder;

class RoleTimPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 74; $i++) {
            RoleTimPermission::create([
                'role_id' => 1,
                'tim_relawan_id' => 1,
                'permission_id' => $i,
            ]);
        }
        for ($i = 1; $i <= 74; $i++) {
            if (
                $i != 1 && $i != 2 && $i != 3 && $i != 12
                && $i != 54 && $i != 55 && $i != 56 && $i != 57
                && $i != 71 && $i != 72 && $i != 73 && $i != 74
            ) {
                RoleTimPermission::create([
                    'role_id' => 2,
                    'tim_relawan_id' => 1,
                    'permission_id' => $i,
                ]);
            }
        }
        for ($i = 1; $i <= 74; $i++) {
            if (
                $i == 13 || $i == 15 || $i == 16 || $i == 17
                || $i == 18 || $i == 23 || $i == 28 || $i == 21
                || $i == 22 || $i == 25 || $i == 26 || $i == 27
                || $i == 29 || $i == 30 || $i == 31 || $i == 32
                || $i == 33 || $i == 34 || $i == 35 || $i == 36
                || $i == 38 || $i == 39 || $i == 47 || $i == 48
                || $i == 49 || $i == 51 || $i == 53 || $i == 68
                || $i == 69 || $i == 70
            ) {
                RoleTimPermission::create([
                    'role_id' => 3,
                    'tim_relawan_id' => 1,
                    'permission_id' => $i,
                ]);
            }
        }
        for ($i = 1; $i <= 74; $i++) {
            if (
                $i == 13 || $i == 29 || $i == 30 || $i == 31
                || $i == 32 || $i == 38 || $i == 39 || $i == 48
                || $i == 51 || $i == 53 || $i == 68 || $i == 69
                || $i == 70

            ) {
                RoleTimPermission::create([
                    'role_id' => 4,
                    'tim_relawan_id' => 1,
                    'permission_id' => $i,
                ]);
            }
        }
        for ($i = 1; $i <= 74; $i++) {
            if (
                $i == 13 || $i == 31
                || $i == 32 || $i == 38 || $i == 39 || $i == 50
                || $i == 51 || $i == 52 || $i == 53 || $i == 68 || $i == 69
                || $i == 70

            ) {
                RoleTimPermission::create([
                    'role_id' => 5,
                    'tim_relawan_id' => 1,
                    'permission_id' => $i,
                ]);
            }
        }
    }
}