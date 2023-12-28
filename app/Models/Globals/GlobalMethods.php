<?php

namespace App\Models\Globals;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalMethods {

    use HasFactory;
    use SoftDeletes;
    
    public function __construct() {

    }

    public function getCountryById(int $id){
        return DB::table('countries')
              ->where('id', $id)->first();
    }

    public function getCompanyById(int $id){
        return DB::table('companies')
              ->where('id', $id)->first();
    }

    public function getCurrencyByCompanyId(int $id){
        $company = DB::table('companies')
            ->where('id', $id)->first();
        return DB::table('currencies')
            ->where('id', $company->currency_id)->first();
        
    }


    public function getCurrencyById(int $id) {
        return DB::table('currencies')
          ->where('id', $id)->first();
    }

    public function getProviderById(int $id) {
        return DB::table('providers')
          ->where('id', $id)->first();
    }

    public function getProviderServiceypeById(int $id) {
        return DB::table('provider_services')
          ->where('id', $id)->first();
    }

}