<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryLogistikStoksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_logistik_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_barang_id')->constrained()->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->bigInteger('stok_awal');
            $table->bigInteger('stok_akhir');
            $table->bigInteger('total_masuk')->nullable();
            $table->bigInteger('total_keluar')->nullable();
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
        Schema::dropIfExists('history_logistik_stoks');
    }
}
