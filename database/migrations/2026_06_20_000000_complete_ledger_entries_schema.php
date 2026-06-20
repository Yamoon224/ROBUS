<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $missing = [
            'is_admin' => ! Schema::hasColumn('ledger_entries', 'is_admin'),
            'is_comission' => ! Schema::hasColumn('ledger_entries', 'is_comission'),
            'is_uv' => ! Schema::hasColumn('ledger_entries', 'is_uv'),
            'is_wallet' => ! Schema::hasColumn('ledger_entries', 'is_wallet'),
            'currency_code' => ! Schema::hasColumn('ledger_entries', 'currency_code'),
        ];

        Schema::table('ledger_entries', function (Blueprint $table) use ($missing) {
            if ($missing['is_admin']) {
                $table->boolean('is_admin')->default(false)->after('description');
            }
            if ($missing['is_comission']) {
                $table->boolean('is_comission')->default(false)->after('is_admin');
            }
            if ($missing['is_uv']) {
                $table->boolean('is_uv')->default(false)->after('is_comission');
            }
            if ($missing['is_wallet']) {
                $table->boolean('is_wallet')->default(false)->after('is_uv');
            }
            if ($missing['currency_code']) {
                $table->string('currency_code', 3)->default('XOF')->after('credit');
            }
        });

        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropForeign(['admin_com_id']);
            $table->foreign('admin_com_id')
                ->references('id')
                ->on('admins_coms')
                ->nullOnDelete();
            $table->index('created_at', 'ledger_entries_created_at_index');
            $table->index(['is_admin', 'created_at'], 'ledger_entries_admin_date_index');
            $table->index(['is_comission', 'created_at'], 'ledger_entries_commission_date_index');
        });
    }

    public function down()
    {
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropIndex('ledger_entries_created_at_index');
            $table->dropIndex('ledger_entries_admin_date_index');
            $table->dropIndex('ledger_entries_commission_date_index');
            $table->dropForeign(['admin_com_id']);
            $table->foreign('admin_com_id')
                ->references('id')
                ->on('admins_coms')
                ->cascadeOnDelete();
        });

        // Les colonnes sont conservées pour protéger les installations où elles
        // existaient déjà manuellement avant cette migration.
    }
};
