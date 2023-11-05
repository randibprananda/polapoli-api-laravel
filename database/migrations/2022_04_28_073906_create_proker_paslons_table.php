<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProkerPaslonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proker_paslons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tentang_paslon_id')->constrained()->onDelete('cascade');
            $table->text('isi_proker');
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
        Schema::dropIfExists('proker_paslons');
    }
}
