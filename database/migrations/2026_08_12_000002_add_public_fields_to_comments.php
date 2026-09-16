<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('comments')) {
            return;
        }

        if (Schema::hasColumn('comments', 'sid_user_id')) {
            DB::statement('ALTER TABLE comments MODIFY sid_user_id BIGINT UNSIGNED NULL');
        }

        $shouldAddTargetForeign = !Schema::hasColumn('comments', 'target_sid_user_id');

        Schema::table('comments', function (Blueprint $table) use ($shouldAddTargetForeign) {
            if (!Schema::hasColumn('comments', 'target_sid_user_id')) {
                $table->unsignedBigInteger('target_sid_user_id')->nullable()->after('sid_user_id');
            }

            if (!Schema::hasColumn('comments', 'commenter_name')) {
                $table->string('commenter_name')->nullable()->after('target_sid_user_id');
            }

            if (!Schema::hasColumn('comments', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('commenter_name');
            }
        });

        if ($shouldAddTargetForeign) {
            Schema::table('comments', function (Blueprint $table) {
                $table->foreign('target_sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('comments')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'target_sid_user_id')) {
                $table->dropForeign(['target_sid_user_id']);
            }

            $columns = array_filter([
                Schema::hasColumn('comments', 'target_sid_user_id') ? 'target_sid_user_id' : null,
                Schema::hasColumn('comments', 'commenter_name') ? 'commenter_name' : null,
                Schema::hasColumn('comments', 'is_public') ? 'is_public' : null,
            ]);

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
