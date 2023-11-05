<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoLasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lo_las', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_survey_id')->constrained()->onDelete('cascade');
            $table->text('longitude_latitude');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_responden');
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
        Schema::dropIfExists('lo_las');
    }
}