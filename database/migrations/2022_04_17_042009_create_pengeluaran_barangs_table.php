<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaranBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluaran_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propinsi_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('stok_barang_id')->nullable()->constrained()->onDelete('cascade');
            $table->bigInteger('jumlah_pengeluaran');
            $table->text('keterangan');
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
        Schema::dropIfExists('pengeluaran_barangs');
    }
}
