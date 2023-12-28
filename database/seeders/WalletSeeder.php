<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallets')->delete();

        $time = Carbon::now()->toDateTimeString();

        DB::table('wallets')->insert([
            'wallet_id' => 'ROBUS_AZr125',
            'company_id' => 1,
            'currencie_id' => 1,
            'country_id' => 107,
            'amount' => 200000000,
            'status' =>true,
            'created_by' => 1,
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }
}