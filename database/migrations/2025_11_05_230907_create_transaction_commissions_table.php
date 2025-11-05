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
        Schema::create('transaction_commissions', function (Blueprint $table) {
           $table->bigIncrements('id');
          
        //    $table->unsignedBigInteger('wallet_commission_id')->nullable();
        //     $table->foreign('wallet_commission_id')
        //         ->references('id')
        //         ->on('wallet_commissions')
        //         ->nullOnDelete();

        //     $table->unsignedBigInteger('wallet_admin_id')->nullable();
        //     $table->foreign('wallet_admin_id')
        //         ->references('id')
        //         ->on('wallet_admins')
        //         ->nullOnDelete();

            $table->unsignedBigInteger('sid_user_id');
            $table->foreign('sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->string('type_transaction')->nullable();
            $table->string('status')->nullable();
            $table->string('amount_solde')->nullable();
            $table->string('amount_solde_after')->nullable();
            $table->string('amount_solde_befor')->nullable();
            $table->string('operator_transaction_name')->nullable();
            $table->double('pay_amount');
            $table->double('amount_received');
            $table->double('fee_amount');
            $table->double('received_amount');
            $table->timestamp('pay_at');
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('transaction_commissions');
    }
};
