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
    

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('transaction_id')->nullable();
            $table->unsignedBigInteger('admin_com_id')->nullable();

            $table->foreign('admin_com_id')->nullable()
                ->references('id')
                ->on('admins_coms')
                ->onDelete('cascade');



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
        Schema::dropIfExists('ledger_entries');
    }
};
