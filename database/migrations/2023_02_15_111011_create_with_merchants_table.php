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
        Schema::create('with_merchants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sid_user_id')->nullable();
            $table->foreign('sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->double('amount_with_draw')->nullable();
            $table->datetime('date_removed')->nullable();
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
        Schema::dropIfExists('with_merchants');
    }
};
