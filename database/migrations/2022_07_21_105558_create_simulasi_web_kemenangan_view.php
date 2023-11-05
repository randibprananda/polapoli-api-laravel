<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSimulasiWebKemenanganView extends Migration
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
            CREATE VIEW simulasi_web_kemenangan_view AS
            SELECT
                dpt_lkpr_view.tim_relawan_id AS tim_relawan_id_left,
                dpt_lkpr_view.propinsi_id AS propinsi_id_left,
                dpt_lkpr_view.kabupaten_id AS kabupaten_id_left,
                dpt_lkpr_view.kecamatan_id AS kecamatan_id_left,
                dpt_lkpr_view.kelurahan_id AS kelurahan_id_left,
                dpt_lkpr_view.laki_laki, dpt_lkpr_view.perempuan, dpt_lkpr_view.total_lk_pr,

                dpt_pendukung_view.tim_relawan_id AS tim_relawan_id_right,
                dpt_pendukung_view.propinsi_id AS propinsi_id_right,
                dpt_pendukung_view.kabupaten_id AS kabupaten_id_right,
                dpt_pendukung_view.kecamatan_id AS kecamatan_id_right,
                dpt_pendukung_view.kelurahan_id AS kelurahan_id_right,
                dpt_pendukung_view.jml_pendukung

                    FROM dpt_lkpr_view LEFT JOIN dpt_pendukung_view ON dpt_lkpr_view.tim_relawan_id=dpt_pendukung_view.tim_relawan_id AND dpt_lkpr_view.kelurahan_id = dpt_pendukung_view.kelurahan_id

                    UNION

                    SELECT
                        dpt_lkpr_view.tim_relawan_id AS tim_relawan_id_left,
                        dpt_lkpr_view.propinsi_id AS propinsi_id_left,
                        dpt_lkpr_view.kabupaten_id AS kabupaten_id_left,
                        dpt_lkpr_view.kecamatan_id AS kecamatan_id_left,
                        dpt_lkpr_view.kelurahan_id AS kelurahan_id_left,
                        dpt_lkpr_view.laki_laki, dpt_lkpr_view.perempuan, dpt_lkpr_view.total_lk_pr,

                        dpt_pendukung_view.tim_relawan_id AS tim_relawan_id_right,
                        dpt_pendukung_view.propinsi_id AS propinsi_id_right,
                        dpt_pendukung_view.kabupaten_id AS kabupaten_id_right,
                        dpt_pendukung_view.kecamatan_id AS kecamatan_id_right,
                        dpt_pendukung_view.kelurahan_id AS kelurahan_id_right,
                        dpt_pendukung_view.jml_pendukung

                            FROM dpt_lkpr_view RIGHT JOIN dpt_pendukung_view ON dpt_lkpr_view.tim_relawan_id=dpt_pendukung_view.tim_relawan_id AND dpt_lkpr_view.kelurahan_id = dpt_pendukung_view.kelurahan_id
            SQL;
    }

    private function dropView(): string
    {
        return <<<SQL

            DROP VIEW IF EXISTS `simulasi_web_kemenangan_view`;
            SQL;
    }
}