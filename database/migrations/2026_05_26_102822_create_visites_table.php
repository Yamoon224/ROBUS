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
        Schema::create('visites', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->unsignedBigInteger('sidUser_id')->nullable();
            $table->foreign('sidUser_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->decimal('latitude',  10, 8)->nullable(); 
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('confirmed_lat', 10, 8)->nullable(); 
            $table->decimal('confirmed_lng', 11, 8)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->float('distance_m')->nullable(); 

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
        Schema::dropIfExists('visites');
    }
};
