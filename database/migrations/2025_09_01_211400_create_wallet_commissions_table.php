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
        Schema::create('wallet_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wallet_id')->unique();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('currencie_id');
            $table->foreign('currencie_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->double('amount')->default(0.0);
            $table->boolean('status')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->unsignedBigInteger('sid_user_id')->nullable();
            $table->foreign('sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
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
        Schema::dropIfExists('wallet_commissions');
    }
};
