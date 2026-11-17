<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * En production, failed_jobs.id n'est pas AUTO_INCREMENT : chaque insertion d'un job échoué
 * utilise l'id 0 et la 2e échoue (« Duplicate entry '0' for key PRIMARY »), donc les jobs
 * échoués étaient perdus. Idempotent : ne fait rien si la colonne est déjà auto-incrémentée.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('failed_jobs') || DB::getDriverName() !== 'mysql') {
            return;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM `failed_jobs` WHERE Field = 'id'");

        if ($column && stripos((string) $column->Extra, 'auto_increment') === false) {
            // Une éventuelle ligne parasite à id 0 serait renumérotée par MySQL lors du MODIFY.
            DB::statement('ALTER TABLE `failed_jobs` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }
    }

    public function down(): void
    {
        // Volontairement vide : on ne retire pas l'auto-incrément.
    }
};
