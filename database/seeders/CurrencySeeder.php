<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->delete();

		$currencies = array(
			array('code' => 'CFA', 'status' => true, 'value' => 1 , 'name' => 'Franc CFA'),
            array('code' => 'USD', 'status' => true, 'value' => 640 , 'name' => 'Dollar des États-Unis'),
            array('code' => 'GNF', 'status' => true, 'value' => 5 , 'name' => 'Franc guinéen'),
            array('code' => 'Dt', 'status' => true, 'value' => 60 , 'name' => 'Dinar tunisien'),
            array('code' => 'DH', 'status' => true, 'value' => 350 , 'name' => 'Dirham marocain'),
            array('code' => 'EUR', 'status' => true, 'value' => 640 , 'name' => 'Euro France'),
		);

		DB::table('currencies')->insert($currencies);
    }
}