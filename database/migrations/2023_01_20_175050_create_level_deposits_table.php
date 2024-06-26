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
        Schema::create('level_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount_begin');
            $table->string('amount_end');
            $table->double('percent')->nullable();
            $table->double('amount')->nullable();
            $table->unsignedBigInteger('deposit_cost_id');
            $table->foreign('deposit_cost_id')->references('id')->on('deposit_costs')->onDelete('cascade');
            $table->boolean('is_inter_bank')->default(false);
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
        Schema::dropIfExists('level_deposits');
    }
};