<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('jenis_isu_id');
            $table->foreign('jenis_isu_id')
                ->references('id')
                ->on('kindof_issues')
                ->onDelete('cascade');
            $table->enum('dampak_isu', ['Positif', 'Negatif', 'Netral']);
            $table->string('tanggal_isu');
            $table->text('keterangan_isu');
            $table->string('nama_pelapor');
            $table->string('judul_isu')->nullable();
            $table->string('url_isu')->nullable();
            $table->text("foto_isu")->nullable();
            $table->foreignId('propinsi_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->nullable()->constrained()->onDelete('cascade');
            $table->text("tanggapan_isu")->nullable();
            $table->text("ditanggapi_pada")->nullable();
            $table->boolean("is_abaikan")->default(0);
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
        Schema::dropIfExists('issues');
    }
}
