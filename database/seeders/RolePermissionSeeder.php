<?php

namespace Database\Seeders;

use App\Models\TimRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        // Manajemen Tim Relawan
        Permission::create(['name' => 'buat_tim_relawan']);
        Permission::create(['name' => 'pembelian_akun_premium']);
        Permission::create(['name' => 'status_pembayaran_tim_relawan']);

        // Master Data
        Permission::create(['name' => 'crud_data_tps']);
        Permission::create(['name' => 'import_data_tps']);
        Permission::create(['name' => 'crud_data_jumlah_dpt']);
        Permission::create(['name' => 'import_data_jumlah_dpt']);

        // Manajemen Paslon
        Permission::create(['name' => 'crud_data_paslon']);

        // Manajemen Donasi
        Permission::create(['name' => 'crud_donasi']);
        Permission::create(['name' => 'riwayat_hasil_donasi']);
        Permission::create(['name' => 'crud_alokasi_dana']);
        Permission::create(['name' => 'create_request_wd_donasi']);
        Permission::create(['name' => 'pemberian_donasi']);

        // Manajemen Koordinator
        Permission::create(['name' => 'crud_koordinator']);
        Permission::create(['name' => 'daftar_koordinator_under_koordinator']);
        Permission::create(['name' => 'daftar_relawan_under_koordinator']);
        Permission::create(['name' => 'daftar_saksi_under_koordinator']);
        Permission::create(['name' => 'log_aktifitas_koordinator']);

        // Manajemen Relawan
        Permission::create(['name' => 'crud_relawan']);
        Permission::create(['name' => 'riwayat_kunjungan_relawan']);
        Permission::create(['name' => 'geolocation_heatmap_kunjungan_relawan']);
        Permission::create(['name' => 'riwayat_pendistribusian_logistik']);
        Permission::create(['name' => 'log_aktifitas_relawan']);

        // Manajemen Saksi
        Permission::create(['name' => 'crud_saksi']);
        Permission::create(['name' => 'lokasi_tps_under_saksi']);
        Permission::create(['name' => 'hasil_quick_count']);
        Permission::create(['name' => 'hasil_real_count']);
        Permission::create(['name' => 'log_aktifitas_saksi']);

        // Manajemen Data DPT
        Permission::create(['name' => 'crud_data_dpt']);
        Permission::create(['name' => 'crud_data_pemilih_pendukung']);

        // Simulasi Kemenangan
        Permission::create(['name' => 'data_simulasi_kemenangan']);
        Permission::create(['name' => 'perhitungan_simulasi']);

        // Manajemen Logistik
        Permission::create(['name' => 'manajemen_logistik']);
        Permission::create(['name' => 'manajemen_pemesanan_logistik']);
        Permission::create(['name' => 'manajemen_penerimaan_logistik']);
        Permission::create(['name' => 'manajemen_pengeluaran_logistik']);

        // Manajemen Feed
        Permission::create(['name' => 'crud_feed']);
        Permission::create(['name' => 'read_share_feed']);

        // Monitoring Isu
        Permission::create(['name' => 'crud_data_isu']);
        Permission::create(['name' => 'verifikasi_data_isu']);
        Permission::create(['name' => 'rekapitulasi_koordinator']);
        Permission::create(['name' => 'rekapitulasi_relawan']);
        Permission::create(['name' => 'rekapitulasi_saksi']);
        Permission::create(['name' => 'rekapitulasi_pemilih']);

        // Survey
        Permission::create(['name' => 'crud_survey']);
        Permission::create(['name' => 'crud_pertanyaan']);
        Permission::create(['name' => 'input_jawaban_survey']);
        Permission::create(['name' => 'hasil_survey']);
        Permission::create(['name' => 'geolocation']);

        // Quick Count
        Permission::create(['name' => 'perhitungan_quick_count']);
        Permission::create(['name' => 'hasil_perhitungan_quick_count']);

        // Real Count
        Permission::create(['name' => 'perhitungan_real_count']);
        Permission::create(['name' => 'hasil_perhitungan_real_count']);

        // User & Role
        Permission::create(['name' => 'crud_user_konsultan']);
        Permission::create(['name' => 'crud_role']);
        Permission::create(['name' => 'manajemen_permission']);
        Permission::create(['name' => 'pengaturan_gaji']);

        // Halaman Kemenangan
        Permission::create(['name' => 'tambah_background']);
        Permission::create(['name' => 'info_calon']);
        Permission::create(['name' => 'slug_halaman_profil_kemenangan']);
        Permission::create(['name' => 'crud_parpol']);
        Permission::create(['name' => 'tambah_visi_paslon']);
        Permission::create(['name' => 'crud_misi_paslon']);
        Permission::create(['name' => 'crud_program_kerja']);
        Permission::create(['name' => 'crud_galeri_paslon']);
        Permission::create(['name' => 'crud_contact']);
        Permission::create(['name' => 'crud_social_media']);
        Permission::create(['name' => 'view_halaman_kemenangan']);

        // Pengaturan
        Permission::create(['name' => 'ubah_profil']);
        Permission::create(['name' => 'ubah_password']);

        // Pengaturan Tim
        Permission::create(['name' => 'ubah_foto']);
        Permission::create(['name' => 'ubah_nama_tim']);
        Permission::create(['name' => 'timeline_pilkada']);
        Permission::create(['name' => 'link_youtube_sambutan_paslon']);

        $pmRole = Role::create([
            'name' => 'Project Manager',
            'guard_name' => 'api'
        ]);

        $konsultanRole = Role::create([
            'name' => 'Konsultan',
            'guard_name' => 'api'
        ]);

        $koordinatorRole = Role::create([
            'name' => 'Koordinator',
            'guard_name' => 'api'
        ]);

        $relawanRole = Role::create([
            'name' => 'Relawan',
            'guard_name' => 'api'
        ]);
        $relawanRole->syncPermissions([
            13, 31, 32, 33, 34, 40, 41, 50, 53, 55, 72, 73, 74,
        ]);


        $saksiRole = Role::create([
            'name' => 'Saksi',
            'guard_name' => 'api'
        ]);
        $saksiRole->syncPermissions([
            13, 33, 34, 40, 41, 52, 53, 54, 55, 72, 73, 74,
        ]);


        TimRole::create([
            'tim_relawan_id' => 1,
            'role_id' => 1,
            'gaji' => 0,
            'metode_gaji' => 'Per Bulan',
        ]);
        TimRole::create([
            'tim_relawan_id' => 1,
            'role_id' => 2,
            'gaji' => 0,
            'metode_gaji' => 'Per Bulan',
        ]);
        TimRole::create([
            'tim_relawan_id' => 1,
            'role_id' => 3,
            'gaji' => 0,
            'metode_gaji' => 'Per Bulan',
        ]);
        TimRole::create([
            'tim_relawan_id' => 1,
            'role_id' => 4,
            'gaji' => 0,
            'metode_gaji' => 'Per Bulan',
        ]);
        TimRole::create([
            'tim_relawan_id' => 1,
            'role_id' => 5,
            'gaji' => 0,
            'metode_gaji' => 'Per Bulan',
        ]);
    }
}