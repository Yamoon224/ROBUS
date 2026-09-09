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
        Schema::table('pixel_innove_services', function (Blueprint $table) {
            $table->boolean('is_cdf')->default(false)->after('is_xaf');
            $table->boolean('is_gmd')->default(false)->after('is_cdf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pixel_innove_services', function (Blueprint $table) {
            $table->dropColumn(['is_cdf', 'is_gmd']);
        });
    }
};
