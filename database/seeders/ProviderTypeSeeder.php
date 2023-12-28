<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('provider_types')->delete();

		$provider_types = array(
			array('name' => 'MOBILE MONEY', 'status' => true , 'description' => 'Paiement Money'),
            array('name' => 'Facture', 'status' => true,  'description' => 'Paiement des facture'),
            array('name' => 'Banque', 'status' => true,  'description' => 'Les operations bancaires'),
		);

		DB::table('provider_types')->insert($provider_types);
    }
}