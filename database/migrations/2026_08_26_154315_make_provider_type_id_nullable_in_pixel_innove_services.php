<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE pixel_innove_services MODIFY COLUMN provider_type_id BIGINT UNSIGNED NULL DEFAULT NULL');
    }

    public function down()
    {
        DB::statement('UPDATE pixel_innove_services SET provider_type_id = 0 WHERE provider_type_id IS NULL');
        DB::statement('ALTER TABLE pixel_innove_services MODIFY COLUMN provider_type_id BIGINT UNSIGNED NOT NULL');
    }
};
