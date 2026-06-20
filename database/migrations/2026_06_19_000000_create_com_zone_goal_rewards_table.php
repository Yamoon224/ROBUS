<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('com_zone_goal_rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sid_user_id');
            $table->unsignedBigInteger('operator_service_id');
            $table->unsignedBigInteger('with_merchant_id')->nullable();
            $table->string('goal_signature', 64);
            $table->unsignedBigInteger('transactions_count');
            $table->double('transactions_amount');
            $table->double('reward_amount');
            $table->double('balance_before');
            $table->double('balance_after');
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->foreign('sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->foreign('operator_service_id')->references('id')->on('operator_services')->onDelete('cascade');
            $table->foreign('with_merchant_id')->references('id')->on('with_merchants')->nullOnDelete();
            $table->unique(['operator_service_id', 'goal_signature'], 'com_zone_goal_reward_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('com_zone_goal_rewards');
    }
};
