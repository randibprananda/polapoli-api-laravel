<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDPTTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_p_t', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->foreignId('propinsi_id')->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained()->onDelete('cascade');
            $table->integer('dapil');
            $table->string('nik');
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('tps');
            $table->integer('rt');
            $table->integer('rw');
            $table->text('alamat');
            $table->boolean('is_pendukung')->default(0);
            $table->string('agama')->nullable();
            $table->string('suku')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('referal_relawan')->nullable();
            $table->foreign('referal_relawan')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('no_hp')->nullable();
            $table->string('no_hp_lainnya')->nullable();
            $table->string('email')->nullable();
            $table->text('foto')->nullable();
            $table->text('foto_ktp')->nullable();
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
        Schema::dropIfExists('d_p_t');
    }
}