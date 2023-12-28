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
        Schema::table('level_countries', function (Blueprint $table) {
            $table->string('percent_under_distributer');
            $table->double('percent_admin_under_distributer')->nullable();
            $table->double('percent_deposit_under_distributer')->nullable();
            $table->double('percent_with_drawal_under_distributer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('level_countries', function (Blueprint $table) {
            //
        });
    }
};
