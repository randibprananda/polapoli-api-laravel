<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_barang_id')->constrained()->onDelete('cascade');
            $table->bigInteger('jumlah_pesanan');
            $table->bigInteger('jumlah_diterima')->default(0);
            $table->bigInteger('sisa_pesanan');
            $table->bigInteger('estimasi_harga_total');
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
        Schema::dropIfExists('pemesanan_barangs');
    }
}
