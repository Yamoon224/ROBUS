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
        Schema::create('invoice_phone_credits', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('body');
            $table->double('amount');
            $table->unsignedBigInteger('wallet_phone_credit_id');
            $table->foreign('wallet_phone_credit_id')->references('id')->on('wallet_phone_credits')->onDelete('cascade');
            $table->boolean('is_send')->default(false);
            $table->boolean('in_history')->default(true);
            $table->timestamp('send_at')->nullable();
            $table->timestamp('schedule_sending_to')->nullable();
            $table->integer('created_by');
            $table->integer('deleted_by')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('invoice_phone_credits');
    }
};