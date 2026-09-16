<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->decimal('commissions', 12, 2)->nullable()->after('total_fee')
                ->comment('Commission versée au sous-distributeur');
            $table->decimal('commission_companie', 12, 2)->nullable()->after('commissions')
                ->comment('Commission versée à la compagnie');
        });
    }

    public function down(): void
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->dropColumn(['commissions', 'commission_companie']);
        });
    }
};
