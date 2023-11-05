<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_survey_id')->constrained()->onDelete('cascade');
            $table->enum('tipe', ['TEXT', 'PILIHAN GANDA', 'CHECKLIST', 'GAMBAR']);
            $table->text('label_inputan');
            $table->json('option');
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
        Schema::dropIfExists('field_forms');
    }
}