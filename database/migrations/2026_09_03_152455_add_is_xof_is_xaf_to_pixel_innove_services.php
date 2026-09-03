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
            $table->boolean('is_xof')->default(false)->after('commission_percent');
            $table->boolean('is_xaf')->default(false)->after('is_xof');
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
            $table->dropColumn(['is_xof', 'is_xaf']);
        });
    }
};
