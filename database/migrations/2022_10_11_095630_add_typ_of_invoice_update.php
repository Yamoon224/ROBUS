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
        Schema::table('wallet_inters', function (Blueprint $table) {
            $table->string('number_decoder')->nullable();
            $table->string('id_invoice')->nullable();
            $table->string('number_contract')->nullable();
            $table->string('phone_invoice')->nullable();
            $table->string('id_invoice_pre')->nullable();
            $table->string('name')->nullable();
            $table->string('number_phone')->nullable();
            $table->string('paiementChoix_phone')->nullable();
            $table->string('paiementChoix_type')->nullable();
            $table->string('factureChoix_type')->nullable();
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_inters', function (Blueprint $table) {
            //
        });
    }
};