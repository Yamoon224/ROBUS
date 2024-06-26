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
        Schema::create('wallet_xbets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->unsignedBigInteger('Onexbet_id');
            $table->foreign('Onexbet_id')->references('id')->on('Onexbets')->onDelete('cascade');
            $table->double('amount')->default(0.0);
            $table->double('commissions')->default(0);
            $table->double('commissions_admin')->default(0);
            $table->double('amount_solde')->default(0);
            $table->double('commission_masters')->default(0);
            $table->integer('updated_by')->nullable();
            $table->string('description')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('status')->default('PENDING');
            $table->string('costs_amount')->nullable();
            $table->string('countrie')->nullable();
            $table->string('citie')->nullable();
            $table->string('code_drawal')->nullable();
            $table->string('full_name')->nullable();
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
        Schema::dropIfExists('wallet_xbets');
    }
};
