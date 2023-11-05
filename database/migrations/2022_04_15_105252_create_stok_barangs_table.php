<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->string('nama_barang');
            $table->decimal('harga_satuan', 8, 2);
            $table->string('nama_satuan');
            $table->bigInteger('stok_awal')->default(0);
            $table->bigInteger('stok_akhir')->default(0);
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
        Schema::dropIfExists('stok_barangs');
    }
}
