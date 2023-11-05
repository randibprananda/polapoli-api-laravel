<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('real_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propinsi_id')->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained()->onDelete('cascade');
            $table->string('tps');
            $table->unsignedBigInteger('saksi_relawan_id');
            $table->foreign('saksi_relawan_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->integer('suara_sah');
            $table->integer('suara_tidak_sah');
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->text('foto_form')->nullable();
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
        Schema::dropIfExists('real_counts');
    }
}