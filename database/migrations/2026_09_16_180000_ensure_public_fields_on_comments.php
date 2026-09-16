<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('comments')) {
            return;
        }

        if (Schema::hasColumn('comments', 'sid_user_id')) {
            DB::statement('ALTER TABLE comments MODIFY sid_user_id BIGINT UNSIGNED NULL');
        }

        Schema::table('comments', function (Blueprint $table) {
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
    }

    public function down(): void
    {
        // Colonnes conservees pour ne pas supprimer les avis publics existants.
    }
};
