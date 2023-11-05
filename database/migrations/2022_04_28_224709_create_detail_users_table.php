<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('propinsi_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('no_hp');
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('keterangan')->nullable();
            $table->enum('status_invitation', ['expired', 'pending', 'active'])->default('pending');
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
        Schema::dropIfExists('detail_users');
    }
}
