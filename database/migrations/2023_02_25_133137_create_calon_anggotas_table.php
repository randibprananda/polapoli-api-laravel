<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalonAnggotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calon_anggotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_partai');
            $table->foreign('id_partai')->references('id')->on('partai');
            $table->string('jenis_pencalonan', 30);
            $table->string('foto');
            $table->bigInteger('no_urut');
            $table->string('nama_calon');
            $table->integer('is_usung');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calon_anggotas');
    }
}
