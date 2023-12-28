<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('payment_types')->delete();

		$payment_types = array(
			array('name' => 'Partiel' , 'description' => 'Paiement partiel'),
            array('name' => 'Total',  'description' => 'Paiement total'),
		);

		DB::table('payment_types')->insert($payment_types);
    }
}