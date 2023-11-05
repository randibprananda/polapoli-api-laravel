<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTimAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tim_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_tim_id');
            $table->string('title');
            $table->bigInteger('price');
            $table->string('periode', 25);
            $table->text('description');
            $table->timestamps();

            $table->foreign('order_tim_id')->references('id')->on('order_tims');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_tim_addons');
    }
}
