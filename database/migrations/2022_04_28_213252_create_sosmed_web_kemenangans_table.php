<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSosmedWebKemenangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sosmed_web_kemenangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paslon_id')->constrained()->onDelete('cascade');
            $table->string('instagram')->nullable();
            $table->string('url_instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('url_facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('url_youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('url_twitter')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('url_tiktok')->nullable();
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
        Schema::dropIfExists('sosmed_web_kemenangans');
    }
}
