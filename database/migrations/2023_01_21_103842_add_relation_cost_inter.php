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
        Schema::table('wallet_inters', function (Blueprint $table) {
            $table->unsignedBigInteger('deposit_cost_id');
            $table->foreign('deposit_cost_id')->references('id')->on('deposit_costs')->onDelete('cascade');
            $table->unsignedBigInteger('bank_cost_id');
            $table->foreign('bank_cost_id')->references('id')->on('bank_costs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_inters', function (Blueprint $table) {
            //
        });
    }
};