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
        Schema::create('versement_percents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('percent')->nullable();
            $table->double('percent_bank')->nullable();
            $table->double('percent_admin')->nullable();
            $table->double('percent_under')->nullable();
            $table->double('percent_companie')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('status')->default(1);
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
        Schema::dropIfExists('versement_percents');
    }
};
