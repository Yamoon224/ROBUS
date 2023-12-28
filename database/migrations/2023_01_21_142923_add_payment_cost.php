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
            $table->unsignedBigInteger('payment_cost_id');
            $table->foreign('payment_cost_id')->references('id')->on('payment_costs')->onDelete('cascade');
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