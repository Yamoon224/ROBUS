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
        Schema::table('wallet_deposits', function (Blueprint $table) {
                $table->string("num_carte"); 
                 $table->string("firstanme"); 
                 $table->string("lastname"); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_deposits', function (Blueprint $table) {
            //
        });
    }
};
