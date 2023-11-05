<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParpolPaslonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parpol_paslons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tentang_paslon_id')->constrained()->onDelete('cascade');
            $table->text('foto_parpol')->nullable();
            $table->string('nama_parpol')->nullable();
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
        Schema::dropIfExists('parpol_paslons');
    }
}
