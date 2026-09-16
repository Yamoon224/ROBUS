<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('operators')) {
            return;
        }

        $now = now();
        $values = [
            'name' => 'PIXEL INNOV',
            'updated_at' => $now,
        ];

        foreach (['status', 'is_under'] as $column) {
            if (Schema::hasColumn('operators', $column)) {
                $values[$column] = true;
            }
        }

        foreach (['is_commission', 'is_com', 'is_master', 'is_operator', 'is_api'] as $column) {
            if (Schema::hasColumn('operators', $column)) {
                $values[$column] = false;
            }
        }

        if (Schema::hasColumn('operators', 'deleted_at')) {
            $values['deleted_at'] = null;
        }

        $operator = DB::table('operators')->where('name', 'PIXEL INNOV')->first();

        if ($operator) {
            DB::table('operators')->where('id', $operator->id)->update($values);
            return;
        }

        $values['created_at'] = $now;

        DB::table('operators')->insert($values);
    }

    public function down(): void
    {
        // Conserve l'operateur pour ne pas casser les affectations existantes.
    }
};
