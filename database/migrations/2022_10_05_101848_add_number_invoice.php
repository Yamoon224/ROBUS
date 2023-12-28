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
        Schema::table('wallet_payments', function (Blueprint $table) {
            // $table->string('phone')->nullable();
            // $table->string('id_invoice_pre')->nullable();
            $table->string('is_send')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_payments', function (Blueprint $table) {
            //
        });
    }
};