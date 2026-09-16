<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pixel_innove_services', function (Blueprint $table) {
            $table->decimal('commission_under_pct', 5, 2)->default(0)->after('commission_percent')
                ->comment('% du montant versé au sous-distributeur');
            $table->decimal('commission_companie_pct', 5, 2)->default(0)->after('commission_under_pct')
                ->comment('% du montant versé à la compagnie');
        });
    }

    public function down(): void
    {
        Schema::table('pixel_innove_services', function (Blueprint $table) {
            $table->dropColumn(['commission_under_pct', 'commission_companie_pct']);
        });
    }
};
