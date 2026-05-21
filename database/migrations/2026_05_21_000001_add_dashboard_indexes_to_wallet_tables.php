<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'wallet_payments' => 'wpay',
        'wallet_transactions' => 'wtra',
        'wallet_banks' => 'wban',
        'wallet_deposits' => 'wdep',
        'wallet_reloads' => 'wrel',
        'wallet_phone_credits' => 'wpho',
        'wallet_inters' => 'wint',
        'wallet_xbets' => 'wxbt',
        'wallet_djamos' => 'wdja',
        'wallet_pushes' => 'wpus',
        'wallet_tresort_money' => 'wtre',
        'wallet_versements' => 'wver',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table => $prefix) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table, $prefix) {
                $this->indexIfColumnsExist($tableBlueprint, $table, ['created_at', 'status'], "{$prefix}_created_status_idx");
                $this->indexIfColumnsExist($tableBlueprint, $table, ['wallet_id', 'created_at', 'status'], "{$prefix}_wallet_created_status_idx");
                $this->indexIfColumnsExist($tableBlueprint, $table, ['provider_id', 'created_at', 'status'], "{$prefix}_provider_created_status_idx");
                $this->indexIfColumnsExist($tableBlueprint, $table, ['updated_by', 'created_at', 'status'], "{$prefix}_updated_created_status_idx");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table => $prefix) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table, $prefix) {
                $this->dropIndexIfColumnsExist($tableBlueprint, $table, ['created_at', 'status'], "{$prefix}_created_status_idx");
                $this->dropIndexIfColumnsExist($tableBlueprint, $table, ['wallet_id', 'created_at', 'status'], "{$prefix}_wallet_created_status_idx");
                $this->dropIndexIfColumnsExist($tableBlueprint, $table, ['provider_id', 'created_at', 'status'], "{$prefix}_provider_created_status_idx");
                $this->dropIndexIfColumnsExist($tableBlueprint, $table, ['updated_by', 'created_at', 'status'], "{$prefix}_updated_created_status_idx");
            });
        }
    }

    private function indexIfColumnsExist(Blueprint $tableBlueprint, string $table, array $columns, string $name): void
    {
        if ($this->columnsExist($table, $columns)) {
            $tableBlueprint->index($columns, $name);
        }
    }

    private function dropIndexIfColumnsExist(Blueprint $tableBlueprint, string $table, array $columns, string $name): void
    {
        if ($this->columnsExist($table, $columns)) {
            $tableBlueprint->dropIndex($name);
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
};
