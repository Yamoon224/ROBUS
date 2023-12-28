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
        Schema::create('level_countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('percent_merchands');
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('percent');
            $table->double('percent_admin')->nullable();
            $table->double('percent_deposit')->nullable();
            $table->double('percent_with_drawal')->nullable();
            $table->string('status')->default(0);
            $table->boolean('euro_send_west_africa')->default(false);
            $table->boolean('euro_send_centre_africa')->default(false);
            $table->boolean('euro_send_ivory')->default(false);
            $table->boolean('euro_send_maghreb')->default(false);
            $table->boolean('euro_send_usa')->default(false);
            $table->boolean('usa_send_centre_africa')->default(false);
            $table->boolean('usa_send_ivory')->default(false);
            $table->boolean('usa_send_west_africa')->default(false);
            $table->boolean('usa_send_maghreb')->default(false);
            $table->boolean('usa_send_euro')->default(false);
            $table->boolean('maghreb_send_centre_africa')->default(false);
            $table->boolean('maghreb_send_usa')->default(false);
            $table->boolean('maghreb_send_euro')->default(false);
            $table->boolean('maghreb_send_west_africa')->default(false);
            $table->boolean('maghreb_send_ivory')->default(false);
            $table->boolean('ivoiry_send_centre_africa')->default(false);
            $table->boolean('ivoiry_send_usa')->default(false);
            $table->boolean('ivoiry_send_euro')->default(false);
            $table->boolean('custumer_maghreb')->default(false);
            $table->boolean('custumer_usa')->default(false);
            $table->boolean('custumer_euro')->default(false);
            $table->boolean('custumer_centre')->default(false);
            $table->boolean('custumer_auther')->default(false);
            $table->boolean('custumer_ivory')->default(false);
            $table->boolean('is_robus')->default(false);
            $table->boolean('distributer_custumer')->default(false);
            $table->boolean('custumer_custumer')->default(false);
            $table->boolean('distributer_money')->default(false);
            $table->boolean('west_africa_send_west_africa')->default(false);
            $table->boolean('central_africa_send_central_africa')->default(false);
            $table->boolean('ivoiry_send_west_africa')->default(false);
            $table->boolean('is_mobile_money')->default(false);
            $table->boolean('is_central_africa')->default(false);
            $table->boolean('is_maghreb')->default(false);
            $table->boolean('is_usa')->default(false);
            $table->boolean('is_euro')->default(false);
            $table->boolean('is_west_africa')->default(false);
            $table->boolean('west_africa_send_ivory')->default(false);
            $table->boolean('west_africa_send_centre_africa')->default(false);
            $table->boolean('centre_africa_send_west_africa')->default(false);
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
        Schema::dropIfExists('level_countries');
    }
};
