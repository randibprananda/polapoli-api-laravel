<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDptLkprView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }



    private function createView(): string
    {
        return <<<SQL
            CREATE VIEW dpt_lkpr_view AS
                SELECT tim_relawan_id, propinsi_id, kabupaten_id, kecamatan_id, kelurahan_id, laki_laki, perempuan , (laki_laki+perempuan) AS total_lk_pr FROM `jumlah_dpts`
            SQL;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return <<<SQL

            DROP VIEW IF EXISTS `dpt_lkpr_view`;
            SQL;
    }
}