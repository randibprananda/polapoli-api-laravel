<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuickCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quick_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->enum('metode_pengambilan', ['Tatap Muka', 'Telepon'])->nullable();
            $table->foreignId('propinsi_id')->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained()->onDelete('cascade');
            $table->string('tps');
            $table->string('nama_responden');
            $table->string('nik')->nullable();
            $table->string('usia')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_hp_lain')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('relawan_id');
            $table->foreign('relawan_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('kandidat_pilihan_id');
            $table->foreign('kandidat_pilihan_id')
                ->references('id')
                ->on('paslons')
                ->onDelete('cascade');
            $table->text('bukti')->nullable();
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
        Schema::dropIfExists('quick_counts');
    }
}