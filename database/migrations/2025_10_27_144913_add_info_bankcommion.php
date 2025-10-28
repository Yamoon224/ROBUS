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
        Schema::table('bank_commissions', function (Blueprint $table) {
             $table->boolean('is_euro')->default(0);
             $table->boolean('is_asie')->default(0);
             $table->boolean('is_afrique')->default(0);
             $table->boolean('is_west')->default(0);
             $table->boolean('is_mooney')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_commissions', function (Blueprint $table) {
            //
        });
    }
};
