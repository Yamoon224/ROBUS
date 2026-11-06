<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $types = [
        ['name' => 'International Zone', 'description' => 'Transferts Pixel Innov zone internationale (Cash Out / Cash In)'],
        ['name' => 'Depot Zone', 'description' => 'Dépôts mobile money Pixel Innov par zone'],
    ];

    public function up(): void
    {
        foreach ($this->types as $type) {
            $exists = DB::table('provider_types')->where('name', $type['name'])->exists();

            if (!$exists) {
                DB::table('provider_types')->insert([
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('provider_types')
            ->whereIn('name', collect($this->types)->pluck('name'))
            ->delete();
    }
};
