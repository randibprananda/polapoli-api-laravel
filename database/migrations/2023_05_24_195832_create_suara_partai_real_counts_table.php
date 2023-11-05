<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuaraPartaiRealCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suara_partai_real_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('real_count_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger("partai_id");
            $table->foreign("partai_id")->references('id')->on("partai");
            $table->integer('suara_sah_partai');
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
        Schema::dropIfExists('suara_partai_real_counts');
    }
}
