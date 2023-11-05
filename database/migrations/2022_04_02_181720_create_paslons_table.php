<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaslonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paslons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tim_relawan_id');
            $table->foreign('tim_relawan_id')
                ->references('id')
                ->on('tim_relawans')
                ->onDelete('cascade');
            $table->string('jenis_pencalonan');
            $table->string('nomor_urut');
            $table->string('nama_paslon');
            $table->string('nama_wakil_paslon')->nullable();
            $table->boolean('is_usung');
            $table->string('paslon_profile_photo', 2048)->nullable();
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
        Schema::dropIfExists('paslons');
    }
}
