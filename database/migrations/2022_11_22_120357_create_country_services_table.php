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
        Schema::create('country_services', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade');

            $table->integer('provider_id')->unsigned()->nullable();
            $table->foreign('provider_id')->references('id')
                ->on('providers')->onDelete('cascade');

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
        Schema::dropIfExists('country_services');
    }
};