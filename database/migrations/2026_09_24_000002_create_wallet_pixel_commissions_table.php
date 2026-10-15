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
        Schema::create('wallet_pixel_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wallet_id')->unique();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('currencie_id')->index();
            $table->unsignedBigInteger('country_id')->index();
            $table->double('amount')->default(0.0);
            $table->boolean('status')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->unsignedBigInteger('sid_user_id')->nullable()->index();
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
        Schema::dropIfExists('wallet_pixel_commissions');
    }
};
