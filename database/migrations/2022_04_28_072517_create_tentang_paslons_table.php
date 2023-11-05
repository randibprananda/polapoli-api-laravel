<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTentangPaslonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tentang_paslons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paslon_id')->constrained()->onDelete('cascade');
            $table->text('background')->nullable();
            $table->text('foto_calon_web_kemenangan')->nullable();
            $table->string('tema_warna')->nullable();
            $table->string('slogan')->nullable();
            $table->string('motto')->nullable();
            $table->string('slug')->nullable();
            $table->text('visi')->nullable();
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
        Schema::dropIfExists('tentang_paslons');
    }
}
