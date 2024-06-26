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
            $table->unsignedBigInteger('wallet_xbet_id')->nullable();
            $table->foreign('wallet_xbet_id')->references('id')->on('wallet_xbets')->onDelete('cascade');
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
