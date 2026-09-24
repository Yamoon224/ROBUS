<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('ledger_entries', 'is_pixel')) {
            Schema::table('ledger_entries', function (Blueprint $table) {
                $table->boolean('is_pixel')->default(false)->after('is_wallet');
            });
        }

        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->index(['is_pixel', 'created_at'], 'ledger_entries_pixel_date_index');
        });
    }

    public function down()
    {
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropIndex('ledger_entries_pixel_date_index');
        });

        // La colonne est conservée pour protéger les installations où elle
        // existait déjà manuellement avant cette migration.
    }
};
