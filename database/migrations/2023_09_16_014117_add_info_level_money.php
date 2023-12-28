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
            $table->boolean('usa_send_centre_africa')->default(false);
            $table->boolean('usa_send_ivory')->default(false);
            $table->boolean('usa_send_west_africa')->default(false);
            $table->boolean('usa_send_maghreb')->default(false);
            $table->boolean('usa_send_euro')->default(false);
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
