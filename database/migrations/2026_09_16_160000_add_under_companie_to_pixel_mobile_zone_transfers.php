<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('pixel_mobile_zone_transfers', 'commissions')) {
                $table->decimal('commissions', 12, 2)->nullable()
                    ->comment('Commission versée au sous-distributeur');
            }
            if (!Schema::hasColumn('pixel_mobile_zone_transfers', 'commission_companie')) {
                $table->decimal('commission_companie', 12, 2)->nullable()
                    ->comment('Commission versée à la compagnie');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('pixel_mobile_zone_transfers', 'commissions') ? 'commissions' : null,
                Schema::hasColumn('pixel_mobile_zone_transfers', 'commission_companie') ? 'commission_companie' : null,
            ]));
        });
    }
};
