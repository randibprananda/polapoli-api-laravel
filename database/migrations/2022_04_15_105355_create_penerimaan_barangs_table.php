<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_barang_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('stok_barang_id')->nullable()->constrained()->onDelete('cascade');
            $table->bigInteger('jumlah_diterima');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('penerimaan_barangs');
    }
}
