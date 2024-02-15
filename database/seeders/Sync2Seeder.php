<?php

namespace Database\Seeders;

use App\Models\Custumers\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Sync2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('users')->delete();

        $datas = file_get_contents(base_path('app/Data/ch.json'));
        $datas = json_decode($datas, true);
        
        foreach ($datas as $key => $value) {

            if($key >= 0){
                Log::info('Start ' . $key);
                $citie = City::where('name', (String)$value['name'])->first();
                if(empty($citie)){
                    $citie = new City();
                    Log::info('New ' . $value['name']);
                } else {
                    $citie = City::find($citie->id);
                    Log::info($citie->name . ' ' . $value['name']);
                }
                
                $citie->name = $value['name'];
                $citie->lat = $value['lat'];
                $citie->ing = $value['lng'];
                $citie->population = $value['population'];
                $citie->population_proper = $value['population_proper'];
                $citie->country_id = $value['country_id'];
                $citie->admin_name = $value['admin_name'];
                $citie->created_at = now();
                $citie->updated_at = now();
                
                
            }
            $citie->save();
        }
    }

}