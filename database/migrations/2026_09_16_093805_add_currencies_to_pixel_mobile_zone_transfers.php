<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->string('sender_currency', 10)->nullable()->after('currency');
            $table->string('recipient_currency', 10)->nullable()->after('sender_currency');
        });
    }

    public function down()
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->dropColumn(['sender_currency', 'recipient_currency']);
        });
    }
};
