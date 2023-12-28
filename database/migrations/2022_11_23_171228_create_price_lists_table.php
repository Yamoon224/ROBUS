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
        Schema::create('price_lists', function (Blueprint $table) {
            $table->increments('id');
            
            $table->double('begin_amount');
            $table->double('end_amount');
            $table->double('cost_amount');
             
            $table->integer('bank_id')->unsigned()->nullable();
            $table->foreign('bank_id')->references('id')
                ->on('bank_costs')->onDelete('cascade');

            $table->integer('deposit_id')->unsigned()->nullable();
            $table->foreign('deposit_id')->references('id')
                ->on('deposit_costs')->onDelete('cascade');

            $table->integer('inter_id')->unsigned()->nullable();
            $table->foreign('inter_id')->references('id')
                ->on('inter_costs')->onDelete('cascade');


            $table->integer('order_card_id')->unsigned()->nullable();
            $table->foreign('order_card_id')->references('id')
                ->on('order_card_costs')->onDelete('cascade');


            $table->integer('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')->references('id')
                ->on('payment_costs')->onDelete('cascade');


            $table->integer('phone_credit_id')->unsigned()->nullable();
            $table->foreign('phone_credit_id')->references('id')
                ->on('phone_credit_costs')->onDelete('cascade');


            $table->integer('reload_id')->unsigned()->nullable();
            $table->foreign('reload_id')->references('id')
                ->on('reload_costs')->onDelete('cascade');


            $table->integer('transaction_id')->unsigned()->nullable();
            $table->foreign('transaction_id')->references('id')
                ->on('transaction_costs')->onDelete('cascade');


            $table->softDeletes();

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
        Schema::dropIfExists('price_lists');
    }
};