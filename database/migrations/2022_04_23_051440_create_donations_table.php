<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_relawan_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('donation_image');
            $table->string('donation_title');
            $table->text('donation_description');
            $table->boolean('is_target')->default(0);
            $table->boolean('is_batas')->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);
            $table->decimal('target_amount', 20, 2)->default(0);
            $table->string('batas_akhir')->nullable();
            $table->boolean('is_close')->default(0);
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
        Schema::dropIfExists('donations');
    }
}
