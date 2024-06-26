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
        Schema::table('transaction_costs', function (Blueprint $table) {
            $table->double('percent_admin')->nullable();
            $table->double('percent_deposit')->nullable();
            $table->double('percent_with_drawal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_costs', function (Blueprint $table) {
            //
        });
    }
};