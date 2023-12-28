<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'country_id' => 107,          
            'name' => 'ROBUS',
            'email'  => 'sabdydev@gmail.Com',
            'phone'  => '+2250546963728',
            'city_id'  => 1,
            'address' => 'robus 225',
            'website' => 'robus.ci',
            'status' => true,
        ]);
    }
}