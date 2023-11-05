<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuaraPaslonRealCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suara_paslon_real_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('real_count_id')->constrained()->onDelete('cascade');
            $table->foreignId('paslon_id')->constrained()->onDelete('cascade');
            $table->integer('suara_sah_paslon');
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
        Schema::dropIfExists('suara_paslon_real_counts');
    }
}