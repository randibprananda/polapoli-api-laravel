<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPengalamanKerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pengalaman_kerjas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengalaman_kerja');
            $table->foreign('id_pengalaman_kerja')->references('id')->on('pengalaman_kerjas');
            $table->text('description');
            $table->year('start');
            $table->year('end');
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
        Schema::dropIfExists('detail_pengalaman_kerjas');
    }
}
