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
        Schema::table('admins_coms', function (Blueprint $table) {
            $table->unsignedBigInteger('wallet_commission_id')->default(1);
            $table->foreign('wallet_commission_id')
                ->references('id')
                ->on('wallet_commissions')
                ->onDelete('cascade');

            $table->unsignedBigInteger('wallet_admin_id')->default(1);
            $table->foreign('wallet_admin_id')
                ->references('id')
                ->on('wallet_admins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins_coms', function (Blueprint $table) {
            //
        });
    }
};
