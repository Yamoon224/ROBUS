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
            $table->boolean('distributer_custumer')->default(false);
            $table->boolean('custumer_custumer')->default(false);
            $table->boolean('distributer_money')->default(false);
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
