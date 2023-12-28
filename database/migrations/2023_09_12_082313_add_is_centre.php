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
            $table->boolean('is_central_africa')->default(false);
            $table->boolean('is_maghreb')->default(false);
            $table->boolean('is_usa')->default(false);
            $table->boolean('is_euro')->default(false);
            $table->boolean('is_west_africa')->default(false);
            $table->boolean('west_africa_send_ivory')->default(false);
            $table->boolean('west_africa_send_centre_africa')->default(false);
            $table->boolean('centre_africa_send_west_africa')->default(false);
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
