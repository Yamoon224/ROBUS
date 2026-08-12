<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key constraint to allow nullable sid_user_id
        DB::statement('ALTER TABLE comments MODIFY sid_user_id BIGINT UNSIGNED NULL');

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('target_sid_user_id')->nullable()->after('sid_user_id');
            $table->foreign('target_sid_user_id')->references('id')->on('sid_users')->onDelete('cascade');
            $table->string('commenter_name')->nullable()->after('target_sid_user_id');
            $table->boolean('is_public')->default(false)->after('commenter_name');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['target_sid_user_id']);
            $table->dropColumn(['target_sid_user_id', 'commenter_name', 'is_public']);
        });
    }
};
