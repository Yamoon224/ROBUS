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
        Schema::create('auther_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_push_id')->nullable();
            $table->foreign('wallet_push_id')
                ->references('id')
                ->on('wallet_pushes')
                ->nullOnDelete();

            $table->unsignedBigInteger('wallet_djamo_id')->nullable();
            $table->foreign('wallet_djamo_id')
                ->references('id')
                ->on('wallet_djamos')
                ->nullOnDelete();

            $table->unsignedBigInteger('wallet_tresort_money_id')->nullable();
            $table->foreign('wallet_tresort_money_id')
                ->references('id')
                ->on('wallet_tresort_money')
                ->nullOnDelete();

            $table->unsignedBigInteger('wallet_versement_id')->nullable();
            $table->foreign('wallet_versement_id')
                ->references('id')
                ->on('wallet_versements')
                ->nullOnDelete();


            $table->unsignedBigInteger('sid_user_id');
            $table->foreign('sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->string('operator_transaction_name')->nullable();
            $table->string('trans_comp_id');
            $table->double('pay_amount');
            $table->double('fee_amount');
            $table->double('received_amount');
            $table->timestamp('pay_at');
            $table->integer('deleted_by')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('auther_transactions');
    }
};
