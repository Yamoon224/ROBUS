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
        Schema::create('wallet_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->unsignedBigInteger('bank_cost_id');
            $table->foreign('bank_cost_id')->references('id')->on('bank_costs')->onDelete('cascade');
            $table->double('amount')->default(0.0);
            $table->string('description')->nullable();
            $table->string('name_custumer')->nullable();
            $table->string('image')->nullable();
            $table->string('validated_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('name_benef')->nullable();
            $table->string('phone_benef')->nullable();
            $table->string('name_bank')->nullable();
            $table->string('num_bank')->nullable();
            $table->string('auther_code')->nullable();
            $table->double('commissions')->default(0.0);
            $table->double('amount_received')->default(0.0);
            $table->double('amount_solde')->default(0.0);
            $table->string('costs_amount')->nullable();
            $table->double('commissions_admin')->default(0.0);
            $table->double('commission_companie')->default(0.0);
            $table->double('commission_masters')->default(0);
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('status')->default('PENDING');
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
        Schema::dropIfExists('wallet_banks');
    }
};