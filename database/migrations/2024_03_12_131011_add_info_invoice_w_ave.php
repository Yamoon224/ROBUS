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
        Schema::table('invoice_waves', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_wave_id');
            $table->foreign('payment_wave_id')->references('id')->on('payment_waves')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_waves', function (Blueprint $table) {
            //
        });
    }
};
