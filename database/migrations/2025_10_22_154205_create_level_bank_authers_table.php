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
        Schema::create('level_bank_authers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount_begin');
            $table->string('amount_end');
            $table->double('amount')->nullable();
            $table->double('percent')->nullable();
            $table->double('percent_bank')->nullable();
            $table->double('percent_admin')->nullable();
            $table->double('percent_under')->nullable();
            $table->double('percent_companie')->nullable();
            $table->unsignedBigInteger('bank_commission_id');
            $table->foreign('bank_commission_id')->references('id')->on('bank_commissions')->onDelete('cascade');
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
        Schema::dropIfExists('level_bank_authers');
    }
};
