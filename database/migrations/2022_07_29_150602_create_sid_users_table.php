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
        Schema::create('sid_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade'); 
            $table->string('lastname');
            $table->longText('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique(9);
            $table->string('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('password');
            $table->integer('created_by');
            $table->integer('deleted_by')->nullable();
            $table->boolean('is_distributer')->default(false);
            $table->boolean('is_under_distributer')->default(false);
            $table->boolean('is_accounting')->default(false);
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
        Schema::dropIfExists('sid_users');
    }
};