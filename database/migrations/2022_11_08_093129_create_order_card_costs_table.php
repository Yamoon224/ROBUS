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
        Schema::create('order_card_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_card_id');
            $table->foreign('bank_card_id')->references('id')->on('bank_cards')->onDelete('cascade');
            $table->string('name');
            $table->string('percent');
            $table->string('percent_merchands');
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('status')->default(0);
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
        Schema::dropIfExists('order_card_costs');
    }
};