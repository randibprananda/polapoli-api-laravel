<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDptPendukungView extends Migration
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
            CREATE VIEW dpt_pendukung_view AS
                SELECT tim_relawan_id, propinsi_id, kabupaten_id, kecamatan_id, kelurahan_id, IFNULL(SUM(is_pendukung), 0) AS jml_pendukung
                    FROM d_p_t GROUP BY kelurahan_id, tim_relawan_id
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

            DROP VIEW IF EXISTS `dpt_pendukung_view`;
            SQL;
    }
}