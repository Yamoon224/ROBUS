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
        Schema::table('invoice_banks', function (Blueprint $table) {
        $table->unsignedBigInteger('payment_bank_id')->nullable();
         $table->foreign('payment_bank_id')->references('id')->on('payment_banks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_banks', function (Blueprint $table) {
            //
        });
    }
};