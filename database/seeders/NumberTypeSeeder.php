<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NumberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('number_types')->delete();

		$number_types = array(
			array('name' => 'Numero telephone' , 'description' => 'Le numero de telephone'),
            array('name' => 'Numero decodeur',  'description' => 'Le numero du decodeur'),
		);

		DB::table('number_types')->insert($number_types);
    }
}