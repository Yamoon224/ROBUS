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
            $table->boolean('custumer_maghreb')->default(false);
            $table->boolean('custumer_usa')->default(false);
            $table->boolean('custumer_euro')->default(false);
            $table->boolean('custumer_centre')->default(false);
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
