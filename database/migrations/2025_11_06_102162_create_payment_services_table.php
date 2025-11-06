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
        Schema::create('payment_services', function (Blueprint $table) {
            $table->bigIncrements('id');

               $table->unsignedBigInteger('operator_payment_id')->nullable();
                $table->foreign('operator_payment_id')
                    ->references('id')
                    ->on('operator_payments')
                    ->nullOnDelete();

                $table->unsignedBigInteger('country_id')->nullable();
                $table->foreign('country_id')
                    ->references('id')
                    ->on('countries')
                    ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_services');
    }
};
