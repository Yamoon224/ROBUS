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
        Schema::table('wallet_bank_sends', function (Blueprint $table) {
            $table->string('choose_transaction')->default(1);
            $table->double('amount_solde')->default(0);
            $table->string('number_compte')->nullable();
            $table->string('phone')->nullable();
            $table->string('name_complet')->nullable();
            $table->string('num_piece')->nullable();
            $table->string('name_benef')->nullable();
            $table->string('validated_date')->nullable();
            $table->string('provider_type')->nullable();
            $table->string('choose_type_transaction')->nullable();
            $table->string('image')->nullable();
            $table->string('costs_amount')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('identity_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_bank_sends', function (Blueprint $table) {
            //
        });
    }
};
