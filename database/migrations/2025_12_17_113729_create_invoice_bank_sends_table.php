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
        Schema::create('invoice_bank_sends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->double('amount');
            $table->unsignedBigInteger('wallet_bank_send_id');
            $table->foreign('wallet_bank_send_id')->references('id')->on('wallet_bank_sends')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('operator_transaction_name')->nullable();
            $table->string('pay_amount')->nullable();
            $table->string('fee_amount')->nullable();
            $table->string('received_amount')->nullable();
            $table->integer('created_by');
            $table->integer('deleted_by')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('invoice_bank_sends');
    }
};
