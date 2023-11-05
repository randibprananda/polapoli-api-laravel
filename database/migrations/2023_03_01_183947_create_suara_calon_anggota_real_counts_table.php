<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuaraCalonAnggotaRealCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suara_calon_anggota_real_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('real_count_id');
            $table->unsignedBigInteger('paslon_id');
            $table->integer('suara_sah_paslon');
            $table->timestamps();

            $table->foreign('real_count_id')->references('id')->on('quick_counts');
            $table->foreign('paslon_id')->references('id')->on('calon_anggotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suara_calon_anggota_real_counts');
    }
}
