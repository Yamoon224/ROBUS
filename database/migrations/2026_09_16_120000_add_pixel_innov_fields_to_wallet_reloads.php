<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_reloads', function (Blueprint $table) {
            if (!Schema::hasColumn('wallet_reloads', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (!Schema::hasColumn('wallet_reloads', 'reference')) {
                $table->string('reference')->nullable();
            }

            if (!Schema::hasColumn('wallet_reloads', 'metadata')) {
                $table->json('metadata')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Colonnes conservees pour eviter de supprimer des champs deja presents en production.
    }
};
