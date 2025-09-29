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
        Schema::table('wallet_xbets', function (Blueprint $table) {
            $table->string('code_acompte')->nullable();
            $table->string('citie_name')->nullable();
            $table->string('area_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_xbets', function (Blueprint $table) {
            //
        });
    }
};
