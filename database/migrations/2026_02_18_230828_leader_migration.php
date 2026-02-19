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
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('transaction_id')->nullable();
             $table->unsignedBigInteger('wallet_admin_id');
            $table->foreign('wallet_admin_id')->references('id')->on('wallet_admins')->onDelete('cascade');
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->string('description')->nullable();
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
        Schema::table('ledger_entries', function (Blueprint $table) {
            //
        });
    }
};
