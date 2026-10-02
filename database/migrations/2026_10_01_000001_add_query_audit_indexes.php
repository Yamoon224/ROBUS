<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index manquants relevés par l'audit des requêtes.
     * [table => [nom de l'index => colonnes]]
     */
    private function indexes(): array
    {
        $indexes = [];

        // Plafonds journaliers et totaux "du jour par opérateur" : created_by + created_at
        $walletTables = [
            'wallet_payments' => 'wpay',
            'wallet_transactions' => 'wtra',
            'wallet_banks' => 'wban',
            'wallet_deposits' => 'wdep',
            'wallet_reloads' => 'wrel',
            'wallet_phone_credits' => 'wpho',
            'wallet_inters' => 'wint',
            'wallet_xbets' => 'wxbt',
            'wallet_waves' => 'wwav',
            'wallet_djamos' => 'wdja',
            'wallet_pushes' => 'wpus',
            'wallet_tresort_money' => 'wtre',
            'wallet_versements' => 'wver',
            'wallet_bank_sends' => 'wbse',
            'bank_cards' => 'bcar',
        ];

        foreach ($walletTables as $table => $prefix) {
            $indexes[$table]["{$prefix}_created_by_created_idx"] = ['created_by', 'created_at'];
        }

        // Recherche du bénéficiaire par numéro de compte dans les historiques
        $indexes['wallet_transactions']['wtra_num_wallet_idx'] = ['num_wallet'];
        $indexes['wallet_reloads']['wrel_account_wallet_idx'] = ['account_wallet'];

        return $indexes;
    }

    public function up(): void
    {
        foreach ($this->indexes() as $table => $tableIndexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($tableIndexes as $name => $columns) {
                if (! $this->columnsExist($table, $columns) || $this->indexExists($table, $name)) {
                    continue;
                }

                $columns = $this->prefixLongColumns($table, $columns);

                Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
                    $blueprint->index($columns, $name);
                });
            }
        }
    }

    /**
     * Une colonne texte longue dépasse la taille maximale d'une clé (1000 octets
     * en MyISAM) : elle est indexée sur ses 191 premiers caractères.
     */
    private function prefixLongColumns(string $table, array $columns): array
    {
        if (DB::getDriverName() !== 'mysql') {
            return $columns;
        }

        foreach ($columns as $i => $column) {
            $type = DB::select("SHOW COLUMNS FROM `{$table}` WHERE Field = ?", [$column])[0]->Type ?? '';

            if (str_contains($type, 'text') || (preg_match('/^(var)?char\((\d+)\)/i', $type, $m) && (int) $m[2] > 191)) {
                $columns[$i] = DB::raw("`{$column}`(191)");
            }
        }

        return $columns;
    }

    public function down(): void
    {
        foreach ($this->indexes() as $table => $tableIndexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($tableIndexes as $name => $columns) {
                if ($this->indexExists($table, $name)) {
                    Schema::table($table, function (Blueprint $blueprint) use ($name) {
                        $blueprint->dropIndex($name);
                    });
                }
            }
        }
    }

    private function columnsExist(string $table, array $columns): bool
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }

    private function indexExists(string $table, string $name): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return false;
        }

        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name])) > 0;
    }
};
