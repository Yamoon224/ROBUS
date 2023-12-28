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

        Schema::table('order_card_costs', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_type_id');
            $table->foreign('provider_type_id')->references('id')->on('	provider_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_card_costs', function (Blueprint $table) {
            //
        });
    }
};
