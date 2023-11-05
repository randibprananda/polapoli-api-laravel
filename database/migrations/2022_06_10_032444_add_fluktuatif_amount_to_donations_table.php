<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFluktuatifAmountToDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->decimal('fluktuatif_penarikan_amount', 20, 2)->default(0);
            $table->decimal('fluktuatif_alokasi_amount', 20, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('fluktuatif_penarikan_amount');
            $table->dropColumn('fluktuatif_alokasi_amount');
        });
    }
}