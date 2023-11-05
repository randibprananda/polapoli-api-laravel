<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTimRelawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_tim_relawans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm');
            $table->foreign('pm')
                ->references('id')->onDelete('cascade')
                ->on('users');
            $table->unsignedBigInteger('tim_relawan_id');
            $table->foreign('tim_relawan_id')
                ->references('id')->onDelete('cascade')
                ->on('tim_relawans');
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
        Schema::dropIfExists('detail_tim_relawans');
    }
}