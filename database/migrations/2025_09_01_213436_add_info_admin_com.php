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
        Schema::table('admins_coms', function (Blueprint $table) {
            $table->unsignedBigInteger('wallet_push_id')->nullable();
            $table->foreign('wallet_push_id')->references('id')->on('wallet_pushes')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_djamo_id')->nullable();
            $table->foreign('wallet_djamo_id')->references('id')->on('wallet_djamos')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_tresort_money_id')->nullable();
            $table->foreign('wallet_tresort_money_id')->references('id')->on('wallet_tresort_money')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins_coms', function (Blueprint $table) {
            //
        });
    }
};
