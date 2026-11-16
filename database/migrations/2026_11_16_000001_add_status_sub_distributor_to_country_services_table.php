<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // `status` reste le statut côté clients ; `status_sub_distributor` est celui des sous-distributeurs.
    public function up(): void
    {
        Schema::table('country_services', function (Blueprint $table) {
            $table->boolean('status_sub_distributor')->default(true)->after('status')->index();
        });

        // Reprend l'état actuel pour ne rien changer au comportement existant.
        DB::table('country_services')->update(['status_sub_distributor' => DB::raw('status')]);
    }

    public function down(): void
    {
        Schema::table('country_services', function (Blueprint $table) {
            $table->dropColumn('status_sub_distributor');
        });
    }
};
