<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('judul_survey');
            $table->string('tingkat_survei');
            $table->foreignId('propinsi_id')->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('dapil')->nullable();
            $table->integer('target_responden');
            $table->string('pembukaan_survey');
            $table->string('penutupan_survey');
            $table->enum('status', ['selesai', 'publish', 'draft']);
            $table->enum('flag', ['survey', 'flag']);
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
        Schema::dropIfExists('form_surveys');
    }
}
