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
         Schema::create('push_percents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('percent');
            $table->integer('percent_admin')->nullable();
            $table->double('percent_masters')->nullable();
            $table->double('percent_merchands')->nullable();
            $table->double('percent_with_drawal')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('status')->default(1);
            $table->boolean('is_web')->default(false);
            $table->double('percent_web')->default(0.0);
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
        Schema::dropIfExists('push_percents');
        
    }
};
