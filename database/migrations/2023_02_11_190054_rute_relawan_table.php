<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RuteRelawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rute_relawan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_survey');
            $table->unsignedBigInteger('propinsi_id');
            $table->unsignedBigInteger('kabupaten_id');
            $table->unsignedBigInteger('kecamatan_id');
            $table->unsignedBigInteger('kelurahan_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tim_relawan_id');
            $table->string('rt');
            $table->string('rw');
            $table->date('jadwal_kunjungan');
            $table->text('keterangan');
            $table->integer('dapil');
            $table->timestamps();

            $table->foreign('propinsi_id')->references('id')->on('propinsis');
            $table->foreign('kabupaten_id')->references('id')->on('kabupatens');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tim_relawan_id')->references('id')->on('tim_relawans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
