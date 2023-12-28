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
        Schema::table('level_moneys', function (Blueprint $table) {
            $table->boolean('west_africa_send_west_africa')->default(false);
            $table->boolean('central_africa_send_central_africa')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('level_moneys', function (Blueprint $table) {
            //
        });
    }
};
