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
        Schema::create('integration_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_key')->unique();
            $table->string('api_secret');
            $table->string('webhook_url')->nullable();
            $table->string('document')->nullable();
            $table->string('juridict_form')->nullable();
            $table->string('piece_document')->nullable();
            $table->string('return_url')->nullable();
            $table->string('cancel_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('sid_user_id');
            $table->foreign('sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->json('allowed_ips')->nullable();
            $table->decimal('transaction_limit', 15, 2)->default(0);
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
        Schema::dropIfExists('integration_clients');
    }
};
