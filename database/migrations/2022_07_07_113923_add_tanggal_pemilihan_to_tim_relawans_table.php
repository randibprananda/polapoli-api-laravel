<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalPemilihanToTimRelawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tim_relawans', function (Blueprint $table) {
            $table->string('tanggal_pemilihan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tim_relawans', function (Blueprint $table) {
            $table->dropColumn('tanggal_pemilihan');
        });
    }
}