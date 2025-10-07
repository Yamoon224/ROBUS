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
        Schema::create('auther_transaction_invoices', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('body');
            $table->double('amount');
            $table->unsignedBigInteger('auther_transaction_id');
            $table->foreign('auther_transaction_id')->references('id')->on('auther_transactions')->onDelete('cascade');
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
        Schema::dropIfExists('auther_transaction_invoices');
    }
};
