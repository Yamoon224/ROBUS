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
        Schema::create('percent_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('percent')->default(0.00)->default(0.00);
            $table->double('percent_admin')->default(0.00);
            $table->double('percent_merchands')->default(0.00);
            $table->double('percent_deposit')->default(0.00);
            $table->double('percent_with_drawal')->default(0.00);
            $table->double('percent_web')->default(0.00);
            $table->double('deleted_by')->nullable();
            $table->double('created_by')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('percent_companies');
    }
};
