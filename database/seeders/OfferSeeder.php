<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('offers')->delete();

		$offers = array(
			array('name' => 'ACCES', 'status' => true , 'description' => 'CANAL +'),
            array('name' => 'EVASION', 'status' => true,  'description' => 'Paiement des canal'),
            array('name' => 'ESSENTIEL', 'status' => true,  'description' => 'Paiement canal'),
            array('name' => 'ACCES +', 'status' => true,  'description' => 'Paiement canal'),
            array('name' => 'EVASION', 'status' => true,  'description' => 'Paiement canal'),
		);

		DB::table('offers')->insert($offers);
    }
}