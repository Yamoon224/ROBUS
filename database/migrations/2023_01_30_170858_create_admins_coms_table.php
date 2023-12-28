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
        Schema::create('admins_coms', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->string('amount')->nullable();

            $table->unsignedBigInteger('wallet_transaction_id')->nullable();
            $table->foreign('wallet_transaction_id')->references('id')->on('wallet_transactions')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_bank_id')->nullable();
            $table->foreign('wallet_bank_id')->references('id')->on('wallet_banks')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_deposit_id')->nullable();
            $table->foreign('wallet_deposit_id')->references('id')->on('wallet_deposits')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_inter_id')->nullable();
            $table->foreign('wallet_inter_id')->references('id')->on('wallet_inters')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_payment_id')->nullable();
            $table->foreign('wallet_payment_id')->references('id')->on('wallet_payments')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_phone_credit_id')->nullable();
            $table->foreign('wallet_phone_credit_id')->references('id')->on('wallet_phone_credits')->onDelete('cascade');

            $table->unsignedBigInteger('wallet_reload_id')->nullable();
            $table->foreign('wallet_reload_id')->references('id')->on('wallet_reloads')->onDelete('cascade');

            
            $table->unsignedBigInteger('bank_card_id')->nullable();
            $table->foreign('bank_card_id')->references('id')->on('bank_cards')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins_coms');
    }
};
