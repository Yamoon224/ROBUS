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
        Schema::create('level_bank_sends', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('amount_begin');
            $table->string('amount_end');
            $table->double('percent')->nullable();
            $table->double('amount')->nullable();
            $table->double('comission_distributer')->nullable();
            $table->double('percent_masters')->nullable();
            $table->double('percent_companie')->nullable();
            $table->double('percent_admin')->nullable();
            $table->double('percent_bank')->nullable();
            $table->unsignedBigInteger('bank_send_id');
            $table->foreign('bank_send_id')->references('id')->on('bank_sends')->onDelete('cascade');
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
        Schema::dropIfExists('level_bank_sends');
    }
};
