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

            // $table->text('image')->nullable();

            $table->unsignedBigInteger('djamo_percent_id');
            $table->foreign('djamo_percent_id')->references('id')->on('djamo_percents')->onDelete('cascade');

            $table->unsignedBigInteger('push_percent_id');
            $table->foreign('push_percent_id')->references('id')->on('push_percents')->onDelete('cascade');

            $table->unsignedBigInteger('virement_percent_id');
            $table->foreign('virement_percent_id')->references('id')->on('versement_percents')->onDelete('cascade');

            $table->unsignedBigInteger('argent_tresort_id');
            $table->foreign('argent_tresort_id')->references('id')->on('argent_tresorts')->onDelete('cascade');

            $table->unsignedBigInteger('bank_commission_id');
            $table->foreign('bank_commission_id')->references('id')->on('bank_commissions')->onDelete('cascade');
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
