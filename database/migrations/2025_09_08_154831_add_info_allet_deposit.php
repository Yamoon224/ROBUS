<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_deposits', function (Blueprint $table) {
            $table->unsignedBigInteger('wallet_versement_id')->nullable();
            $table->foreign('wallet_versement_id')->references('id')->on('wallet_versements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_deposits', function (Blueprint $table) {
            //
        });
    }
};
