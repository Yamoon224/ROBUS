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
        Schema::create('pushes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('percent');
            $table->integer('percent_admin')->nullable();
            $table->double('percent_masters')->nullable();
            $table->double('percent_merchands')->nullable();
            $table->double('percent_with_drawal')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->boolean('is_under_distributer')->default(0);
            $table->boolean('is_inter')->default(0);
            $table->boolean('is_reload')->default(0);
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
        Schema::dropIfExists('pushes');
    }
};
